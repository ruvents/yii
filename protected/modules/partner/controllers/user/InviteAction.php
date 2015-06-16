<?php
namespace partner\controllers\user;

use event\models\Approved;
use event\models\Role;
use partner\models\search\InviteRequest;
use \event\models\InviteRequest as InviteRequestModel;

class InviteAction extends \partner\components\Action
{
    public function run()
    {
        /** @var \CHttpRequest $request */
        $request = \Yii::app()->getRequest();
        if (($action = $request->getParam('Action')) !== null) {
            switch ($action) {
                case 'approved':
                    $this->processApproved(Approved::Yes);
                    break;

                case 'disapproved':
                    $this->processApproved(Approved::No);
                    break;
            }
        }

        $search = new InviteRequest($this->getEvent());
        $this->getController()->render('invite', [
            'search' => $search,
            'event'  => $this->getEvent()
        ]);
    }

    /**
     * Обработка заявки на участие
     * @param $approved
     * @throws \CHttpException
     */
    private function processApproved($approved)
    {
        $request = \Yii::app()->getRequest();
        $inviteRequest  = InviteRequestModel::model()->byEventId($this->getEvent()->Id)->findByPk($request->getParam('InviteId'));
        if ($inviteRequest == null) {
            throw new \CHttpException(404);
        }

        $role = null;
        if ($approved == Approved::Yes) {
            $role = Role::model()->findByPk($request->getParam('RoleId'));
        }
        $inviteRequest->changeStatus($approved, $role);
        $this->getController()->refresh();
    }
}
