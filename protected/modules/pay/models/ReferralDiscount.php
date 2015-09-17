<?php
namespace pay\models;

use application\components\ActiveRecord;
use event\models\Event;
use user\models\Referral;
use user\models\User;

/**
 * This is the model class for table "PayReferralDiscount".
 *
 * The followings are the available columns in table 'PayReferralDiscount':
 * @property integer $Id
 * @property integer $EventId
 * @property integer $ProductId
 * @property integer $Discount
 * @property string $CreationTime
 * @property string $StartTime
 * @property string $EndTime
 *
 * The followings are the available model relations:
 * @property Event $Event
 *
 * @method ReferralDiscount find()
 * @method ReferralDiscount[] findAll()
 * @method ReferralDiscount byEventId(integer $id)
 */
class ReferralDiscount extends ActiveRecord
{
	/**
	 * Returns the static model of the specified AR class.
	 * @param string $className active record class name.
	 * @return ReferralDiscount the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}

	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'PayReferralDiscount';
	}

	/**
	 * @return array relational rules.
	 */
	public function relations()
	{
		return [
			'Event' => [self::BELONGS_TO, '\event\models\Event', 'EventId'],
			'Product' => [self::BELONGS_TO, '\pay\models\Product', 'ProductId']
		];
	}

	/**
	 * @param int $id
	 * @param bool $orIsNull
	 * @param bool $useAnd
	 * @return $this
	 */
	public function byProductId($id, $orIsNull = true, $useAnd = true)
	{
		$criteria = new \CDbCriteria();
		$criteria->addCondition('"t"."ProductId" = :ProductId'. ($orIsNull ? ' OR "t"."ProductId" IS NULL' : ''));
		$criteria->params['ProductId'] = $id;
		$this->getDbCriteria()->mergeWith($criteria, $useAnd);
		return $this;
	}

	/**
	 * @param string $time
	 * @param bool $useAnd
	 * @return $this
	 */
	public function byActual($time, $useAnd = true)
	{
		$criteria = new \CDbCriteria();
		$criteria->addCondition('"t"."StartTime" IS NULL OR "t"."StartTime" <= :Time');
		$criteria->addCondition('"t"."EndTime" IS NULL OR "t"."EndTime" >= :Time');
		$criteria->params['Time'] = $time;
		$this->getDbCriteria()->mergeWith($criteria, $useAnd);
		return $this;
	}

	/**
	 * Примет скидку к заказу и возвращает стоимость заказа, с учетом этой скидки
	 * @param OrderItem $orderItem
	 * @return int
	 */
	public function apply(OrderItem $orderItem)
	{
		$price = $orderItem->getPrice();
		return $price - $this->getDiscount($orderItem->Product, $price);
	}

	/**
	 * Возврвщает размер скидки
	 * @param Product $product
	 * @param int|null $price
	 * @return float
	 */
	public function getDiscount(Product $product, $price = null)
	{
		if ($price === null) {
			$price = $product->getPrice();
		}
		return $price * $this->Discount / 100;
	}

	/**
	 * Ищет действующую реферальную по указанным параметрам
	 *
	 * @param Product $product
	 * @param User $user
	 * @param null $time
	 * @return ReferralDiscount|null
	 */
	public static function findDiscount(Product $product, User $user, $time = null) {
		if (empty($time)) {
			$time = date('Y-m-d H:i:s');
		}

		$discount = self::model()->byEventId($product->EventId)->byProductId($product->Id)->byActual($time)->find();
		if (!empty($discount)) {
			$exists = Referral::model()->byUserId($user->Id)->byEventId($product->EventId)->exists();
			if ($exists) {
				return $discount;
			}
		}
		return null;
	}
}