<?php
namespace user\controllers\ajax;

use sms\components\gates\SmsRu;
use user\components\handlers\PhoneVerify;
use user\models\User;

class PhoneVerifyAction extends \CAction
{
    const VERIFY_SEND_DELAY = 20;

    /** @var User */
    private $user;

    public function run()
    {
        $this->user = \Yii::app()->getUser()->getCurrentUser();

        if (empty($this->user->PrimaryPhone) || $this->user->PrimaryPhoneVerify) {
            throw new \CHttpException(404);
        }

        $request = \Yii::app()->getRequest();
        switch ($request->getParam('action')) {
            case 'send':
                $result = $this->send();
                break;
            case 'verify':
                $result = $this->verify();
                break;
        }
        echo json_encode($result);
        \Yii::app()->end();
    }

    private function send()
    {
        if ($this->user->PrimaryPhoneVerifyTime !== null && (time() - strtotime($this->user->PrimaryPhoneVerifyTime)) < self::VERIFY_SEND_DELAY) {
            return ['error' => \Yii::t('app', 'Повторное отправление кода возможно только через {delay} секунд', ['{delay}' => self::VERIFY_SEND_DELAY])];
        }

        $this->user->PrimaryPhoneVerifyTime = date('Y-m-d H:i:s');
        $this->user->save();

        $sms = new PhoneVerify(new SmsRu(), $this->user);
        if ($sms->send()) {
            return ['success' => true];
        }
        return ['error' => \Yii::t('app', 'Внутренняя ошибка.')];
    }

    private function verify()
    {
        $request = \Yii::app()->getRequest();
        $code = $request->getParam('code');
        if ($this->user->getPrimaryPhoneVerifyCode() == $code) {
            $this->user->PrimaryPhoneVerify = true;
            $this->user->save();
            return ['success' => true];
        } else {
            return ['error' => \Yii::t('app', 'Неверный код подтверждения.')];
        }
    }
}