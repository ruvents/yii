<?php
namespace user\models;

use application\components\ActiveRecord;
use company\models\Company;
use Yii;

/**
 * @property int $Id
 * @property int $UserId
 * @property int $CompanyId
 * @property int $StartYear
 * @property int $StartMonth
 * @property int $EndYear
 * @property int $EndMonth
 * @property string $Position
 * @property bool $Primary
 *
 * @property Company $Company
 * @property User $User
 *
 * Описание вспомогательных методов
 * @method Employment   with($condition = '')
 * @method Employment   find($condition = '', $params = [])
 * @method Employment   findByPk($pk, $condition = '', $params = [])
 * @method Employment   findByAttributes($attributes, $condition = '', $params = [])
 * @method Employment[] findAll($condition = '', $params = [])
 * @method Employment[] findAllByAttributes($attributes, $condition = '', $params = [])
 *
 * @method Employment byId(int $id, bool $useAnd = true)
 * @method Employment byUserId(int $id, bool $useAnd = true)
 * @method Employment byCompanyId(int $id, bool $useAnd = true)
 * @method Employment byPrimary(bool $primary = true, bool $useAnd = true)
 */
class Employment extends ActiveRecord
{
    const TableName = 'UserEmployment';

    /**
     * @param null|string $className
     * @return static
     */
    public static function model($className = __CLASS__)
    {
        /** @noinspection PhpIncompatibleReturnTypeInspection */
        return parent::model($className);
    }

    public function tableName()
    {
        return self::TableName;
    }

    public function relations()
    {
        return [
            'Company' => [self::BELONGS_TO, '\company\models\Company', 'CompanyId'],
            'User' => [self::BELONGS_TO, '\user\models\User', 'UserId'],
        ];
    }

    public function __toString()
    {
        return $this->Company->Name.(!empty($this->Position) ? ', '.$this->Position : '');
    }

    /**
     * @static
     * @param int $userId
     * @return void
     */
    public static function ResetAllUserPrimary($userId)
    {
        Yii::app()->db->createCommand()->update(self::TableName, ['Primary' => 0],
            'UserId = :UserId', [':UserId' => $userId]);
    }

    /**
     * @return \company\models\Company
     */
    public function GetCompany()
    {
        $company = $this->Company;
        if (isset($company)) {
            return $company;
        } else {
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
        $result = [];

        $result['year'] = intval(substr($date, 0, 4));
        $result['month'] = intval(substr($date, 5, 2));
        $result['day'] = intval(substr($date, 8, 2));

        return $result;
    }

    /**
     * Возвращает отформатированную дату начала работы
     *
     * @param string $format
     *
     * @return string
     */
    public function GetFormatedStartWorking($format = 'LLLL yyyy')
    {
        if (-1 === $date = mktime(0, 0, 0, $this->StartMonth, 1, $this->StartYear)) {
            return 'неизвестно';
        }

        return Yii::app()->getDateFormatter()->format($format, $date);
    }

    /**
     * Устанавливает корректную дату начала работы из массива day, month, year
     *
     * @param array $date
     * @return void
     */
    public function SetParsedStartWorking($date)
    {
        if (empty($date['year']) || intval($date['year']) == 0) {
            $result = '0000';
        } else {
            $result = $date['year'];
        }
        $result .= '-';
        if (empty($date['month']) || intval($date['month']) == 0) {
            $result .= '00';
        } else {
            $result .= $date['month'];
        }
        $result .= '-';
        if (empty($date['day']) || intval($date['day']) == 0) {
            $result .= '00';
        } else {
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
        $result = [];

        $result['year'] = intval(substr($date, 0, 4));
        $result['month'] = intval(substr($date, 5, 2));
        $result['day'] = intval(substr($date, 8, 2));

        return $result;
    }

    /**
     * Возвращает отформатированную дату начала работы
     *
     * @param string $format
     *
     * @return string
     */
    public function GetFormatedFinishWorking($format = 'LLLL yyyy')
    {
        if (-1 === $date = mktime(0, 0, 0, $this->EndMonth, 1, $this->EndYear)) {
            return 'неизвестно';
        }

        return Yii::app()->getDateFormatter()->format($format, $date);
    }

    /**
     * Устанавливает корректную дату начала работы из массива day, month, year
     *
     * @param array $date
     * @return void
     */
    public function SetParsedFinishWorking($date)
    {
        if (empty($date['year']) || intval($date['year']) == 0 || empty($date['month']) || $date['month'] == 0) {
            $result = '9999';
        } else {
            $result = $date['year'];
        }
        $result .= '-';
        if (empty($date['month']) || intval($date['month']) == 0) {
            $result .= '00';
        } else {
            $result .= $date['month'];
        }
        $result .= '-';
        if (empty($date['day']) || intval($date['day']) == 0) {
            $result .= '00';
        } else {
            $result .= $date['day'];
        }
        $this->FinishWorking = $result;
    }

    public function chageCompany($companyFullName)
    {
        $company = Company::create($companyFullName);
        $this->CompanyId = $company->Id;
    }

    /**
     *
     * @return null|\stdClass
     */
    public function getWorkingInterval()
    {
        if (empty($this->StartYear)) {
            return null;
        }

        $result = new \stdClass();
        $result->Years = 0;
        $result->Months = 0;

        $dateStart = $this->StartYear.'-'.(!empty($this->StartMonth) ? $this->StartMonth : '1').'-1';

        if (!empty($this->EndYear)) {
            $dateEnd = $this->EndYear.'-'.(!empty($this->EndMonth) ? $this->EndMonth : '1').'-1';
        } else {
            $dateEnd = date('Y-n-j');
        }

        $dtStart = new \DateTime($dateStart);
        $dtEnd = new \DateTime($dateEnd);
        if (!empty($this->StartMonth) || !empty($this->EndMonth)) {
            $dtEnd->modify('+1 month');
        }

        $diff = $dtStart->diff($dtEnd);
        $result->Years = $diff->format('%y');
        $result->Months = $diff->format('%m');

        return $result;
    }
}