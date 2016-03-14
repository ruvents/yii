<?php
namespace event\models;

use application\components\ActiveRecord;
use event\components\UserDataManager;
use user\models\User;

/**
 * Class UserData
 *
 * @property int $Id
 * @property int $EventId
 * @property int $UserId
 * @property int $CreatorId
 * @property string $Attributes
 * @property string $CreationTime
 * @property bool $Deleted
 *
 * @property Event $Event
 * @property User $User
 * @property User $Creator
 *
 * @method UserData find($condition='',$params=array())
 * @method UserData findByPk($pk,$condition='',$params=array())
 * @method UserData[] findAll($condition='',$params=array())
 */

class UserData extends ActiveRecord
{
    protected $manager;

    /**
     * Creates an empty user data record
     * @param Event $event
     * @param User $user
     * @return self
     */
    public static function createEmpty($event, $user)
    {
        if ($event instanceof Event) {
            $event = $event->Id;
        }

        if ($user instanceof User) {
            $user = $user->Id;
        }

        $model = new UserData();
        $model->EventId = $event;
        $model->UserId = $user;
        $definitions = $model->getManager()->getDefinitions();

        if (empty($definitions) || self::model()->byEventId($event)->byUserId($user)->exists()) {
            return $model;
        }

        $model->save();

        return $model;
    }

    /**
     * @param Event $event
     * @param User $user
     * @return string[]
     */
    public static function getDefinedAttributes($event, $user)
    {
        $userDataModels = UserData::model()
            ->byEventId($event->Id)
            ->byUserId($user->Id)
            ->findAll(['order' => 't."CreationTime" DESC']);

        $attributeNames = [];
        foreach ($userDataModels as $userData) {
            $manager = $userData->getManager();
            foreach ($manager->getDefinitions() as $definition) {
                $name = $definition->name;
                if (!empty($manager->{$name})) {
                    $attributeNames[] = $name;
                }
            }
        }

        $attributeNames = array_unique($attributeNames);

        return $attributeNames;
    }


    /**
     * @param Event $event
     * @param User $user
     * @return array
     */
    public static function getDefinedAttributeValues(Event $event, User $user)
    {
        $values = [];

        $userDataModels = $event->getUserData($user);
        if (!empty($userDataModels)) {
            foreach ($userDataModels as $userData) {
                $manager = $userData->getManager();
                foreach ($manager->getDefinitions() as $definition) {
                    $name = $definition->name;
                    if (!isset($values[$name]) && !empty($manager->$name)) {
                        $values[$name] = $manager->$name;
                    }
                }
            }
        }

        return $values;
    }

    /**
     * @inheritdoc
     */
    public function tableName()
    {
        return 'EventUserData';
    }

    /**
     * @inheritdoc
     */
    public function relations()
    {
        return [
            'Event' => [self::BELONGS_TO, 'event\models\Event', 'EventId'],
            'User' => [self::BELONGS_TO, 'user\models\User', 'UserId'],
            'Creator' => [self::BELONGS_TO, 'user\models\User', 'CreatorId'],
        ];
    }

    /**
     * @return UserDataManager
     */
    public function getManager()
    {
        if (!$this->manager) {
            $this->manager = new UserDataManager($this);
        }

        return $this->manager;
    }

    /**
     * @param int $eventId
     * @param bool $useAnd
     * @return UserData
     */
    public function byEventId($eventId, $useAnd = true)
    {
        $criteria = new \CDbCriteria();
        $criteria->condition = '"t"."EventId" = :EventId';
        $criteria->params = ['EventId' => $eventId];
        $this->getDbCriteria()->mergeWith($criteria, $useAnd);
        return $this;
    }

    /**
     * @param int $userId
     * @param bool $useAnd
     * @return UserData
     */
    public function byUserId($userId, $useAnd = true)
    {
        $criteria = new \CDbCriteria();
        $criteria->condition = '"t"."UserId" = :UserId';
        $criteria->params = ['UserId' => $userId];
        $this->getDbCriteria()->mergeWith($criteria, $useAnd);
        return $this;
    }

    /**
     * @param bool $deleted
     * @param bool $useAnd
     * @return $this
     */
    public function byDeleted($deleted, $useAnd = true)
    {
        $criteria = new \CDbCriteria();
        $criteria->condition = (!$deleted ? 'NOT ' : '') . '"t"."Deleted"';
        $this->getDbCriteria()->mergeWith($criteria, $useAnd);
        return $this;
    }
}
