<?php

namespace connect\components\handlers\decline\user;

class Base extends \connect\components\handlers\Base
{
    /** @var string */
    protected $response;

    /**
     * @param \mail\components\Mailer $mailer
     * @param \CEvent $event
     */
    public function __construct(\mail\components\Mailer $mailer, \CEvent $event)
    {
        parent::__construct($mailer, $event);
        $this->response = $event->params['response'];
    }

    /**
     * @inheritdoc
     */
    public function getSubject()
    {
        return 'Вы отменили встречу с '.$this->meeting->Creator->getFullName();
    }

    /**
     * @inheritdoc
     */
    public function getTo()
    {
        return $this->getUser()->Email;
    }

    /**
     * @inheritdoc
     */
    public function getBody()
    {
        $params = [
            'meeting' => $this->meeting,
            'user' => $this->meeting->Creator,
            'response' => $this->response
        ];

        return $this->renderBody($this->getViewName(), $params);
    }

    public function getViewPath()
    {
        return 'connect.views.mail.decline.user';
    }
}