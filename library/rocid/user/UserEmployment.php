<?php
AutoLoader::Import('library.rocid.user.*');
AutoLoader::Import('library.rocid.company.*');

/**
 * @property int EmploymentId
 * @property int $UserId
 * @property int $CompanyId
 * @property string $StartWorking
 * @property string $FinishWorking
 * @property string $Position
 * @property int $Primary
 *
 * @property Company $Company
 */
class UserEmployment extends CActiveRecord
{
  const TableName = 'UserEmployment';

  public static function model($className=__CLASS__)
  {    
    return parent::model($className);
  }
  
  public function tableName()
  {
    return self::TableName;
  }
  
  public function primaryKey()
  {
    return 'EmploymentId';
  }
  
  public function relations()
  {
    return array(
      'Company' => array(self::BELONGS_TO, 'Company', 'CompanyId'), 
      'User' => array(self::BELONGS_TO, 'User', 'UserId',
        'order' => 'User.LastName'),
    );
  }

  /**
   * @static
   * @param int $userId
   * @return void
   */
  public static function ResetAllUserPrimary($userId)
  {
    Yii::app()->db->createCommand()->update(self::TableName, array('Primary' => 0),
                                            'UserId = :UserId', array(':UserId' => $userId));
  }
  
  /**
  * @return Company
  */
  public function GetCompany()
  {
    $company = $this->Company;
    if (isset($company))
    {
      return $company;
    }
    else
    {
      return null;
    }
  }

  /**
  * Возвращает ассоциативный массив с полями day, month, year
  *
  * @return array
  */
  public function GetParsedStartWorking()
  {
    $date = $this->StartWorking;
    $result = array();

    $result['year'] = intval(substr($date, 0, 4));
    $result['month'] = intval(substr($date, 5, 2));
    $result['day'] = intval(substr($date, 8, 2));

    return $result;
  }
  
  /**
  * Возвращает отформатированную дату начала работы
  *
  * @return array
  */
  public function GetFormatedStartWorking()
  {
        $date = strtotime($this->StartWorking);
      
  	if (!$date || strpos($this->StartWorking, '9999') !== false || strpos($this->StartWorking, '0000') !== false)
  	{
            return 'неизвестно';
  	}
        
        if (strpos($this->StartWorking, '-00-00'))
        {
            return intval(substr($this->StartWorking, 0, 4));
        }
        elseif (strpos($this->StartWorking, '-00'))
        {
            $date = strtotime(str_replace('-00', '-01', $this->StartWorking));
        }
        
        return Yii::app()->dateFormatter->format('LLLL yyyy', $date);
  }

  /**
   * Устанавливает корректную дату начала работы из массива day, month, year
   * @param array $date
   * @return void
   */
  public function SetParsedStartWorking($date)
  {
    if (empty($date['year']) || intval($date['year']) == 0)
    {
      $result = '0000';
    }
    else
    {
      $result = $date['year'];
    }
    $result .= '-';
    if (empty($date['month']) || intval($date['month']) == 0)
    {
      $result .= '00';
    }
    else
    {
      $result .= $date['month'];
    }
    $result .= '-';
    if (empty($date['day']) || intval($date['day']) == 0)
    {
      $result .= '00';
    }
    else
    {
      $result .= $date['day'];
    }
    $this->StartWorking = $result;
  }

  /**
  * Возвращает ассоциативный массив с полями day, month, year
  *
  * @return array
  */
  public function GetParsedFinishWorking()
  {
    $date = $this->FinishWorking;
    $result = array();

    $result['year'] = intval(substr($date, 0, 4));
    $result['month'] = intval(substr($date, 5, 2));
    $result['day'] = intval(substr($date, 8, 2));

    return $result;
  }
  
  /**
  * Возвращает отформатированную дату окончания работы
  *
  * @return array
  */
  public function GetFormatedFinishWorking()
  {
        if (strpos($this->FinishWorking, '9999') !== false)
        {
            return 'по настоящее время';
        }
  	$date = strtotime($this->FinishWorking);
  	if (!$date)
  	{
            return 'по настоящее время';
  	}
        if (strpos($this->FinishWorking, '-00-00'))
        {
            return intval(substr($this->FinishWorking, 0, 4));
        }
        elseif (strpos($this->FinishWorking, '-00'))
        {
            $date = strtotime(str_replace('-00', '-01', $this->FinishWorking));
        }
        return Yii::app()->dateFormatter->format('LLLL yyyy', $date);
  }

  /**
   * Устанавливает корректную дату начала работы из массива day, month, year
   * @param array $date
   * @return void
   */
  public function SetParsedFinishWorking($date)
  {
    if (empty($date['year']) || intval($date['year']) == 0 || empty($date['month']) || $date['month'] == 0)
    {
      $result = '9999';
    }
    else
    {
      $result = $date['year'];
    }
    $result .= '-';
    if (empty($date['month']) || intval($date['month']) == 0)
    {
      $result .= '00';
    }
    else
    {
      $result .= $date['month'];
    }
    $result .= '-';
    if (empty($date['day']) || intval($date['day']) == 0)
    {
      $result .= '00';
    }
    else
    {
      $result .= $date['day'];
    }
    $this->FinishWorking = $result;
  }
}