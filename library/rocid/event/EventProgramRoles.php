<?php
/**
 * @property int $RoleId
 * @property string $Name
 */
class EventProgramRoles extends CActiveRecord
{
  public static $TableName = 'EventProgramRoles';
  
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
    return 'RoleId';
  }
  
  public function relations()
  {
    return array(      
      );
  }

  /**
   * @static
   * @return EventProgramRoles[]
   */
  public static function GetAll()
  {
    $roles = EventProgramRoles::model();
    $criteria = new CDbCriteria();
    $criteria->order = 't.RoleId';
    return $roles->findAll($criteria);
  }
  
  /**
  * Геттеры и сеттеры для полей
  */
  public function GetRoleId()
  {
    return $this->RoleId;
  }
  
  //Name
  public function GetName()
  {
    return $this->Name;
  }
  
  public function SetName($value)
  {
    $this->Name = $value;
  }
}