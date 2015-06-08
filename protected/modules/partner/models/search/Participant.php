<?php
/**
 * Created by PhpStorm.
 * User: Андрей
 * Date: 28.05.2015
 * Time: 17:06
 */

namespace application\modules\partner\models\search;


use application\components\form\SearchFormModel;
use application\components\web\ActiveDataProvider;
use user\models\User;
use event\models\Event;

class Participant extends SearchFormModel
{
    /** @var Event */
    private $event;

    public function __construct(Event $event)
    {
        $this->event = $event;
        parent::__construct('');
    }

    public $Query;

    public $Role;

    public $Company;

    public $Ruvents;

    public function rules()
    {
        return [
            ['Query,Company', 'filter', 'filter' => '\application\components\utility\Texts::clear'],
            ['Role', 'type', 'type' => 'array']
        ];
    }

    public function attributeLabels()
    {
        return [
            'Name' => 'ФИО или E-mail',
            'Role' => 'Статус',
            'Company' => 'Работа',
            'Ruvents' => 'Регистрация'
        ];
    }


    /**
     * @return \CDataProvider
     */
    public function getDataProvider()
    {
        $sort = $this->getSort();
        $criteria = new \CDbCriteria();
        $criteria->with = [
            'Participants' => [
                'on' => '"Participants"."EventId" = :EventId',
                'params' => ['EventId' => $this->event->Id],
                'together' => false
            ],
            'ParticipantsForCriteria' => [
                'together' => true,
                'select' => false,
                'on' => '"ParticipantsForCriteria"."EventId" = :EventId',
                'params' => ['EventId' => $this->event->Id],
            ],
            'Badges' => [
                'together' => false,
                'order' => '"Badges"."CreationTime" ASC',
                'with' => ['Operator'],
                'on' => '"Badges"."EventId" = :EventId',
                'params' => [
                    'EventId' => $this->event->Id
                ]
            ]
        ];

        $criteria->addInCondition('"t"."Id"', \CHtml::listData(User::model()->findAll($this->getCriteria()), 'Id', 'Id'));

        if (array_key_exists('Ruvents', $sort->getDirections())) {
            $criteria->with['Badges']['together'] = true;
            $criteria->with['Badges']['on'] = '"Badges"."Id" IN (
                SELECT MIN("Id") FROM "RuventsBadge"
                WHERE "EventId" = :EventId
                GROUP BY "UserId"
            )';
        } elseif (array_key_exists('Role', $sort->getDirections())) {
            $criteria->group = '"t"."Id","ParticipantsForCriteria"."Id"';
        }

        return new \CActiveDataProvider('\user\models\User', [
            'criteria' => $criteria,
            'sort' => $sort,
            'pagination' => [
                'pageSize' => 10,
            ]
        ]);
    }

    /**
     * @return \CDbCriteria
     */
    private function getCriteria()
    {
        $criteria = new \CDbCriteria();
        $criteria->addCondition('"Participants"."EventId" = :EventId');
        $criteria->params['EventId'] = $this->event->Id;
        $criteria->with = [
            'Participants' => [
                'together' => true,
                'select' => false
            ],
            'EmploymentsForCriteria' => [
                'together' => true,
                'select' => false,
                'with' => [
                    'Company' => ['select' => false]
                ]
            ]
        ];

        if ($this->validate()) {
            if ($this->Query != '') {
                $this->Query = trim($this->Query);
                if (filter_var($this->Query, FILTER_VALIDATE_EMAIL) !== false) {
                    $criteria->addCondition('"t"."Email" = :Email');
                    $criteria->params['Email'] = $this->Query;
                } else {
                    $criteria->mergeWith(User::model()->bySearch($this->Query, null, true, false)->getDbCriteria());
                }
            }
            if (!empty($this->Role)) {
                $criteria->addInCondition('"Participants"."RoleId"', $this->Role);
            }

            if (!empty($this->Company)) {
                $criteria->addCondition('"Company"."Name" ILIKE :Company AND "EmploymentsForCriteria"."Primary"');
                $criteria->params['Company'] = '%' . $this->Company . '%';
            }
        }

        return $criteria;
    }

    public function getSort()
    {
        $sort = new \CSort();
        $sort->defaultOrder = ['Role' => SORT_DESC];
        $sort->attributes = [
            'Query' => 't.RunetId',
            'Name'  => [
                'asc'  => '"t"."LastName" ASC, "t"."FirstName" ASC',
                'desc' => '"t"."LastName" DESC, "t"."FirstName" DESC',
            ],
            'Role' => [
                'asc'  => 'max("ParticipantsForCriteria"."CreationTime") ASC',
                'desc' => 'max("ParticipantsForCriteria"."CreationTime") DESC'
            ],
            'Ruvents' => [
                'asc'  => '"Badges"."CreationTime" ASC',
                'desc' => '"Badges"."CreationTime" DESC'
            ]
        ];
        return $sort;

    }

    /**
     * @return array
     */
    public function getRoleData()
    {
        return \CHtml::listData($this->event->getRoles(), 'Id', 'Title');
    }
}