<?php
namespace api\controllers\pay;

class FilterBookAction extends \api\components\Action
{
    public function run()
    {
        $request = \Yii::app()->getRequest();
        $manager = $request->getParam('Manager');
        $params = $request->getParam('Params', []);
        $bookTime = $request->getParam('BookTime', null);
        $payerRunetId = intval($request->getParam('PayerRunetId', 0));
        $ownerRunetId = intval($request->getParam('OwnerRunetId', 0));

        /** @var $product \pay\models\Product */
        $product = \pay\models\Product::model()
            ->byManagerName($manager)
            ->byEventId($this->getEvent()->Id)->find();

        if ($product !== null) {
            $product = $product->getManager()->getFilterProduct($params);
        }
        if ($product === null) {
            throw new \api\components\Exception(420);
        }

        /** @var $payer \user\models\User */
        $payer = \user\models\User::model()->byRunetId($payerRunetId)->find();
        /** @var $owner \user\models\User */
        $owner = \user\models\User::model()->byRunetId($ownerRunetId)->find();

        if ($payer === null) {
            throw new \api\components\Exception(202, array($payerRunetId));
        }
        if ($owner === null) {
            throw new \api\components\Exception(202, array($ownerRunetId));
        }
        if ($product->EventId != $this->getEvent()->Id) {
            throw new \api\components\Exception(402);
        }
        if (!$product->getManager()->checkProduct($owner)) {
            throw new \api\components\Exception(403);
        }

        $orderItem = $product->getManager()->createOrderItem($payer, $owner, $bookTime, $params);
        $collection = \pay\components\OrderItemCollection::createByOrderItems([$orderItem]);
        $result = null;
        foreach ($collection as $item) {
            $result = $this->getAccount()->getDataBuilder()->createOrderItem($item);
            break;
        }
        $this->getController()->setResult($result);
    }
}
