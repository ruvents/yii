<?php
namespace api\controllers\user;

use api\components\Action;
use user\models\User;
use api\components\Exception;

class LoginAction extends Action
{
    public function run()
    {
        $request = \Yii::app()->getRequest();

        $login = $this->getLoginParam();
        $password = base64_decode($request->getParam('Password'));

        $user = User::model()->byRunetId($login)->byEmail($login, 'OR')->find();
        if ($user === null) {
            throw new Exception(211, [$login]);
        }

        if (!$user->checkLogin($password)) {
            throw new Exception(201);
        }

        $this->getAccount()->getDataBuilder()->createUser($user);
        $this->getAccount()->getDataBuilder()->buildUserContacts($user);
        $this->getAccount()->getDataBuilder()->buildUserEmployment($user);
        $result = $this->getAccount()->getDataBuilder()->buildUserEvent($user);
        $this->setResult($result);
    }

    /**
     * @return mixed
     * @throws Exception
     */
    public function getLoginParam()
    {
        $request = \Yii::app()->getRequest();
        foreach (['Login', 'Email', 'RunetId'] as $name) {
            $login = $request->getParam($name);
            if (!empty($login)) {
                return trim($login);
            }
        }
        throw new Exception(110);
    }
}
