<?php
namespace api\controllers\ms;

class PayUrlAction extends \api\components\Action
{
  public function run()
  {
    $request = \Yii::app()->getRequest();
    $externalId = $request->getParam('ExternalId');
    $lang = $request->getParam('Lang', 'ru');

    $externalUser = \api\models\ExternalUser::model()
        ->byExternalId($externalId)->byPartner($this->getAccount()->Role)->find();
    if ($externalUser === null)
      throw new \api\components\Exception(3003, [$externalId]);

    $url = $externalUser->User->getFastauthUrl(\Yii::app()->createUrl('/pay/cabinet/register', ['eventIdName' => $this->getEvent()->IdName, 'lang' => $lang]));

    $this->setResult(['PayUrl' => $url]);
  }
}