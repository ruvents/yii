<?php
namespace partner\components;
\AutoLoader::Import('library.mail.*');

class Notifier
{
  protected $account = null;

  /**
   * @param $account \partner\models\Account
   */
  public function __construct($account)
  {
    $this->account = $account;
  }

  /**
   * @param $user \user\models\User
   */
  public function NotifyNewParticipant($user)
  {
    if ($this->account->NoticeEmail == null)
    {
      return;
    }

    $event = Event::GetById($this->account->EventId);

    $view = new View();
    $view->SetTemplate('new-participant', 'partner', 'notice', '', 'public');
    $view->User = $user;
    $view->Event = $event;

    $email = $this->account->NoticeEmail;

    $mail = new PHPMailer(false);
    $mail->ContentType = 'text/plain';
    $mail->IsHTML(false);
    $mail->AddAddress($email);
    $mail->SetFrom('partners@rocid.ru', 'ROCID:// Партнеры', false);
    $mail->CharSet = 'utf-8';
    $mail->Subject = '=?UTF-8?B?'. base64_encode('На [' . $event->Name . '] зарегистрирован новый участник') .'?=';
    $mail->Body = $view;
    $mail->Send();
  }

  public function NotifyRoleChange($user, $oldRole, $newRole)
  {
    if ($this->account->NoticeEmail == null)
    {
      return;
    }

    $event = Event::GetById($this->account->EventId);

    $view = new View();
    $view->SetTemplate('change-role', 'partner', 'notice', '', 'public');
    $view->User = $user;
    $view->Event = $event;
    $view->OldRole = $oldRole;
    $view->NewRole = $newRole;

    $email = $this->account->NoticeEmail;

    $mail = new PHPMailer(false);
    $mail->ContentType = 'text/plain';
    $mail->IsHTML(false);
    $mail->AddAddress($email);
    $mail->SetFrom('partners@rocid.ru', 'ROCID:// Партнеры', false);
    $mail->CharSet = 'utf-8';
    $mail->Subject = '=?UTF-8?B?'. base64_encode('На [' . $event->Name . '] изменен статус участника') .'?=';
    $mail->Body = $view;
    $mail->Send();
  }
}