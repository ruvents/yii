<?php
namespace api\models;
/**
 * @property int $Id
 * @property string $Key
 * @property string $Secret
 * @property int $EventId
 * @property string $IpCheck
 * @property string $Role
 *
 * @property \event\models\Event $Event
 * @property Domain[] $Domains
 * @property Ip[] $Ips
 */
class Account extends \CActiveRecord
{
  /**
   * @param string $className
   *
   * @return Account
   */
  public static function model($className=__CLASS__)
  {
    return parent::model($className);
  }

  public function tableName()
  {
    return 'ApiAccount';
  }

  public function primaryKey()
  {
    return 'Id';
  }

  public function relations()
  {
    return array(
      'Event' => array(self::BELONGS_TO, '\event\models\Event', 'EventId'),
      'Domains' => array(self::HAS_MANY, '\api\models\Domain', 'AccountId'),
      'Ips' => array(self::HAS_MANY, '\api\models\Ip', 'AccountId')
    );
  }

  /**
   * @param string $key
   * @param bool $useAnd
   *
   * @return Account
   */
  public function byKey($key, $useAnd = true)
  {
    $criteria = new \CDbCriteria();
    $criteria->condition = '"t"."Key" = :Key';
    $criteria->params = array(':Key' => $key);
    $this->getDbCriteria()->mergeWith($criteria, $useAnd);
    return $this;
  }

  protected $_dataBuilder = null;

  /**
   * @return \api\components\builders\Builder
   */
  public function getDataBuilder()
  {
    if ($this->_dataBuilder === null)
    {
      $version = \Yii::app()->getRequest()->getParam('v', null);
      $timestamp = strtotime($version);
      if ($timestamp === false)
      {
        $this->_dataBuilder = new \api\components\builders\Builder($this);
      }
      else
      {
        $this->_dataBuilder = $this->getVersioningBuilder($timestamp);
      }
    }

    return $this->_dataBuilder;
  }

  /**
   * @param int $timestamp
   * @return \api\components\builders\Builder
   */
  protected function getVersioningBuilder($timestamp)
  {
    return new \api\components\builders\Builder($this);
  }

  public function checkIp($ip)
  {
    //todo: fix it
    return true;
  }

  /**
   * @param string $hash
   * @param int $timestamp
   * @return bool
   */
  public function CheckHash($hash, $timestamp)
  {
    if ($hash === $this->getHash($timestamp))
    {
      return true;
    }
    return false;
  }

  public function checkReferer($referer, $hash)
  {
    if ($hash !== $this->getRefererHash($referer))
    {
      return false;
    }
    foreach ($this->Domains as $domain)
    {
      $pattern = '/^' . $domain->Domain . '$/i';
      if (preg_match($pattern, $referer) === 1)
      {
        return true;
      }
    }
    return false;
  }

  /**
   * @param string $url
   *
   * @return bool
   */
  public function checkUrl($url)
  {
    $host = parse_url($url, PHP_URL_HOST);
    foreach ($this->Domains as $domain)
    {
      $pattern = '/^' . $domain->Domain . '$/i';
      if (preg_match($pattern, $host) === 1)
      {
        return true;
      }
    }
    return false;
  }

  public function getRefererHash($referer)
  {
    return substr(md5($this->Key . $referer . $this->Secret . 'nPOg9ODiyos4HJIYS9FGGJ7qw'), 0, 16);
  }

  /**
   * @param int $timestamp
   * @return string
   */
  private function getHash($timestamp)
  {
    return substr(md5($this->Key . $timestamp . $this->Secret), 0, 16);
  }
}