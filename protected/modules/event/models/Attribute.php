<?php
namespace event\models;

/**
 * @property int $Id
 * @property int $EventId
 * @property string $Name
 * @property string $Value
 * @property int $Order
 *
 * @property Event $Event
 */
class Attribute extends \application\models\translation\ActiveRecord
{  
  /**
   * @param string $className
   * @return Attribute
   */
  public static function model($className=__CLASS__)
  {
    return parent::model($className);
  }

  public function tableName()
  {
    return 'EventAttribute';
  }

  public function primaryKey()
  {
    return 'Id';
  }

  public function relations()
  {
    return array(
      'Event' => array(self::BELONGS_TO, '\event\models\Event', 'EventId'),
    );
  }
  
   /**
   * 
   * @return string[]
   */
  public function getTranslationFields()
  {
    return ['Value'];
  }
}
