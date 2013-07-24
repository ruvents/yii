<?php
namespace partner\controllers\user;

class ExportAction extends \partner\components\Action 
{
  private $csvDelimiter = ';';
  private $csvCharset = 'utf8';
  private $language = 'ru';
  
  public function run()
  {
    if (\Yii::app()->request->getIsPostRequest())
    {
      $this->export();
    }

    /** @var $roles \event\models\Role[] */
    $roles = \event\models\Role::model()
        ->byEventId(\Yii::app()->partner->getAccount()->EventId)->findAll();
    
    $this->getController()->setPageTitle('Экспорт участников в CSV');
    $this->getController()->initActiveBottomMenu('export');
    $this->getController()->render('export', array('roles' => $roles));
  }

  private function export()
  {
    ini_set("memory_limit", "512M");

    if (\Yii::app()->partner->getAccount()->EventId == 391)
    {
      \Yii::app()->language = 'en';
    }

    $request = \Yii::app()->getRequest();
    $this->csvCharset = $request->getParam('charset', $this->csvCharset);
    $this->language = $request->getParam('language', $this->language);
    $roles = $request->getParam('roles');

    \Yii::app()->setLanguage($this->language);

    header('Content-type: text/csv; charset='.$this->csvCharset);
    header('Content-Disposition: attachment; filename=participans.csv');

    $fp = fopen('php://output', '');
    $row = array(
      'RUNET-ID',
      'Фамилия',
      'Имя',
      'Отчество',
      'Компания',
      'Должность',
      'Email',
      'Телефон',
      'Статус на мероприятии',
      'Cумма оплаты',
      'Тип оплаты',
      'Дата регистрации на мероприятие',
      'Дата оплаты участия',
      'Дата выдачи бейджа'
    );
    fputcsv($fp, $this->rowHandler($row), $this->csvDelimiter);

    $criteria = new \CDbCriteria();
    $criteria->with = array(
      'Participants' => array('on' => '"Participants"."EventId" = :EventId', 'params' => array(
        'EventId' => $this->getEvent()->Id
      ), 'together' => false),
      'Employments' => array('together' => false),
      'Employments.Company' => array('together' => false),
      'LinkPhones.Phone' => array('together' => false)
    );
    $criteria->order = '"t"."LastName" ASC, "t"."FirstName" ASC';

    $command = \Yii::app()->getDb()->createCommand();
    $command->select('EventParticipant.UserId')->from('EventParticipant');
    $command->where('"EventParticipant"."EventId" = '.$this->getEvent()->Id);
    if ($roles !== null)
    {
      $command->andWhere(array('in', 'EventParticipant.RoleId', $roles));
    }
    $criteria->addCondition('"t"."Id" IN ('.$command->getText().')');

    $users = \user\models\User::model()->findAll($criteria);

    foreach ($users as $user)
    {
      /** @var \event\models\Participant $participant */
      $participant = null;
      foreach ($user->Participants as $curP)
      {
        if ($participant == null || $participant->Role->Priority < $curP->Role->Priority)
        {
          $participant = $curP;
        }
      }

      $row = array(
        'RUNET-ID' => $user->RunetId,
        'LastName' => $user->LastName,
        'FirstName' => $user->FirstName,
        'FatherName' => $user->FatherName,
        'Company' => '',
        'Position' => '',
        'Email' => $user->Email,
        'Phone' => !empty($user->LinkPhones) ? (string)$user->LinkPhones[0]->Phone : '',
        'Role' => $participant != null ? $participant->Role->Title : '-',
        'Price' => '',
        'PaidType' => '',
        'DateRegister' => \Yii::app()->dateFormatter->format('dd MMMM yyyy H:m', $participant->CreationTime),
        'DatePay' => '',
        'DateBadge' => ''
      );

      if ($user->getEmploymentPrimary() !== null)
      {
        $row['Company'] = $user->getEmploymentPrimary()->Company->Name;
        $row['Position'] = $user->getEmploymentPrimary()->Position;
      }

      $criteria = new \CDbCriteria();
      $criteria->with = array(
        'Product',
        'Product.Attributes' => array('select' => false, 'alias' => 'ProductAttributes')
      );
      $criteria->condition = '"t"."OwnerId" = :OwnerId AND "Product"."EventId" = :EventId AND "t"."Paid" AND "ProductAttributes"."Name" = :Name';
      $criteria->params['EventId'] = \Yii::app()->partner->getAccount()->EventId;
      $criteria->params['OwnerId'] = $user->Id;
      $criteria->params['Name'] = 'RoleId';
      /** @var $orderItem \pay\models\OrderItem */
      $orderItem = \pay\models\OrderItem::model()->find($criteria);
      if ($orderItem !== null)
      {
        $row['Price'] = $orderItem->getPriceDiscount() !== null ? $orderItem->getPriceDiscount() : 0;
        $row['DatePay'] = \Yii::app()->dateFormatter->format('dd MMMM yyyy H:m', strtotime($orderItem->PaidTime));
        foreach ($orderItem->OrderLinks as $link)
        {
          if ($link->Order->Paid)
          {
            $row['PaidType'] = $link->Order->Juridical ? \Yii::t('app', 'Юр. лицо') : \Yii::t('app', 'Физ. лицо'); 
            break;
          }
        }
      }

      /** @var $badge \ruvents\models\Badge */
      $badge = \ruvents\models\Badge::model()
          ->byEventId($this->getEvent()->Id)
          ->byUserId($user->Id)->find();
      if ($badge !== null)
      {
        $row['DateBadge'] = $badge->CreationTime;
      }

      fputcsv($fp, $this->rowHandler($row), $this->csvDelimiter);
    }

    \Yii::app()->end();
  }


  private function rowHandler($row)
  {
    foreach ($row as &$item)
    {
      if ($this->csvCharset == 'Windows-1251')
      {
        $item = iconv('utf-8', 'Windows-1251', $item);
      }
      $item = str_replace($this->csvDelimiter, '', $item);
    }
    unset($item);
    return $row;
  }
}

?>
