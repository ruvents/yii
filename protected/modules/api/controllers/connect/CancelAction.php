<?php
namespace api\controllers\connect;

use api\components\Exception;
use connect\models\forms\CancelCreator;
use connect\models\forms\Response;
use connect\models\Meeting;
use connect\models\MeetingLinkUser;

use nastradamus39\slate\annotations\ApiAction;
use nastradamus39\slate\annotations\Action\Request;
use nastradamus39\slate\annotations\Action\Response as ApiResponse;
use nastradamus39\slate\annotations\Action\Param;

class CancelAction extends \api\components\Action
{
    /**
     * @ApiAction(
     *     controller="Connect",
     *     title="Отмена встречи",
     *     description="Отменяет встречу. Статус встречи меняется на 'отмененная'",
     *     request=@Request(
     *          method="GET",
     *          url="/connect/cancel",
     *          body="",
     *          params={
     *              @Param(title="MeetingId", description="Айди встречи.", mandatory="Y"),
     *              @Param(title="RunetId",   description="Runetid создателя встречи.", mandatory="Y")
     *          },
     *          response=@ApiResponse(body="{'Success': true}")
     *      )
     * )
     */
    public function run()
    {
        $user = $this->getRequestedUser();
        $meetingId = $this->getRequestParam('MeetingId', null);

        $meeting = Meeting::model()->byCreatorId($user->Id)->findByPk($meetingId);
        if ($meeting){
            try{
                $form = new CancelCreator($meeting);
                $form->Status = Meeting::STATUS_CANCELLED;
                $form->Response = \Yii::app()->getRequest()->getParam('Response', null);
                $form->updateActiveRecord();
                $this->setSuccessResult();
            }
            catch (\Exception $e){
                $this->setResult(['Success' => false, 'Error' => $e]);
            }
            return;
        }

        $meeting = Meeting::model()->byUserId($user->Id)->findByPk($meetingId);
        if ($meeting){
            try{
                $form = new Response($meeting->UserLinks[0]);
                $form->Status = MeetingLinkUser::STATUS_CANCELLED;
                $form->Response = \Yii::app()->getRequest()->getParam('Response', null);
                $form->updateActiveRecord();
                $this->setSuccessResult();
            }
            catch (\Exception $e){
                $this->setResult(['Success' => false, 'Error' => $e]);
            }
            return;
        }

        if (!$meeting){
            throw new Exception(4001, [$meeting]);
        }
    }
}