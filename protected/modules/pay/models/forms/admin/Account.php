<?php
namespace pay\models\forms\admin;

class Account extends \CFormModel
{
  private $account;
  
  public $EventId;
  public $EventTitle;
  public $Own;
  public $OrderTemplateName;
  public $ReturnUrl;
  public $Offer;
  public $OfferFile;
  public $OrderLastTime;
  public $OrderEnable;
  public $Uniteller;
  public $PayOnline;
  
  /**
   * 
   * @param \pay\models\Account $account
   * @param string $scenario
   */
  public function __construct($account, $scenario = '')
  {
    parent::__construct($scenario);
    $this->account = $account;
  }
  
  public function rules()
  {
    return [
      ['EventId,EventTitle,Own,OrderEnable', 'required'],
      ['OrderTemplateName, Offer', 'type', 'type' => 'string', 'allowEmpty' => true],
      ['ReturnUrl', 'url', 'allowEmpty' => true],
      ['OrderLastTime', 'date', 'format' => 'dd.MM.yyyy', 'allowEmpty' => true],
      ['OfferFile', 'file', 'types' => 'pdf,doc,docx', 'allowEmpty' => true],
      ['EventId', 'filter', 'filter' => [$this, 'filterEventId']],
      ['Uniteller,PayOnline', 'numerical', 'max' => 1, 'min' => 1, 'allowEmpty' => true]
    ];
  }
  
  public function getAccount()
  {
    return $this->account;
  }
  
  public function attributeLabels()
  {
    return [
      'EventId' => \Yii::t('app', 'ID мероприятия'),
      'EventTitle' => \Yii::t('app', 'Название мероприятия'),
      'Own' => \Yii::t('app', 'Собственное мероприятие'),
      'OrderTemplateName' => \Yii::t('app', 'Шаблон для счетов'),
      'ReturnUrl' => \Yii::t('app', 'URL после оплаты'),
      'Offer' => \Yii::t('app', 'Оферта'),
      'OfferFile' => \Yii::t('app', 'Файл с офертой'),
      'OrderLastTime' => \Yii::t('app', 'Последняя дата выставления счета'),
      'OrderEnable' => \Yii::t('app', 'Разрешить выставлять счета'),
      'Uniteller' => \Yii::t('app', 'Использовать платежную систему Uniteller'),
      'PayOnline' => \Yii::t('app', 'Использовать платежную систему PayOnline'),
      'PaySystem' => \Yii::t('app', 'Платежная система')
    ];
  }
  
  public function filterEventId($value)
  {
    if ($this->account->getIsNewRecord() && !$this->hasErrors('EventId'))
    {
      $account = \pay\models\Account::model()->byEventId($this->EventId)->find();
      if ($account !== null)
      {
        $this->addError('EventId', \Yii::t('app', 'Платежный аккаунт для этого мероприятия уже существует. Для его редактирования перейдите по <a href="{link}">ссылке</a>.', [
          '{link}' => \Yii::app()->getController()->createUrl('/pay/admin/account/edit', ['accountId' => $account->Id])
        ]));
      }
    }
    return $value;
  }
  
  public function getOrderTemplateNameData()
  {
    return $this->getData(\Yii::getPathOfAlias('pay.views.order.bills'));
  }
  
  public function getOfferPath()
  {
    return \Yii::getPathOfAlias('webroot.docs.offers');
  }
  
  public function getOfferData()
  {
    $data = $this->getData($this->getOfferPath(), true);
    unset($data['base']);
    return $data;
  }
  
  private function getData($path, $showExtension = false)
  {
    $data = ['' => \Yii::t('app', 'По умолчанию')];
    foreach (new \DirectoryIterator($path) as $file)
    {
      if ($file->isFile())
      {
        $name = $showExtension ? $file->getBasename() : $file->getBasename('.'.$file->getExtension());
        $data[$name] = $name;
      }
    }
    return $data;
  }
}
