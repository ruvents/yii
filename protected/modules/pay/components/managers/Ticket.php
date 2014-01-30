<?php
namespace pay\components\managers;

/**
 * Class Ticket
 * @property int $ProductId
 */
class Ticket extends BaseProductManager
{
  protected $paidProduct;

  public function __construct($product)
  {
    parent::__construct($product);
    $this->paidProduct = \pay\models\Product::model()->findByPk($this->ProductId);
  }

  public function getOrderItemAttributeNames()
  {
    return ['Count'];
  }


  public function getProductAttributeNames()
  {
    return ['ProductId'];
  }


  public function checkProduct($user, $params = [])
  {
    return true;
  }

  /**
   * Оформляет покупку продукта на пользователя
   *
   * @param \user\models\User $user
   * @param \pay\models\OrderItem $orderItem
   * @param array $params
   *
   * @return bool
   */
  protected function internalBuyProduct($user, $orderItem = null, $params = array())
  {
    $coupons = [];
    for ($i = 0; $i < $orderItem->getItemAttribute('Count'); $i++)
    {
      $coupon = new \pay\models\Coupon();
      $coupon->EventId = $this->product->EventId;
      $coupon->ProductId = $this->ProductId;
      $coupon->Code = 'ticket-'.$coupon->generateCode();
      $coupon->OwnerId = $user->Id;
      $coupon->Discount = 1;
      $coupon->IsTicket = true;
      $coupon->save();
      $coupons[] = $coupon;
    }

    $event = new \CModelEvent($this, ['payer' => $user, 'product' => $this->paidProduct, 'coupons' => $coupons]);
    $mail = new \pay\components\handlers\buyproduct\Ticket(new \mail\components\mailers\PhpMailer(), $event);
    $mail->send();
    return true;
  }

  /**
   * Отменяет покупку продукта на пользовтеля
   * @param \user\models\User $user
   * @return bool
   */
  public function rollbackProduct($user)
  {
    return false;
  }

  /**
   *
   * @param \user\models\User $fromUser
   * @param \user\models\User $toUser
   * @param array $params
   *
   * @return bool
   */
  protected function internalChangeOwner($fromUser, $toUser, $params = array())
  {
    return false;
  }

  public function filter($params, $filter)
  {
    return [];
  }

  public function getFilterProduct($params)
  {
    return $this->product;
  }

  public function getPrice($orderItem)
  {
    $price = $this->paidProduct->getManager()->getPrice($orderItem);
    if (is_object($orderItem))
    {
      $count = intval($orderItem->getItemAttribute('Count'));
      return ($price * $count);
    }
    return $price;
  }

  public function getTitle($orderItem)
  {
    $count = intval($orderItem->getItemAttribute('Count'));
    $title  = \Yii::t('app', '1#Билет|n>1#Билеты', $count);
    $title .= ' '.\Yii::t('app', 'на').' "';
    $title .= $this->paidProduct->Title.'"';
    return $title;
  }

  /**
   * @return \pay\models\Product
   */
  public function getPaidProduct()
  {
    return $this->paidProduct;
  }


}