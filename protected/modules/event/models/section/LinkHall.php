<?php
namespace event\models\section;

/**
 * @property int $Id
 * @property int $SectionId
 * @property int $HallId
 *
 * @property Section $Section
 * @property Hall $Hall
 */
class LinkHall extends \CActiveRecord
{
  /**
   * @param string $className
   *
   * @return LinkHall
   */
  public static function model($className=__CLASS__)
  {
    return parent::model($className);
  }

  public function tableName()
  {
    return 'EventSectionLinkHall';
  }

  public function primaryKey()
  {
    return 'Id';
  }

  public function relations()
  {
    return array(
      'Section' => array(self::BELONGS_TO, '\event\models\section\Section', 'SectionId'),
      'Hall' => array(self::BELONGS_TO, '\event\models\section\Hall', 'HallId'),
    );
  }

  
  public function byHallId($hallId, $useAnd = true)
  {
    $criteria = new \CDbCriteria();
    $criteria->condition = '"t"."HallId" = :HallId';
    $criteria->params = array('HallId' => $hallId);
    $this->getDbCriteria()->mergeWith($criteria, $useAnd);
    return $this;
  }
  
  public function bySectionId($sectionId, $useAnd = true)
  {
    $criteria = new \CDbCriteria();
    $criteria->condition = '"t"."SectionId" = :SectionId';
    $criteria->params = array('SectionId' => $sectionId);
    $this->getDbCriteria()->mergeWith($criteria, $useAnd);
    return $this;
  }
}
