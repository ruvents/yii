<?php
namespace api\controllers\purpose;

use nastradamus39\slate\annotations\Action\Param;
use nastradamus39\slate\annotations\Action\Request;
use nastradamus39\slate\annotations\Action\Response;
use nastradamus39\slate\annotations\ApiAction;

class DeleteAction extends \api\components\Action
{

    /**
     * @ApiAction(
     *     controller="Event",
     *     title="Удаление цели мероприятия",
     *     description="Удаляет цель посещения мероприятия пользователем.'",
     *     request=@Request(
     *          method="GET",
     *          url="/purpose/delete",
     *          params={
     *              @Param(title="RunetId", mandatory="Y", description="Идентификатор участника."),
     *              @Param(title="Purpose Id", mandatory="Y", description="Идентификатор цели посещения мероприятия.")
     *          },
     *          response=@Response( body="{'Success': true}" )
     *     )
     * )
     */
    public function run()
    {
        $runetId = \Yii::app()->getRequest()->getParam('RunetId');
        $user = \user\models\User::model()->byRunetId($runetId)->find();
        if ($user !== null) {
            $participant = \event\models\Participant::model()->byUserId($user->Id)->byEventId($this->getEvent()->Id)->find();
            if ($participant === null) {
                throw new \api\components\Exception(202, [$runetId]);
            }
        } else {
            throw new \api\components\Exception(202, [$runetId]);
        }

        $purposeId = \Yii::app()->getRequest()->getParam('PurposeId');
        $link = \user\models\LinkEventPurpose::model()->byUserId($user->Id)->byEventId($this->getEvent()->Id)->byPurposeId($purposeId)->find();
        if ($link !== null) {
            $link->delete();
        }
        $this->setSuccessResult();
    }
}