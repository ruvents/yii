<?php
/**
 * Created by PhpStorm.
 * User: Андрей
 * Date: 19.11.2015
 * Time: 15:25
 */

namespace api\controllers\ms;


use api\components\Action;
use api\components\Exception;
use api\components\ms\forms\EditUser;
use api\components\ms\forms\UpdateUser;
use user\models\User;

class UpdateUserAction extends Action
{
    public function run()
    {
        $id = \Yii::app()->getRequest()->getParam('RunetId');
        $user = User::model()->byEventId($this->getAccount()->EventId)->byRunetId($id)->find();
        if ($user === null) {
            throw new Exception(202, [$id]);
        }

        $form = new UpdateUser($user, $this->getAccount());
        $form->fillFromPost();
        if ($form->updateActiveRecord() !== null) {
            $this->setResult(['Success' => true]);
        }
    }
}