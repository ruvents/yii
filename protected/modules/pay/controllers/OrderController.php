<?php

use pay\models\Order;

class OrderController extends \application\components\controllers\MainController
{
    public $layout = '/layouts/bill';

    public function actionIndex($orderId, $hash = null, $clear = null)
    {
        /** @var $order Order */
        $order = Order::model()
            ->findByPk($orderId);

        if ($order === null || (!\pay\models\OrderType::getIsBank($order->Type))) {
            throw new \CHttpException(404);
        }

        $checkHash = $order->checkHash($hash);
        if (!$checkHash && (\Yii::app()->user->getCurrentUser() === null || \Yii::app()->user->getCurrentUser()->Id != $order->PayerId)) {
            throw new \CHttpException(404);
        }

        $this->setPageTitle('Счёт № '.$order->Number);

        if ($clear === null && $order->Deleted) {
            $this->renderText(\Yii::t('app', 'Данный счет был удален. Вы можете выставить новый или восстановить этот счет обратившись по адресу {email}.', ['{email}' => \CHtml::mailto('fin@runet-id.com')]));
            \Yii::app()->end();
        }

        $this->render($order->getViewName(), [
            'order' => $order,
            'billData' => $order->getBillData()->Data,
            'total' => $order->getBillData()->Total,
            'nds' => $order->getBillData()->Nds,
            'withSign' => $clear === null,
            'template' => $order->getViewTemplate()
        ]);
    }
}
