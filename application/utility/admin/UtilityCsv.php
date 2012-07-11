<?php

class UtilityCsv extends AdminCommand
{

  /**
   * Основные действия комманды
   * @return void
   */
  protected function doExecute()
  {
    //return;
    ini_set("memory_limit", "512M");

    $file = fopen('site12.csv', 'w');
    $eventId = 246;

    $model = EventUser::model()->with(array('User', 'User.Settings', 'User.Employments' => array('on' => 'Employments.Primary = 1'), 'User.Employments.Company', 'User.Emails'));
    //$model = EventProgramUserLink::model()->with(array('User', 'User.Employments' => array('on' => 'Employments.Primary = 1'), 'User.Employments.Company'));

    $criteria = new CDbCriteria();
    $criteria->condition = 't.EventId = :EventId';
    $criteria->params = array(':EventId' => $eventId);
    $criteria->group = 't.UserId';
    $criteria->order = 't.CreationTime';

    /** @var $eventUsers EventUser[] */
    $eventUsers = $model->findAll($criteria);

    foreach($eventUsers as $eUser)
    {
      /** @var $user User */
      $user = $eUser->User;

      $name = iconv('utf-8', 'Windows-1251', $user->LastName . ' ' . $user->FirstName . (!empty($user->FatherName) ? ' ' . $user->FatherName : ''));

      $CompanyName = '';
      $Position = '';

      if (!empty($user->Employments))
      {
        $Position = iconv('utf-8', 'Windows-1251', $user->Employments[0]->Position);
        $CompanyName = iconv('utf-8', 'Windows-1251', $user->Employments[0]->Company->Name);
      }

      $roleName = iconv('utf-8', 'Windows-1251', $eUser->EventRole->Name);

      $email = iconv('utf-8', 'Windows-1251', $user->GetEmail() != null ? $user->GetEmail()->Email : $eUser->User->Email);

      $sendMail = iconv('utf-8', 'Windows-1251', $user->Settings->ProjNews == 1 ? 'да' : 'нет');

      fputcsv($file, array(date('d.m.Y H:i:s', $eUser->CreationTime), $user->RocId, $name, $CompanyName, $Position, $email, $roleName, $sendMail), ';');

    }
    fclose($file);

    echo 'Done!';
  }
}
