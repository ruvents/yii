<?php
namespace api\models\forms\admin;

use api\models\Account as AccountModel;

class Account extends \CFormModel
{
    public $Id;
    public $EventId;
    public $EventTitle;
    public $Ips = [];
    public $Domains = [];
    public $Key;
    public $Secret;
    public $Delete;
    public $Role;
    public $RequestPhoneOnRegistration;
    public $QuotaByUser;
    public $QuotaByUserCounter;
    public $Blocked;
    public $BlockedReason;

    public function rules()
    {
        return [
            ['EventTitle, Role', 'required'],
            ['RequestPhoneOnRegistration', 'safe'],
            ['EventId', 'exist', 'attributeName' => 'Id', 'className' => '\event\models\Event'],
            ['Ips', 'filter', 'filter' => [$this, 'filterIps']],
            ['Domains', 'filter', 'filter' => [$this, 'filterDomains']],
            ['Key, Secret', 'safe'],
            ['QuotaByUser', 'numerical'],
            ['Blocked', 'boolean']
        ];
    }

    public function attributeLabels()
    {
        return [
            'EventTitle' => \Yii::t('app', 'Название мероприятия'),
            'EventId'    => \Yii::t('app', 'Id меропрития'),
            'Ips'        => \Yii::t('app', 'IP адреса'),
            'Domains'    => \Yii::t('app', 'Домены'),
            'Role' => \Yii::t('app', 'Тип аккаунта'),
            'RequestPhoneOnRegistration' => \Yii::t('app', 'Запрашивать номер телефона при регистрации'),
            'QuotaByUser' => \Yii::t('app', 'Квота'),
            'Blocked' => \Yii::t('app', 'Заблокирован')
        ];
    }

    public function filterDomains($value)
    {
        foreach ($value as $val)
        {
            if (empty($val))
            {
                $this->addError('Domains', \Yii::t('app', 'Некорректно заполнен домен.'));
                break;
            }
        }
        return $value;
    }

    public function filterIps($value)
    {
        foreach ($value as $val)
        {
            if (empty($val))
            {
                $this->addError('Ips', \Yii::t('app', 'Некорректно заполнен IP адрес.'));
                break;
            }
        }
        return $value;
    }

    public function getRoles()
    {
        $roles = [
            '' => 'Выберите тип аккаунта'
        ];
        $roles = array_merge($roles, AccountModel::getRoleLabels());
        return $roles;
    }

    public function getRequestPhoneOnRegistrationStatusData()
    {
        return [
            \application\models\RequiredStatus::None => \Yii::t('app', 'Нет'),
            \application\models\RequiredStatus::Required => \Yii::t('app', 'Да, обязательный ввод'),
            \application\models\RequiredStatus::NotRequired => \Yii::t('app', 'Да, но не обязательно')
        ];
    }

}
