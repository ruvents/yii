<?php
namespace pay\models;

use application\models\translation\ActiveRecord;

/**
 * @property int $Id
 * @property int $EventId
 * @property bool $Own
 * @property string $OrderTemplateName
 * @property string $ReturnUrl
 * @property string $Offer
 * @property string $OrderLastTime
 * @property bool $OrderEnable
 * @property bool $Uniteller
 * @property bool $UnitellerRuvents
 * @property bool $PayOnline
 * @property bool $PayOnlineRuvents
 * @property bool $CloudPayments
 * @property bool $WalletOne
 * @property bool $MailRuMoney
 * @property int $OrderTemplateId
 * @property bool $SandBoxUser
 * @property string $SandBoxUserRegisterUrl
 * @property string $ReceiptName
 * @property int $ReceiptTemplateId
 * @property string $ReceiptLastTime
 * @property bool $ReceiptEnable
 * @property string $OrderDisableMessage
 * @property int $OrderMinTotal
 * @property string $OrderMinTotalMessage
 * @property string $AfterPayUrl
 * @property string $CabinetIndexTabTitle
 * @property string $CabinetHasRecentPaidItemsMessage
 * @property string $CabinetJuridicalCreateInfo
 *
 * @property \event\models\Event $Event
 * @property OrderJuridicalTemplate $OrderTemplate
 * @property OrderJuridicalTemplate $ReceiptTemplate
 *
 * @method \pay\models\Account find($condition = '', $params = array())
 * @method \pay\models\Account findByPk($pk, $condition = '', $params = array())
 * @method \pay\models\Account[] findAll($condition = '', $params = array())
 *
 */
class Account extends ActiveRecord
{
    /**
     * @param string $className
     *
     * @return Account
     */
    public static function model($className = __CLASS__)
    {
        return parent::model($className);
    }

    public function tableName()
    {
        return 'PayAccount';
    }

    public function primaryKey()
    {
        return 'Id';
    }

    public function relations()
    {
        return array(
            'Event' => [self::BELONGS_TO, '\event\models\Event', 'EventId'],
            'OrderTemplate' => [self::BELONGS_TO, '\pay\models\OrderJuridicalTemplate', 'OrderTemplateId'],
            'ReceiptTemplate' => [self::BELONGS_TO, '\pay\models\OrderJuridicalTemplate', 'ReceiptTemplateId'],
        );
    }

    /**
     * @param int $eventId
     * @param bool $useAnd
     *
     * @return Account
     */
    public function byEventId($eventId, $useAnd = true)
    {
        $criteria = new \CDbCriteria();
        $criteria->condition = '"t"."EventId" = :EventId';
        $criteria->params = array('EventId' => $eventId);
        $this->getDbCriteria()->mergeWith($criteria, $useAnd);
        return $this;
    }

    /**
     * @return string[]
     */
    public function getTranslationFields()
    {
        return ['OrderDisableMessage'];
    }
}
