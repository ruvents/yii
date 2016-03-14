<?php
namespace api\controllers\pay;

use api\components\Action;
use api\components\Exception;
use pay\components\CodeException;
use pay\components\MessageException;
use pay\components\OrderItemCollection;
use pay\models\Product;
use user\models\User;

/**
 * Class AddAction
 */
class AddAction extends Action
{
    /**
     * @inheritdoc
     * @throws Exception
     * @throws CodeException
     * @throws MessageException
     */
    public function run()
    {
        $request = \Yii::app()->getRequest();
        $productId = $request->getParam('ProductId');

        $payerRunetId = $request->getParam('PayerRunetId', null);
        if ($payerRunetId === null) {
            $payerRunetId = $request->getParam('PayerRocId', null);
        }

        $ownerRunetId = $request->getParam('OwnerRunetId', null);
        if ($ownerRunetId === null) {
            $ownerRunetId = $request->getParam('OwnerRocId', null);
        }

        /** @var Product $product */
        $product = Product::model()
            ->byEventId($this->getEvent()->Id)
            ->findByPk($productId);

        $payer = User::model()->byRunetId($payerRunetId)->find();
        $owner = User::model()->byRunetId($ownerRunetId)->find();

        if (!$product) {
            throw new Exception(401, [$productId]);
        } elseif ($payer == null) {
            throw new Exception(202, [$payerRunetId]);
        } elseif ($owner == null) {
            throw new Exception(202, [$ownerRunetId]);
        } elseif ($this->getEvent()->Id != $product->EventId) {
            throw new Exception(402);
        }

        $attributes = $request->getParam('Attributes', []);

        try {
            $orderItem = $product->getManager()->createOrderItem($payer, $owner, null, $attributes);
        } catch (Exception $e) {
            throw new Exception(408, [$e->getCode(), $e->getMessage()], $e);
        }

        $collection = OrderItemCollection::createByOrderItems([$orderItem]);
        $result = null;
        foreach ($collection as $item) {
            $result = $this->getAccount()->getDataBuilder()->createOrderItem($item);
            break;
        }

        $this->getController()->setResult($result);
    }
}
