<?php

namespace connect\models;

use application\components\ActiveRecord;
use user\models\User;

/**
 * @property integer $Id
 * @property integer $CreatorId
 * @property integer $PlaceId
 * @property string $Date
 * @property integer $Type
 * @property string $CreateTime
 * @property integer $ReservationNumber
 *
 * @property Place $Place
 * @property User $Creator
 * @property MeetingLinkUser[] $UserLinks
 *
 * @method Meeting byPlaceId(int $id)
 * @method Meeting byUserId(int $id)
 * @method Meeting byCreatorId(int $id)
 * @method Meeting byType(int $id)
 * @method Meeting byReservationNumber(int $id)
 *
 * @method Meeting with($condition='')
 * @method Meeting find($condition='',$params=array())
 * @method Meeting findByPk($pk,$condition='',$params=array())
 * @method Meeting findByAttributes($attributes,$condition='',$params=array())
 * @method Meeting[] findAll($condition='',$params=array())
 * @method Meeting[] findAllByAttributes($attributes,$condition='',$params=array())
 */
class Meeting extends ActiveRecord
{
    const TYPE_PRIVATE = 1;
    const TYPE_PUBLIC = 2;

    public function tableName()
    {
        return 'ConnectMeeting';
    }

    public function relations()
    {
        return [
            'Place' => [self::BELONGS_TO, '\connect\models\Place', 'PlaceId'],
            'Creator' => [self::BELONGS_TO, '\user\models\User', 'CreatorId'],
            'UserLinks' => [self::HAS_MANY, '\connect\models\MeetingLinkUser', 'MeetingId']
        ];
    }

    public function getFileDir()
    {
        $dir = \Yii::getPathOfAlias('webroot').'/files/connect';
        if (!is_dir($dir)){
            mkdir($dir, 0755, true);
        }
        return $dir;
    }

    public function getFileUrl()
    {
        return '/files/connect/'.$this->File;
    }
}