<?php
namespace company\models;


/**
 * @throws \Exception
 *
 * @property int $Id
 * @property string $Name
 * @property string $FullName
 * @property string $Info
 * @property string $FullInfo
 * @property string $CreationTime
 * @property string $UpdateTime
 *
 *
 *
 *
 *
 *
 *
 * @property \company\models\LinkEmail[] $LinkEmails
 * @property \company\models\LinkAddress $Address
 * @property \company\models\LinkPhone[] $Phones
 * @property \company\models\LinkSite $Site

 * @property \user\models\Employment[] $Employments
 * @property \user\models\Employment[] $EmploymentsAll
 */
class Company extends \CActiveRecord
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
		return 'Company';
	}
	
	public function primaryKey()
	{
		return 'Id';
	}

  public function relations()
  {
    return array(
      'LinkEmails' => array(self::HAS_MANY, '\company\models\LinkEmail', 'CompanyId'),
      'LinkAddress' => array(self::HAS_ONE, '\company\models\LinkAddress', 'CompanyId'),
      'LinkSite' => array(self::HAS_ONE, '\company\models\LinkSite', 'CompanyId'),
      'LinkPhones' => array(self::HAS_MANY, '\company\models\LinkPhone', 'CompanyId'),  
        
      //Сотрудники
      'Employments' => array(self::HAS_MANY, '\user\models\Employment', 'CompanyId', 'order' => 'User.LastName DESC', 'condition' => 'Users.FinishWorking > CURDATE()', 'with' => array('User')),
      'EmploymentsAll' => array(self::HAS_MANY, '\user\models\Employment', 'CompanyId', 'with' => array('User')),
    );
  }
  
  
  
  public static function getLogoBaseDir($onServerDisc = false)
	{
    $result = \Yii::app()->params['CompanyLogoDir'];
    if ($onServerDisc)
    {
      $result .= $_SERVER['DOCUMENT_ROOT'].$result;
    }
    return $result;
	}
  
  /**
	* Возвращает путь к изображению компании
	* @param bool $onServerDisc
	* @return string
	*/
	public function getLogo($onServerDisc = false)
	{
		$path = $this->Id . '_200.jpg';
		if ($onServerDisc || file_exists(self::getLogoBaseDir(true).$path))
		{
			$path = self::getLogoBaseDir($onServerDisc).$path;
		}
		else
		{
			$path = self::getLogoBaseDir($onServerDisc) . 'no_logo.png';
		}
    return $path;
	}
}