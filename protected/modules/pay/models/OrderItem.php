<?php
namespace pay\models;

/**
 * @property int $Id
 * @property int $ProductId
 * @property int $PayerId
 * @property int $OwnerId
 * @property int $ChangedOwnerId
 * @property string $Booked
 * @property bool $Paid
 * @property string $PaidTime
 * @property string $CreationTime
 * @property bool $Deleted
 * @property string $DeletionTime
 *
 * @property Product $Product
 * @property \user\models\User $Payer
 * @property \user\models\User $Owner
 * @property \user\models\User $ChangedOwner
 * @property OrderLinkOrderItem[] $OrderLinks
 * @property CouponActivationLinkOrderItem $CouponActivationLink
 * @property OrderItemAttribute[] $Attributes
 */
class OrderItem extends \CActiveRecord
{
  /**
   * @static
   * @param string $className
   * @return OrderItem
   */
  public static function model($className=__CLASS__)
  {
    return parent::model($className);
  }

  public function tableName()
  {
    return 'PayOrderItem';
  }

  public function primaryKey()
  {
    return 'Id';
  }

  public function relations()
  {
    return array(
      'Product' => array(self::BELONGS_TO, '\pay\models\Product', 'ProductId'),
      'Payer' => array(self::BELONGS_TO, '\user\models\User', 'PayerId'),
      'Owner' => array(self::BELONGS_TO, '\user\models\User', 'OwnerId'),
      'ChangedOwner' => array(self::BELONGS_TO, '\user\models\User', 'ChangedOwnerId'),
      'OrderLinks' => array(self::HAS_MANY, '\pay\models\OrderLinkOrderItem', 'OrderItemId'),
      'CouponActivationLink' => array(self::HAS_ONE, '\pay\models\CouponActivationLinkOrderItem', 'OrderItemId'),

      'Attributes' => array(self::HAS_MANY, '\pay\models\OrderItemAttribute', 'OrderItemId'),
    );
  }

  public function getItemAttribute($name)
  {
    if ($this->getIsNewRecord())
    {
      throw new \pay\components\Exception('Заказ еще не сохранен.');
    }
    if (in_array($name, $this->Product->getManager()->getOrderItemAttributeNames()))
    {
      $attributes = $this->getOrderItemAttributes();
      return isset($attributes[$name]) ? $attributes[$name]->Value : null;
    }
    else
    {
      throw new \pay\components\Exception('Данный заказ не содержит аттрибута с именем ' . $name);
    }
  }

  public function setItemAttribute($name, $value)
  {
    if ($this->getIsNewRecord())
    {
      throw new \pay\components\Exception('Заказ еще не сохранен.');
    }
    if (in_array($name, $this->Product->getManager()->getOrderItemAttributeNames()))
    {
      $attributes = $this->getOrderItemAttributes();
      if (!isset($attributes[$name]))
      {
        $attribute = new \pay\models\OrderItemAttribute();
        $attribute->OrderItemId = $this->Id;
        $attribute->Name = $name;
        $this->orderItemAttributes[$name] = $attribute;
      }
      else
      {
        $attribute = $attributes[$name];
      }
      $attribute->Value = $value;
      $attribute->save();
    }
    else
    {
      throw new \pay\components\Exception('Данный заказ не содержит аттрибута с именем ' . $name);
    }
  }

  /** @var OrderItemAttribute[] */
  protected $orderItemAttributes = null;

  /**
   * @return ProductAttribute[]
   */
  public function getOrderItemAttributes()
  {
    if ($this->orderItemAttributes === null)
    {
      $this->orderItemAttributes = array();
      foreach ($this->Attributes as $attribute)
      {
        $this->orderItemAttributes[$attribute->Name] = $attribute;
      }
    }

    return $this->orderItemAttributes;
  }

  /**
   * @param int $productId
   * @param bool $useAnd
   *
   * @return OrderItem
   */
  public function byProductId($productId, $useAnd = true)
  {
    $criteria = new \CDbCriteria();
    $criteria->condition = '"t"."ProductId" = :ProductId';
    $criteria->params = array('ProductId' => $productId);
    $this->getDbCriteria()->mergeWith($criteria, $useAnd);
    return $this;
  }

  /**
   * @param int $userId
   * @param bool $useAnd
   * @return OrderItem
   */
  public function byPayerId($userId, $useAnd = true)
  {
    $criteria = new \CDbCriteria();
    $criteria->condition = '"t"."PayerId" = :PayerId';
    $criteria->params = array('PayerId' => $userId);
    $this->getDbCriteria()->mergeWith($criteria, $useAnd);
    return $this;
  }

  /**
   * @param int $userId
   * @param bool $useAnd
   * @return OrderItem
   */
  public function byOwnerId($userId, $useAnd = true)
  {
    $criteria = new \CDbCriteria();
    $criteria->condition = '"t"."OwnerId" = :OwnerId';
    $criteria->params = array('OwnerId' => $userId);
    $this->getDbCriteria()->mergeWith($criteria, $useAnd);
    return $this;
  }

  /**
   * @param int|null $userId
   * @param bool $useAnd
   * @return OrderItem
   */
  public function byChangedOwnerId($userId, $useAnd = true)
  {
    $criteria = new \CDbCriteria();
    if ($userId !== null)
    {
      $criteria->condition = '"t"."ChangedOwnerId" = :ChangedOwner';
      $criteria->params = array('ChangedOwnerId' => $userId);
    }
    else
    {
      $criteria->condition = '"t"."ChangedOwnerId" IS NULL';
    }
    $this->getDbCriteria()->mergeWith($criteria, $useAnd);
    return $this;
  }

  /**
   * @param int $eventId
   * @param bool $useAnd
   * @return OrderItem
   */
  public function byEventId($eventId, $useAnd = true)
  {
    $criteria = new \CDbCriteria();
    $criteria->condition = '"Product"."EventId" = :EventId';
    $criteria->params = array('EventId' => $eventId);
    $criteria->with = array('Product');
    $this->getDbCriteria()->mergeWith($criteria, $useAnd);
    return $this;
  }

  /**
   * @param bool $paid
   * @param bool $useAnd
   * @return OrderItem
   */
  public function byPaid($paid, $useAnd = true)
  {
    $criteria = new \CDbCriteria();
    $criteria->condition = ($paid ? '' : 'NOT ') . '"t"."Paid"';
    $this->getDbCriteria()->mergeWith($criteria, $useAnd);
    return $this;
  }

  /**
   * @param bool $deleted
   * @param bool $useAnd
   *
   * @return OrderItem
   */
  public function byDeleted($deleted, $useAnd = true)
  {
    $criteria = new \CDbCriteria();
    $criteria->condition = ($deleted ? '' : 'NOT ') . '"t"."Deleted"';
    $this->getDbCriteria()->mergeWith($criteria, $useAnd);
    return $this;
  }

  /**
   * Усли параметр $booked=true - добавляет условие, что срок заказа не истек
   * @param $booked
   * @param bool $useAnd
   *
   * @return OrderItem
   */
  public function byBooked($booked = true, $useAnd = true)
  {
    $criteria = new \CDbCriteria();
    if ($booked)
    {
      $criteria->condition = '"t"."Booked" IS NULL OR "t"."Booked" > :Booked';
    }
    else
    {
      $criteria->condition = '"t"."Booked" IS NOT NULL AND "t"."Booked" < :Booked';
    }
    $criteria->params = array('Booked' => date('Y-m-d H:i:s'));
    $this->getDbCriteria()->mergeWith($criteria, $useAnd);
    return $this;
  }

  /**
   * Добавляет ограничение на выборку не оплаченных не удаленных OrderItem, не включенных ни в какие не удаленные юр. счета
   *
   * @param int $payerId
   * @param int $eventId
   * @param bool $not
   * @param bool $useAnd
   *
   * @return OrderItem
   */
  public function byInOrders($payerId, $eventId, $not = true, $useAnd = true)
  {
    $criteria = new \CDbCriteria();
    $criteria->distinct = true;
    $criteria->with = array(
      'OrderLinks.Order' => array('select' => false, 'together' => true),
    );
    $criteria->addCondition('"Order"."Juridical"')
        ->addCondition('NOT "Order"."Deleted"')
        ->addCondition('"Order"."EventId" = :EventId');
    $criteria->params = array('EventId' => $eventId);
    $criteria->select = array('"t"."Id"');

    $model = new OrderItem();
    /** @var $items OrderItem[] */
    $items = $model->byPayerId($payerId)
        ->byPaid(false)
        ->byDeleted(false)
        ->findAll($criteria);
    $ids = array();
    foreach ($items as $item)
    {
      $ids[] = $item->Id;
    }


    $criteria = new \CDbCriteria();
    if ($not)
    {
      $criteria->addNotInCondition('"t"."Id"', $ids);
    }
    else
    {
      $criteria->addInCondition('"t"."Id"', $ids);
    }
    $this->getDbCriteria()->mergeWith($criteria, $useAnd);
    return $this;
  }

  /**
   * @var CouponActivation
   */
  private $couponActivation = null;
  /** @var bool */
  private $couponTrySet = false;
  /**
   * @return CouponActivation
   */
  public function getCouponActivation()
  {
    if (!$this->Product->EnableCoupon)
    {
      return null;
    }
    if ($this->couponActivation === null && !$this->couponTrySet)
    {
      $this->couponTrySet = true;
      if (!$this->Paid)
      {
        /** @var $activation CouponActivation */
        $activation = CouponActivation::model()
            ->byUserId($this->OwnerId)
            ->byEventId($this->Product->EventId)
            ->byEmptyLinkOrderItem()->find();
        if ($activation !== null)
        {
          $rightProduct = $activation->Coupon->ProductId === null || $activation->Coupon->ProductId == $this->ProductId;
          $rightTime = $this->PaidTime === null || $this->PaidTime >= $activation->CreationTime;
          if ($rightProduct && $rightTime)
          {
            $this->couponActivation = $activation;
          }
        }
      }
      else
      {
        $this->couponActivation = $this->CouponActivationLink !== null ? $this->CouponActivationLink->CouponActivation : null;
      }
    }
    return $this->couponActivation;
  }

  /**
   * @return bool
   */
  public function activate()
  {
//    if ($this->Booked !== null && $this->Booked < date('Y-m-d H:i:s'))
//    {
//      $this->Deleted = true;
//      $this->DeletionTime = date('Y-m-d H:i:s');
//      $this->save();
//      return false;
//    }
    $owner = $this->ChangedOwner !== null ? $this->ChangedOwner : $this->Owner;
    if (!$this->Product->getManager()->checkProduct($owner))
    {
      $this->Deleted = true;
      $this->DeletionTime = date('Y-m-d H:i:s');
      $this->save();
      return false;
    }
    $this->Product->getManager()->buyProduct($owner);
    $this->Paid = true;
    if ($this->PaidTime === null)
    {
      $this->PaidTime = date('Y-m-d H:i:s');
    }
    $this->save();
    return true;
  }

  public function deactivate()
  {

  }

  /**
   * @param \user\models\User $newOwner
   *
   * @return bool
   */
  public function changeOwner(\user\models\User $newOwner)
  {
    $fromOwner = empty($this->ChangedOwner) ? $this->Owner : $this->ChangedOwner;
    if ($this->Product->getManager()->redirectProduct($fromOwner, $newOwner))
    {
      $this->ChangedOwnerId = $newOwner->Id;
      $this->save();
      return true;
    }

    return false;
  }


  /**
   * @return int
   */
  public function getPrice()
  {
    return $this->Product->getManager()->getPrice($this);
  }

  /**
   * Итоговое значение цены товара, с учетом скидки, если она есть
   * @throws \pay\components\Exception
   * @return int|null
   */
  public function getPriceDiscount()
  {
    $activation = $this->getCouponActivation();
    $price = $this->getPrice();
    if ($price === null)
    {
      throw new \pay\components\Exception('Не удалось определить цену продукта!');
    }

    if ($activation !== null)
    {
      $price = $price * (1 - $activation->Coupon->Discount);
    }
    return (int)$price;
  }

  public function delete()
  {
    if ($this->Paid || $this->Deleted)
    {
      return false;
    }

    /** @var $links OrderLinkOrderItem[] */
    $links = $this->OrderLinks(array('with' => array('Order')));
    foreach ($links as $link)
    {
      if ($link->Order->Juridical && !$link->Order->Deleted)
      {
        return false;
      }
    }

    $this->Deleted = true;
    $this->DeletionTime = date('Y-m-d H:i:s');
    $this->save();

    return true;
  }

  /**
   *
   * @return int
   */
  public function clearBooked()
  {
    $db = \Yii::app()->getDb();
    $command = $db->createCommand();
    $count = $command->update(
      $this->tableName(),
      array('Deleted' => true),
      'Booked IS NOT NULL AND Booked < :Booked AND NOT Paid AND NOT Deleted',
      array(':Booked' => date('Y-m-d H:i:s'))
    );
    return $count;
  }

  /**
   * @param int $payerId
   * @param int $eventId
   *
   * @return OrderItem[]
   */
  public static function getFreeItems($payerId, $eventId)
  {
    $model = OrderItem::model()
        ->byBooked(true)->byPaid(true, false)
        ->byPayerId($payerId)
        ->byEventId($eventId)
        ->byInOrders($payerId, $eventId)
        ->byDeleted(false);

    return $model->findAll();
  }



//  public static function GetAllByEventId($eventId, $payerId, $ownerId = null)
//  {
//    $criteria = new \CDbCriteria();
//    $criteria->with = array('Product', 'Product.Attributes', 'Owner');
//    $criteria->condition = 'Product.EventId = :EventId AND (t.Booked IS NULL OR t.Booked > :Booked OR t.Paid = :Paid) AND t.Deleted = :Deleted AND t.PayerId = :PayerId';
//    $criteria->params = array(':PayerId' => $payerId, ':EventId' => $eventId, ':Booked' => date('Y-m-d H:i:s'),
//      ':Paid' => 1, ':Deleted' => 0);
//
//    if (!empty($ownerId))
//    {
//      $criteria->addCondition('t.OwnerId = :OwnerId');
//      $criteria->params[':OwnerId'] = $ownerId;
//    }
//
//    return OrderItem::model()->findAll($criteria);
//  }


}
