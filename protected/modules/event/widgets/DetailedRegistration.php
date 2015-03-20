<?php
namespace event\widgets;

use application\components\auth\identity\RunetId;
use application\components\utility\Texts;
use contact\models\Address;
use event\components\WidgetPosition;
use \event\models\forms\DetailedRegistration as DetailedRegistrationForm;
use event\models\Invite;
use event\models\Participant;
use event\models\Role;
use event\models\UserData;
use user\models\User;

class DetailedRegistration extends \event\components\Widget
{
    public function getAttributeNames()
    {
        return [
            'DefaultRoleId',
            'SelectRoleIdList',
            'RegisterUnvisibleUser',
            'ShowEmployment',
            'ShowFatherName',
            'ShowAddress',
            'ShowBirthday',
            'RegistrationBeforeInfo',
            'UseInvites',
            'ShowUserDataLabel',
            'RegistrationCompleteText'
        ];
    }

    /** @var \event\models\forms\DetailedRegistration */
    public $form;

    /** @var  UserData */
    public $userData = null;

    /**
     * @var Invite
     */
    public $invite = null;

    public function init()
    {
        parent::init();
        $this->initForm();
        $this->initUserData();
        if (isset($this->UseInvites) && $this->UseInvites) {
            $code = \Yii::app()->getRequest()->getParam('invite');
            $this->invite = Invite::model()->byEventId($this->getEvent()->Id)->byCode($code)->find();
            if ($this->invite == null || !empty($this->invite->UserId)) {
                $this->form->addError('Invite', \Yii::t('app','Для регистрации на мероприятие «{event}» требуется приглашение.', ['{event}' => $this->event->Title]));
            }
        }
    }

    /**
     * Инициализация основной формы
     */
    private function initForm()
    {
        $scenario = '';
        if (isset($this->ShowEmployment) && $this->ShowEmployment) {
            $scenario = DetailedRegistrationForm::ScenarioShowEmployment;
        }

        $roles = [];
        if (isset($this->SelectRoleIdList)) {
            $roles = Role::model()->findAllByPk(explode(',', $this->SelectRoleIdList));
        }

        $user = \Yii::app()->getUser();
        $this->form = new DetailedRegistrationForm($user->getCurrentUser(), $scenario, $roles);
        if (isset($this->DefaultRoleId)) {
            $this->form->RoleId = $this->DefaultRoleId;
        }
    }

    /**
     * Инициализация дополнительных полей пользователя
     */
    private function initUserData()
    {
        $data = new UserData();
        $data->EventId = $this->getEvent()->Id;

        $definitions = $data->getManager()->getDefinitions();
        if (!empty($definitions)) {
            $this->userData = $data;
        }
    }

    public function getIsHasDefaultResources()
    {
        return true;
    }


    public function process()
    {
        $request = \Yii::app()->getRequest();
        if ($request->getIsPostRequest()) {
            $this->form->attributes = $request->getParam(get_class($this->form));
            $this->form->validate(null, false);

            if ($this->userData !== null) {
                $this->userData->getManager()->setAttributes(
                    $request->getParam(get_class($this->userData->getManager()))
                );
                $this->userData->getManager()->validate();
            }

            if (!$this->form->hasErrors() && ($this->userData == null || !$this->userData->getManager()->hasErrors())) {
                $user = $this->updateUser($this->form->getUser());
                if ($this->invite !== null) {
                    $this->invite->activate($user);
                }
                else {
                    $role = Role::model()->findByPk($this->form->RoleId);
                    $this->getEvent()->registerUser($user, $role);
                }

                if ($this->userData !== null) {
                    $this->userData->UserId = $user->Id;
                    $this->userData->save();
                }

                if (\Yii::app()->getUser()->getIsGuest()) {
                    $identity = new RunetId($user->RunetId);
                    $identity->authenticate();
                    if ($identity->errorCode == \CUserIdentity::ERROR_NONE) {
                        \Yii::app()->getUser()->login($identity);
                    }
                }
                $this->getController()->refresh();
            } elseif ($this->userData !== null) {
                $this->form->addErrors($this->userData->getManager()->getErrors());
            }
        }
    }


    public function run()
    {
        $user = \Yii::app()->user;

        /** @var Participant $participant */
        $participant = null;
        if (!$user->getIsGuest()) {
            $participant = Participant::model()->byEventId($this->event->Id)->byUserId($user->getCurrentUser()->Id)->find();
        }

        if ($participant == null) {
            \Yii::app()->getClientScript()->registerPackage('runetid.jquery.inputmask-multi');
            $this->render('detailed-registration');
        } else {
            $this->render('detailed-registration-complete');
        }

    }

    /**
     * @return string
     */
    public function getTitle()
    {
        return \Yii::t('app', 'Детальная регистрация на мероприятии');
    }

    /**
     * @return string
     */
    public function getPosition()
    {
        return WidgetPosition::Content;
    }


    /**
     * @param User $user
     * @return User
     */
    private function updateUser($user)
    {
        if ($user === null) {
            $user = new User();
            $user->LastName = $this->form->LastName;
            $user->FirstName = $this->form->FirstName;
            $user->FatherName = $this->form->FatherName;
            $user->PrimaryPhone = $this->form->PrimaryPhone;
            $user->Email = $this->form->Email;
            $user->Visible = !isset($this->RegisterUnvisibleUser) || !$this->RegisterUnvisibleUser;
            $user->register($user->Visible);

            if ($this->getEvent()->UnsubscribeNewUser) {
                $user->Settings->UnsubscribeAll = true;
                $user->Settings->save();
            }
        }
        else {
            if (empty($user->PrimaryPhone)) {
                $user->PrimaryPhone = $this->form->PrimaryPhone;
            }
        }

        $user->FatherName = $this->form->FatherName;
        $user->Birthday = \Yii::app()->dateFormatter->format('yyyy-MM-dd', $this->form->Birthday);
        $user->save();

        if (isset($this->ShowEmployment) && $this->ShowEmployment) {
            $employment = $user->getEmploymentPrimary();
            if ($employment === null || $employment->Position !== $this->form->Position || $employment->Company->Name !== $this->form->Company) {
                $user->setEmployment($this->form->Company, $this->form->Position);
            }
        }

        $address = $user->getContactAddress();
        if ($address == null) {
            $address = new Address();
        }
        $address->RegionId = $this->form->Address->RegionId;
        $address->CountryId = $this->form->Address->CountryId;
        $address->CityId = $this->form->Address->CityId;
        $address->save();
        $user->setContactAddress($address);

        return $user;
    }
}