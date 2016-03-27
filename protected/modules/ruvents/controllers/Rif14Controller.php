<?php
use pay\components\admin\Rif;
use pay\models\Product;
use pay\models\ProductGet;
use ruvents\components\Exception;
use user\models\User;

class Rif14Controller extends CController
{
    private static $hotels = [
        0 => Rif::HOTEL_P,
        1 => Rif::HOTEL_LD
    ];

    public function actionIndex($hotel, $product)
    {
        //echo json_encode([321, 454, 35287, 59999, 1466, 122262, 12959, 158947], JSON_UNESCAPED_UNICODE);
        //Yii::app()->end();

        $hotel = intval($hotel);
        $product = intval($product);
        $food = [
            'breakfast' => [1467, 1470, 1473],
            'lunch' => [1468, 1471, 1474],
            'dinner' => [1469, 1472, 1475],
            'banquet' => 1476,
            'anderson' => [2701, 2702, 2703]
        ];

        $users = Rif::getUsersByHotel();

        $usersInclude = [];
        $usersExclude = [];
        if ($product == 1469) {
            if ($hotel == 0) {
                echo json_encode([], JSON_UNESCAPED_UNICODE);
                Yii::app()->end();
            }
        } elseif (in_array($product, $food['anderson'])) {
            if ($hotel == 1) {
                echo json_encode([], JSON_UNESCAPED_UNICODE);
                Yii::app()->end();
            }
        } elseif ($hotel == 1) {
            $usersInclude = $users[Rif::HOTEL_LD];
        } elseif ($hotel == 0 && in_array($product, $food['breakfast'])) {
            $usersExclude = array_merge($users[Rif::HOTEL_LD], $users[Rif::HOTEL_N], $users[Rif::HOTEL_S]);
        } elseif ($hotel == 0 && $product != $food['banquet']) {
            $usersExclude = $users[Rif::HOTEL_LD];
        }

        $criteria = new \CDbCriteria();
        $criteria->with = [
            'Owner',
            'ChangedOwner'
        ];
        $items = \pay\models\OrderItem::model()->byProductId($product)->byPaid(true)->findAll($criteria);

        $result = [];
        foreach ($items as $item) {
            $owner = $item->ChangedOwnerId != null ? $item->ChangedOwner : $item->Owner;
            if (!empty($usersInclude) && in_array($owner->RunetId, $usersInclude)) {
                $result[] = $owner->RunetId;
            } elseif (!empty($usersExclude) && !in_array($owner->RunetId, $usersExclude)) {
                $result[] = $owner->RunetId;
            } elseif (empty($usersExclude) && empty($usersInclude)) {
                $result[] = $owner->RunetId;
            }
        }

        echo json_encode($result, JSON_UNESCAPED_UNICODE);
    }

    public function actionAdd($runetId, $productId)
    {
        $user = User::model()
            ->byRunetId($runetId)
            ->find();
        
        if ($user == null)
            throw new Exception(202, $runetId);

        $product = Product::model()
            ->findByPk($productId);
        
        if ($product == null)
            throw new Exception(401, $productId);

        $get = ProductGet::model()
            ->byUserId($user->Id)
            ->byProductId($product->Id)
            ->find();
        
        if ($get == null) {
            $get = new ProductGet();
            $get->UserId = $user->Id;
            $get->ProductId = $product->Id;
            $get->save();
            $get->refresh();
        }

        echo json_encode(['Success' => true, 'CreationTime' => $get->CreationTime]);
    }

    public function actionList($productId, $fromTime = null)
    {
        $criteria = new \CDbCriteria();
        $criteria->with = ['User'];
        $criteria->order = '"t"."CreationTime" ASC';

        $product = Product::model()->findByPk($productId);
        if ($product === null)
            throw new Exception(401, $productId);

        $criteria->addCondition('"t"."ProductId" = :ProductId');
        $criteria->params['ProductId'] = $product->Id;

        if (!empty($fromTime)) {
            $datetime = DateTime::createFromFormat('Y-m-d H:i:s', $fromTime);
            if ($datetime === false)
                throw new Exception(900, 'FromUpdateTime');

            $criteria->addCondition('"t"."CreationTime" >= :Time');
            $criteria->params['Time'] = $datetime->format('Y-m-d H:i:s');
        }

        $gets = ProductGet::model()->findAll($criteria);
        $result = [];
        foreach ($gets as $get) {
            $item = new \stdClass();
            $item->UserId = $get->User->RunetId;
            $item->ProductId = $get->ProductId;
            $item->CretionTime = $get->CreationTime;
            $result[] = $item;
        }
        echo json_encode($result);
    }

    private function getUsersIdbyRunetId($runetIdList)
    {
        $command = Yii::app()->getDb()->createCommand();
        $command->select('Id')->from('User');
        $command->where('"RunetId" IN (' . implode(',', $runetIdList) . ')');

        $rows = $command->queryAll();
        $result = [];
        foreach ($rows as $row) {
            $result[] = $row['Id'];
        }

        return $result;
    }

    public function actionFillCardUsers()
    {
        $operatorId = 802;
        $criteria = new CDbCriteria();
        $criteria->condition = 't."OperatorId" = :OperatorId';
        $criteria->params = ['OperatorId' => $operatorId];

        /** @var \ruvents\models\Badge[] $badges */
        $badges = \ruvents\models\Badge::model()->findAll($criteria);

        foreach ($badges as $badge) {
            if (\user\models\LoyaltyProgram::model()->byUserId($badge->UserId)->exists()) {
                $badge->delete();
//        $program = new \user\models\LoyaltyProgram();
//        $program->UserId = $badge->UserId;
//        $program->EventId = $badge->EventId;
//        $program->CreationTime = $badge->CreationTime;
//        $program->save();
            }
        }

        echo count($badges);
    }

    public function createLog()
    {
        return null;
    }
} 