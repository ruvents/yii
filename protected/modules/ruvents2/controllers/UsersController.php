<?php
namespace ruvents2\controllers;

use application\components\helpers\ArrayHelper;
use ruvents2\components\Controller;
use ruvents2\components\Exception;
use user\models\User;

class UsersController extends Controller
{
    const MAX_LIMIT = 50;

    public function actionList($query = null)
    {
        if (empty($query)) {
            throw Exception::createInvalidParam('query', 'Строка поиска не может быть пустой');
        }

        $users = [];
        if (filter_var($query, FILTER_VALIDATE_EMAIL)) {
            $user = User::model()->byEmail($query)->byVisible(true)->find();
            if ($user !== null) {
                $users[] = $user;
            }
        } else {
            $userModel = \user\models\User::model()->bySearch($query);
            $criteria = new \CDbCriteria();
            $criteria->with = [
                'Employments' => ['together' => false],
                'Employments.Company' => ['together' => false],
                'LinkPhones.Phone' => ['together' => false]
            ];
            $criteria->limit = self::MAX_LIMIT;
            $users = $userModel->findAll($criteria);
        }

        $result = [];
        foreach ($users as $user) {
            $result[] = $this->getUserData($user);
        }

        $this->renderJson(['Users' => $result]);
    }

    /**
     * @param User $user
     * @return array
     */
    public function getUserData($user)
    {
        $data = ArrayHelper::toArray($user, ['user\models\User' => ['Id', 'LastName', 'FirstName', 'FatherName', 'Email']]);

        $employment = $user->getEmploymentPrimary();
        if ($employment !== null) {
            $data['Company'] = $employment->Company->Name;
            $data['Position'] = $employment->Position;
        }

        if (!empty($user->PrimaryPhone)) {
            $data['Phone'] = $user->PrimaryPhone;
        } elseif ($user->getContactPhone() !== null) {
            $data['Phone'] = $user->getContactPhone()->__toString();
        }

        return $data;
    }
}