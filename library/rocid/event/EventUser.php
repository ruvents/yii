<?php
AutoLoader::Import('library.rocid.event.*');

/**
 * @property int $EventUserId
 * @property int $EventId
 * @property int $UserId
 * @property int $RoleId
 * @property int $Approve
 * @property int $CreationTime
 * @property int $UpdateTime
 *
 * @property User $User
 * @property EventRoles $EventRole
 * @property Event $Event
 */
class EventUser extends CActiveRecord
{
  public static $TableName = 'EventUser';
  
  public static function model($className=__CLASS__)
  {    
    return parent::model($className);
  }
  
  public function tableName()
  {
    return self::$TableName;
  }
  
  public function primaryKey()
  {
    return 'EventUserId';
  }
  
  public function relations()
  {
    return array(
      'Event' => array(self::BELONGS_TO, 'Event', 'EventId', 'order' => 'Event.DateStart DESC, Event.DateEnd DESC'),
      'EventRole' => array(self::BELONGS_TO, 'EventRoles', 'RoleId'),
      'User' => array(self::BELONGS_TO, 'User', 'UserId')
    );
  }

  /**
   * @static
   * @param int $userId
   * @param int $eventId
   * @return EventUser
   */
  public static function GetByUserEventId($userId, $eventId)
  {
    $eventUser = EventUser::model();

    $criteria = new CDbCriteria();
    $criteria->condition = 't.UserId = :UserId AND t.EventId = :EventId';
    $criteria->params = array(':UserId' => $userId, ':EventId' => $eventId);
    return $eventUser->find($criteria);
  }

  /**
   * Возвращает EventUser сгруппированные по RoleId
   * @static
   * @param $eventId
   * @return EventUser[]
   */
  public static function GetEventRoles($eventId)
  {
    $eventUser = EventUser::model()->with('EventRole')->together();

    $criteria = new CDbCriteria();
    $criteria->condition = 't.EventId = :EventId';
    $criteria->params = array(':EventId' => $eventId);
    $criteria->group = 't.RoleId';
    $criteria->order = 'EventRole.Priority DESC';
    return $eventUser->findAll($criteria);
  }

  /**
   * @static
   * @return int
   */
  public static function AllCount()
  {
    return EventUser::model()->count();
  }
  
  /**
  * @return Event
  */
  public function GetEvent()
  {
    return $this->Event;
  }
  
  /**
  * @return EventRoles
  */
  public function GetRole()
  {
    return $this->EventRole;
  }

  /**
   * @param $role EventRoles
   */
  public function UpdateRole($role)
  {
    $this->RoleId = $role->RoleId;
    $this->UpdateTime = time();
    $this->save();
  }
}