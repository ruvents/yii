<?php
namespace user\models\forms;

use application\components\form\CreateUpdateForm;
use application\components\form\CreateUpdateFormCombiner;
use application\components\utility\Texts;
use application\helpers\Flash;
use contact\models\forms\Address;
use CText;
use event\models\Event;
use event\models\Role;
use user\models\forms\fields\Email;
use user\models\forms\fields\Employment;
use user\models\forms\fields\Name;
use user\models\forms\fields\Phone;
use user\models\User;

/**
 * Class Register
 * @package user\models\forms
 *
 */
abstract class Register extends CreateUpdateFormCombiner
{
    /** @var User */
    protected $model;

    /**
     * @inheritdoc
     */
    protected function initForms()
    {
        $this->registerForm(Name::className());
        $this->registerForm(Email::className());
        $this->registerForm(Employment::className());
        $this->registerForm(Phone::className());
    }

    /**
     * @inheritDoc
     */
    public function rules()
    {
        $rules = parent::rules();
        foreach ($rules as $k => $rule) {
            if ($rule[0] === 'Email' && $rule[1] === 'application\components\validators\InlineValidator') {
                $rules[$k][1] = 'validateEmail';
            }
        }
        return $rules;
    }

    /**
     * @param string $attribute
     * @param array $params
     * @return bool|mixed
     */
    public function validateEmail($attribute, $params) {
        if (!$this->isHiddenUser()) {
            return call_user_func_array($params['method'], [$attribute]);
        }
        return true;
    }

    /**
     *
     */
    protected function internalCreateActiveRecord()
    {
        $notify = !$this->isHiddenUser();

        $this->model = new User();
        $this->fillActiveRecord();
        $this->model->PrimaryPhone = $this->Phone;
        $this->model->register($notify);
        if ($this->isHiddenUser()) {
            $this->model->Visible = false;
            $this->model->save();
        }
        if (!empty($this->Company)) {
            $this->model->setEmployment($this->Company, $this->Position);
        }
    }

    /**
     * Возвращает true если будет зарегистрирован скрытый пользователь
     * @return bool
     */
    protected function isHiddenUser()
    {
        return false;
    }
}
