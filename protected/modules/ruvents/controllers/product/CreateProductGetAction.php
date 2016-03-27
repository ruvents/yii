<?php
namespace ruvents\controllers\product;


use pay\models\Product;
use pay\models\ProductGet;
use ruvents\components\Exception;
use user\models\User;

class CreateProductGetAction extends \ruvents\components\Action
{
    public function run($runetId, $productId)
    {
        $user = User::model()
            ->byRunetId($runetId)
            ->byEventId($this->getEvent()->Id)
            ->find();

        if ($user == null)
            throw new Exception(202, $runetId);

        $product = Product::model()
            ->byEventId($this->getEvent()->Id)
            ->findByPk($productId);

        if ($product == null)
            throw new Exception(401, $productId);


        $get = new ProductGet();
        $get->UserId = $user->Id;
        $get->ProductId = $product->Id;
        $get->save();
        $get->refresh();


        $this->renderJson(['Success' => true, 'CreationTime' => $get->CreationTime]);
    }
}