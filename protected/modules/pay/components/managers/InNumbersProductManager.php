<?php
namespace pay\components\managers;

class InNumbersProductManager extends BaseProductManager
{
  const CallbackUrl = 'http://www.in-numbers.ru/subscribe/callback.php';
  const PrivateKey = '586f5ab0e13a03127a0dfa3af3';

  /**
   * Возвращает true - если продукт может быть приобретен пользователем, и false - иначе
   * @param \user\models\User $user
   * @param array $params
   * @return bool
   */
  public function checkProduct($user, $params = array())
  {
    // TODO: Implement CheckProduct() method.
    return true;
  }

  /**
   * Оформляет покупку продукта на пользователя
   * @param \user\models\User $user
   * @param array $params
   * @return bool
   */
  public function internalBuyProduct($user, $params = array())
  {
    $params = array();
    $params['rocid'] = $user->RunetId;
    $params['RunetId'] = $user->RunetId;
    $params['key'] = self::PrivateKey;

    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, self::CallbackUrl . '?' . http_build_query($params));
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 3);
    curl_setopt($ch, CURLOPT_TIMEOUT, 10);
    curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
    $result = curl_exec($ch);
    curl_close($ch);

    if ($result != 'OK')
    {
      return false;
    }
    return true;
  }

  /**
   * @param array $params
   * @param string $filter
   * @return array
   */
  public function filter($params, $filter)
  {
    return array();
  }

  /**
   * @param array $params
   * @return \pay\models\Product
   */
  public function getFilterProduct($params)
  {
    return $this->product;
  }

  /**
   * Отменяет покупку продукта на пользовтеля
   * @param \user\models\User $user
   * @return bool
   */
  public function rollbackProduct($user)
  {

  }

  /**
   *
   * @param \user\models\User $fromUser
   * @param \user\models\User $toUser
   * @param array $params
   *
   * @return bool
   */
  public function internalChangeOwner($fromUser, $toUser, $params = array())
  {

  }
}
