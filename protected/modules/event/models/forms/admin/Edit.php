<?php
namespace event\models\forms\admin;

class Edit extends \CFormModel
{
  const DATE_FORMAT = 'dd.MM.yyyy';
  
  public $Title;
  public $IdName;
  public $Info;
  public $FullInfo;
  public $Visible;
  public $TypeId;
  public $ShowOnMain;
  public $Approved = 0;
  public $Free;
  public $Top;
  
  public $StartDate;
  public $EndDate;
  public $StartDateTS;
  public $EndDateTS;
  
  public $Logo;

  public $SiteUrl;
  
  public $Widgets;
  
  public $ProfInterest = [];
  
  public $Address;

  public function rules()
  {
    return [
      ['Title, IdName, Info, StartDate, EndDate', 'required'],
      ['Free, Top', 'numerical', 'allowEmpty' => true],
      ['StartDate', 'date', 'format' => self::DATE_FORMAT, 'timestampAttribute' => 'StartDateTS'],
      ['EndDate', 'date', 'format' => self::DATE_FORMAT, 'timestampAttribute' => 'EndDateTS'],
      ['Info', 'filter', 'filter' => array(new \application\components\utility\Texts(), 'filterPurify')],
      ['Title, IdName, Info, FullInfo, Visible, TypeId, ShowOnMain, Approved, Widgets, ProfInterest, SiteUrl', 'safe'],
      ['Logo', 'file', 'types' => 'jpg, gif, png', 'allowEmpty' => true]
    ];
  }
  
  public function attributeLabels()
  {
    return [
      'Title' => \Yii::t('app', 'Название'),
      'Info' => \Yii::t('app', 'Краткая информация'),
      'FullInfo' => \Yii::t('app', 'Информация'),
      'Date' => \Yii::t('app', 'Дата проведения'),
      'Type' => \Yii::t('app', 'Тип'),
      'Visible' => \Yii::t('app', 'Публиковать'),
      'ShowOnMain' => \Yii::t('app', 'Публиковать на главной'),
      'Widgets' => \Yii::t('app', 'Виджеты'),
      'ProfInterest' => \Yii::t('app', 'Профессиональные интересы'),
      'Approved' => \Yii::t('app', 'Статус'),
      'Logo' => \Yii::t('app', 'Лого'),
      'SiteUrl' => \Yii::t('app', 'URl сайта'),
      'Address' => \Yii::t('app', 'Адрес'),
      'Free' => \Yii::t('app', 'Бесплатное мероприятие'),
      'Top' => \Yii::t('app', 'Выделить в блок'),
      'StartDate' => \Yii::t('app', 'Дата начала'),
      'EndDate' => \Yii::t('app', 'Дата окончания')
    ];
  }
  
  public function __construct($scenario = '')
  {
    $this->Address = new \contact\models\forms\Address();
    return parent::__construct($scenario);
  }


  public function validate($attributes = null, $clearErrors = true)
  {
    $this->Address->attributes = \Yii::app()->request->getParam(get_class($this->Address));
    if (!$this->Address->validate())
    {
      foreach ($this->Address->getErrors() as $messages)
      {
        $this->addError('Address', $messages[0]);
      }
    }
    return parent::validate($attributes, false);
  }
}
