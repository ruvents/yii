<?php
namespace event\components\handlers\register;

use application\components\utility\PKPassGenerator;
use event\models\MailRegister;
use event\models\Role;
use mail\components\MailLayout;
use \mail\models\Layout;

class Base extends MailLayout
{
    /** @var \event\models\Event  */
    protected $event;
    /** @var  \user\models\User */
    protected $user;
    /** @var  \event\models\Role */
    protected $role;
    /** @var  \event\models\Participant */
    protected $participant;

    /**
     *
     * @param \mail\components\Mailer $mailer
     * @param \CEvent $event
     */
    public function __construct(\mail\components\Mailer $mailer, \CEvent $event)
    {
        parent::__construct($mailer);
        $this->event = $event->sender;
        $this->user  = $event->params['user'];
        $this->role  = $event->params['role'];
        $this->participant  = $event->params['participant'];
    }

    /** @var MailRegister */
    private $registerMail = null;

    /**
     * Возвращает регистрационное письмо, удовлетворяющее критерии
     * @return MailRegister
     */
    private function getRegisterMail()
    {
        if ($this->registerMail === null && $this->hasRegisterMail()) {
            $mails = unserialize(base64_decode($this->event->MailRegister));
            foreach ($mails as $mail) {
                $conditionInExcept = in_array($this->role->Id, $mail->RolesExcept);
                $condition1 = in_array($this->role->Id, $mail->Roles) && !$conditionInExcept;
                $condition2 = $this->registerMail == null && empty($mail->Roles) && empty($mail->RolesExcept);
                $condition3 = empty($mail->Roles) && !empty($mail->RolesExcept) && !$conditionInExcept;
                if ($condition1 || $condition2 || $condition3) {
                    $this->registerMail = $mail;
                }
            }
        }
        return $this->registerMail;
    }

    /**
     * Возвращает true, если у мероприятия заданы регистрационные письма
     * @return bool
     */
    private function hasRegisterMail()
    {
        return isset($this->event->MailRegister);
    }

    /**
     * @inheritdoc
     */
    public function getTo()
    {
        return $this->user->Email;
    }

    /**
     * @inheritdoc
     */
    public function getFrom()
    {
        return 'users@runet-id.com';
    }

    /**
     * @inheritdoc
     */
    public function getFromName()
    {
        return '—RUNET—ID—';
    }

    /**
     * @inheritdoc
     */
    public function getSubject()
    {
        if ($this->getRegisterMail() !== null) {
            return $this->getRegisterMail()->Subject;
        }
        return 'Электронный билет - ' . $this->event->Title;
    }

    /**
     * @inheritdoc
     */
    public function getBody()
    {
        $params = [
            'user' => $this->user,
            'event' => $this->event,
            'participant' => $this->participant,
            'role' => $this->role
        ];

        $viewName = null;
        if ($this->hasRegisterMail()) {
            if ($this->getRegisterMail() !== null) {
                $viewName = $this->getRegisterMail()->getViewName();
            }
        } elseif ($this->role->Id != Role::VIRTUAL_ROLE_ID) {
            $viewName = 'event.views.mail.register.base';
        }

        if ($viewName !== null) {
            return $this->renderBody($viewName, $params);
        }
        return null;
    }

    /**
     * @inheritdoc
     */
    public function getAttachments()
    {
        $attachments = [];

        $mail = $this->getRegisterMail();
        if ($mail === null || $mail->SendPassbook) {
            $pkPass = new PKPassGenerator($this->event, $this->user, $this->role);
            $attachments['ticket.pkpass'] = $pkPass->runAndSave();
        }

        if ($mail !== null && $mail->SendTicket && false) {
            $ticket = $this->participant->getTicket();
            $attachments[$ticket->getFileName()] = $ticket->save();
        }
        return $attachments;
    }

    /**
     * @inheritdoc
     */
    function getUser()
    {
        return $this->user;
    }

    /**
     * @inheritdoc
     */
    public function getLayoutName()
    {
        if (isset($this->getRegisterMail()->Layout)) {
            return $this->getRegisterMail()->Layout;
        } else {
            return Layout::TwoColumn;
        }
    }

    /**
     * @inheritdoc
     */
    public function showUnsubscribeLink()
    {
        return false;
    }

    /**
     * @inheritdoc
     */
    public function getIsPriority()
    {
        return true;
    }
}
