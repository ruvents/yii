<?php
namespace pay\models;

use application\components\ActiveRecord;

/**
 * @property int $Id
 * @property string $Title
 * @property string $Recipient
 * @property string $Address
 * @property int $INN
 * @property int $KPP
 * @property string $Bank
 * @property int $BankAccountNumber
 * @property int $AccountNumber
 * @property int $BIK
 * @property string $Phone
 * @property string $Fax
 * @property string $SignFirstTitle
 * @property string $SignFirstName
 * @property int[] $SignFirstImageMargin
 * @property string $SignSecondTitle
 * @property string $SignSecondName
 * @property int[] $SignSecondImageMargin
 * @property int[] $StampImageMargin
 * @property bool $VAT
 * @property string $OrderTemplateName
 * @property string $NumberFormat
 * @property int $Number
 * @property int $ParentTemplateId
 * @property string $CreationTime
 * @property string $OfferText
 * @property bool $ShowValidity
 * @property int $ValidityDays
 *
 * Описание вспомогательных методов
 * @method OrderJuridicalTemplate   with($condition = '')
 * @method OrderJuridicalTemplate   find($condition = '', $params = [])
 * @method OrderJuridicalTemplate   findByPk($pk, $condition = '', $params = [])
 * @method OrderJuridicalTemplate   findByAttributes($attributes, $condition = '', $params = [])
 * @method OrderJuridicalTemplate[] findAll($condition = '', $params = [])
 * @method OrderJuridicalTemplate[] findAllByAttributes($attributes, $condition = '', $params = [])
 *
 * @method OrderJuridicalTemplate byId(int $id, bool $useAnd = true)
 */
class OrderJuridicalTemplate extends ActiveRecord
{
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
        return 'PayOrderJuridicalTemplate';
    }

    protected function beforeSave()
    {
        if (is_array($this->SignFirstImageMargin)) {
            $this->SignFirstImageMargin = implode(',', $this->SignFirstImageMargin);
        }

        if (is_array($this->SignSecondImageMargin)) {
            $this->SignSecondImageMargin = implode(',', $this->SignSecondImageMargin);
        }

        if (is_array($this->StampImageMargin)) {
            $this->StampImageMargin = implode(',', $this->StampImageMargin);
        }

        return parent::beforeSave();
    }

    protected function afterFind()
    {
        if ($this->SignFirstImageMargin !== null) {
            $this->SignFirstImageMargin = explode(',', trim($this->SignFirstImageMargin, ')('));
        }
        if ($this->SignSecondImageMargin !== null) {
            $this->SignSecondImageMargin = explode(',', trim($this->SignSecondImageMargin, ')('));
        }
        if ($this->StampImageMargin !== null) {
            $this->StampImageMargin = explode(',', trim($this->StampImageMargin, ')('));
        }
        parent::afterFind();
    }

    protected function afterSave()
    {
        if ($this->getIsNewRecord()) {
            mkdir(\Yii::getPathOfAlias('webroot.img.pay.bill.template').DIRECTORY_SEPARATOR.$this->Id.DIRECTORY_SEPARATOR);
        }
        parent::afterSave();
    }

    public function getFirstSignImagePath($absolute = false)
    {
        return $this->getImagePath('fist-sign.png', $absolute);
    }

    public function getSecondSignImagePath($absolute = false)
    {
        return $this->getImagePath('secong-sign.png', $absolute);
    }

    public function getStampImagePath($absolute = false)
    {
        return $this->getImagePath('stamp.png', $absolute);
    }

    private function getImagePath($name, $absolute = false)
    {
        $id = $this->ParentTemplateId !== null ? $this->ParentTemplateId : $this->Id;

        return ($absolute ? \Yii::getPathOfAlias('webroot') : '').'/img/pay/bill/template/'.$id.'/'.$name;
    }

    public function getNextNumber()
    {
        $sql = 'SELECT "Number" FROM "'.$this->tableName().'" WHERE "Id" = :Id FOR UPDATE';
        $command = \Yii::app()->getDb()->createCommand($sql);
        $number = $command->queryScalar(['Id' => $this->Id]);
        $sql = 'UPDATE "'.$this->tableName().'" SET "Number" = "Number" + 1 WHERE "Id" = :Id';
        \Yii::app()->getDb()->createCommand($sql)->execute(['Id' => $this->Id]);

        return sprintf($this->NumberFormat, $number);
    }

    /**
     * @param int $parentId
     * @param bool $useAnd
     *
     * @return $this
     */
    public function byParentId($parentId, $useAnd = true)
    {
        $criteria = new \CDbCriteria();
        $criteria->condition = '"t"."ParentTemplateId" = :ParentId';
        $criteria->params = ['ParentId' => $parentId];
        $this->getDbCriteria()->mergeWith($criteria, $useAnd);

        return $this;
    }
}