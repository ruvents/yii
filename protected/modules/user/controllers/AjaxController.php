<?php
use event\models\UserData;
use user\models\forms\RegisterForm;
use user\models\User;

class AjaxController extends \application\components\controllers\PublicMainController
{
  public function actions()
  {
    return [
      'phoneverify' => '\user\controllers\ajax\PhoneVerifyAction'
    ];
  }

  public function actionSearch($term, $eventId = null)
  {
    $results = array();
    $criteria = new \CDbCriteria();
    $criteria->limit = 10;
    $criteria->with = ['Employments.Company'];
    $model = \user\models\User::model();
    if ($eventId !== null)
    {
      $model->bySearch($term, null, true, false)->byEventId($eventId);
    }
    else
    {
      $model->bySearch($term);
    }
    /** @var $users \user\models\User[] */
    $users = $model->findAll($criteria);
    foreach ($users as $user)
    {
      $results[] = $this->getUserData($user);
    }
    echo json_encode($results);
  }

    public function actionRegister()
    {
        $result = new \stdClass();
        $request = \Yii::app()->getRequest();
        $form = new RegisterForm();
        $form->attributes = $request->getParam(get_class($form));

        if ($form->validate()) {
            $user = $this->createUser($form);
            $result->success = true;
            $result->user = $this->getUserData($user);
        } else {
            $user = \user\models\User::model()->byEmail($form->Email)->byVisible(true)->find();
            $isUserExist = $user !== null && $user->LastName === $form->LastName;
            if ($isUserExist) {
                $result->success = true;
                $result->user = $this->getUserData($user);
            } else {
                $result->success = false;
                $result->errors  = $form->getErrors();
            }
        }
        echo json_encode($result);
    }

    /**
     * @param RegisterForm $form
     * @return User
     */
    private function createUser(RegisterForm $form)
    {
        $user = new \user\models\User();
        $user->LastName = $form->LastName;
        $user->FirstName = $form->FirstName;
        $user->FatherName = $form->FatherName;
        $user->Email = $form->Email;
        $user->PrimaryPhone = $form->Phone;
        $user->register();
        $user->setEmployment($form->Company, $form->Position);
        return $user;
    }

    /**
     * @return User|null
     */
    private function getCreator()
    {
        if (\Yii::app()->user->getCurrentUser() !== null) {
            return \Yii::app()->user->getCurrentUser();
        } elseif (\Yii::app()->tempUser->getCurrentUser() !== null) {
            return \Yii::app()->tempUser->getCurrentUser();
        }
        return null;
    }

    /**
     * @param User $user
     * @return stdClass
     */
    private function getUserData($user)
  {
    $data = new \stdClass();
    $data->RunetId = $data->value = $user->RunetId;
    $data->LastName = $user->LastName;
    $data->FirstName = $user->FirstName;
    $data->FullName = $data->label = $user->getFullName();
    $data->Photo = new \stdClass();
    $data->Photo->Small = $user->getPhoto()->get50px();
    $data->Photo->Medium = $user->getPhoto()->get90px();
    $data->Photo->Large = $user->getPhoto()->get200px();
    if ($user->getEmploymentPrimary() !== null)
    {
      $data->Company = $user->getEmploymentPrimary()->Company->Name;
      $data->Position = trim($user->getEmploymentPrimary()->Position);
    }
    return $data;
  }
}
