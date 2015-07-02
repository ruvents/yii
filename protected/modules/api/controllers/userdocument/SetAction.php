<?php
namespace api\controllers\userdocument;

use \api\components\Exception;
use api\components\Action;
use user\models\DocumentType;
use user\models\forms\document\BaseDocument;
use user\models\User;

class SetAction extends Action
{
    public function run()
    {
        $runetId = \Yii::app()->getRequest()->getPost('RunetId', null);
        $documentTypeId = \Yii::app()->getRequest()->getPost('DocumentTypeId', null);
        $attributes = \Yii::app()->getRequest()->getPost('Attributes', null);

        $documentType = DocumentType::model()->byId($documentTypeId)->find();
        if ($documentType === null) {
            throw new Exception(1001, array($documentTypeId));
        }

        $user = User::model()->byRunetId($runetId)->find();
        if ($user === null) {
            throw new  Exception(202, array($runetId));
        }

        $documentClass = 'user\models\forms\document\\'.$documentType->FormName;

        /** @var BaseDocument $document */
        $document = new $documentClass($documentType, $user);
        $document->setAttributes($attributes);

        $result = $document->createActiveRecord();
        if ($result === null) {
            $error = [
                'Code'=> 1002,
                'Messages' => $document->getErrors()
            ];
            $this->setResult(['Error'=>$error]);
        } else {

            $this->setResult(['Success' => true]);
        }


    }

}
