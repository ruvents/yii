<?php
namespace job\models;

/**
 * @property int $Id
 * @property string $Position
 * @property string $Text
 * @property int $SalaryFrom
 * @property int $SalaryTo
 * @property int $CompanyId
 * @property int $PositionId
 * @property string $CrateTime 
 * @property bool $Visible
 */
class Job extends \CActiveRecord
{
  /**
   * @param string $className
   * @return Company
   */
  public static function model($className=__CLASS__)
	{    
		return parent::model($className);
	}
	
	public function tableName()
	{
		return 'Job';
	}
	
	public function primaryKey()
	{
		return 'Id';
	}
  
  public function relations()
  {
    return array(
      'Company'  => array(self::BELONGS_TO, '\company\models\Company', 'CompanyId'),
      'Category' => array(self::BELONGS_TO, '\job\models\Category', 'CategoryId'),
      'Position' => array(self::BELONGS_TO, '\job\models\Position', 'PositionId'),
    );
  }
  
  public function byVisible($visible = true, $useAnd = true)
  {
    $criteria = new \CDbCriteria();
    $criteria->condition = ($visible ? '' : 'NOT ') . '"t"."Visible"';
    $this->getDbCriteria()->mergeWith($criteria, $useAnd);
    return $this;
  }
  
  public function getPreview()
  {
    $text = strip_tags($this->Text);
    if (mb_strlen($text, 'utf-8') > \Yii::app()->params['JobPreviewLength'])
    {
      $text = mb_substr($text, 0, \Yii::app()->params['JobPreviewLength'], 'utf-8').'...';
    }
    return $text;
  }
}