<?php
namespace pay\models\forms;

class Product extends \CFormModel
{
  private $product;
  
  public $Id;
  public $Title;
  public $Public;
  public $Priority;
  public $EnableCoupon;
  public $Unit;
  public $ManagerName;
  public $Attributes = [];
  public $Prices = [];
  public $Delete;

  public function __construct(\pay\models\Product $product = null, $scenario = '')
  {
    parent::__construct($scenario);
    if ($product !== null)
    {
      foreach ($product->getAttributes() as $attr => $value)
      {
        if (in_array($attr, $this->attributeNames()))
          $this->$attr = $value;
      }
      $this->product = $product;
    }
  }
  
  public function setAttributes($values, $safeOnly = true)
  {
    if (isset($values['Prices']))
    {
      foreach ($values['Prices'] as $value)
      {
        $form = new \pay\models\forms\ProductPrice();
        $form->attributes = $value;
        $this->Prices[] = $form;
      }
      unset($values['Prices']);
    }

    if (!empty($values['Id']))
    {
      $this->product = \pay\models\Product::model()->findByPk($values['Id']);
    }
    parent::setAttributes($values, $safeOnly);
  }
  
  public function filterAttributes($attributes)
  {
    if (!empty($this->product))
    {
      $manager = $this->getProduct()->getManager();
      foreach ($manager->getProductAttributeNames() as $name)
      {
        if (!isset($attributes[$name]) || empty($attributes[$name]))
          $this->addError('Attributes', \Yii::t('app', 'Не указан атрибут обязательный товара').' '.$name);
      }
    }
    return $attributes;
  }
  
  public function filterPrices($prices)
  {
    $valid = true;
    foreach ($prices as $price)
    {
      if (!$price->validate())
      {
        $valid = false;
      }
    }
    if (!$valid)
    {
      $this->addError('Prices', \Yii::t('app', 'Ошибка в заполнении цен'));
    }
    else
    {
      $lastEndDate = new \DateTime();
      foreach ($prices as $i => $price)
      {
        $curStartDate = new \DateTime($price->StartDate);
        if ((empty($price->EndDate) && isset($prices[$i+1]))
          || ($i != 0 && $curStartDate->modify('-1 day') != $lastEndDate))
        {
          $this->addError('Prices', \Yii::t('app', 'Нарушена непрерывность цен'));
          break;
        }
        $lastEndDate->setTimestamp(strtotime($price->EndDate));
      }
    }
    return $prices;
  }
  
  public function clearPrices()
  {
    foreach ($this->Prices as $i => $formPrice)
    {
      if (!empty($formPrice->Delete))
      {
        if (!empty($formPrice->Id))
        {
          $price = \pay\models\ProductPrice::model()->findByPk($this->Prices[$i]->Id);
          if ($price !== null && $price->ProductId == $this->getProduct()->Id)
            $price->delete();
        }
        unset($this->Prices[$i]);
      }
    }
  }


  public function getProduct()
  {
    return $this->product;
  }
  
  public function getManagerData()
  {
    return [
      'EventProductManager' => \Yii::t('app', 'Мероприятие'),
      'FoodProductManager' => \Yii::t('app', 'Питание')
    ];
  }
  
  public function getManagerTitle()
  {
    return $this->getManagerData()[$this->ManagerName];
  }


  public function getPriorityData()
  {
    $result = [];
    for ($i = 0; $i <= 100; $i++)
      $result[] = $i;
    
    return $result;
  }
  
  public function rules()
  {
    return [
      ['Id,Public,Priority,EnableCoupon,Delete', 'safe'],
      ['Title,ManagerName,Unit', 'required'],
      ['Prices', 'filter', 'filter' => array($this, 'filterPrices')],
      ['Attributes', 'filter', 'filter' => array($this, 'filterAttributes')]
    ];
  }
  
  public function attributeLabels()
  {
    return [
      'Title' => \Yii::t('app', 'Название'),
      'Public' => \Yii::t('app', 'Отображение'),
      'Priority' => \Yii::t('app', 'Приоритет'),
      'ManagerName' => \Yii::t('app', 'Менеджер'),
      'Attributes' => \Yii::t('app', 'Параметры'),
      'Prices' => \Yii::t('app', 'Цены'),
      'Unit' => \Yii::t('app', 'Ед. измерения'),
      'EnableCoupon' => \Yii::t('app', 'Разрешить промо-коды')
    ];
  }
}
