<?php
namespace event\components;

use application\components\attribute\BooleanDefinition;
use application\components\attribute\Definition;
use application\components\attribute\IntegerDefinition;
use application\components\attribute\JsonContainer;
use application\components\attribute\ListDefinition;
use application\models\attribute\Group;
use event\models\UserData;
use \application\models\attribute\Definition as DefinitionModel;

/**
 * Class UserDataManager
 * @package event\components
 *
 * @property UserData $model
 *
 * @method UserData model()
 */
class UserDataManager extends \CModel
{
    use JsonContainer;

    /**
     * @param UserData $userData
     */
    public function __construct($userData)
    {
        $this->initJsonContainer($userData);
    }

    /**
     * Имя json-поля модели, для хранения данных
     * @return string
     */
    protected function containerName()
    {
        return 'Attributes';
    }

    /**
     * Описание хранимых в контейнере аттрибутов.
     * Возможны 2 способа задания:
     * 1. ['Name1', 'Name2', ...] - список имен хранимых полей. В данном случае все поля
     * определяют объекты класса Definition
     * 2. [['Name', 'DefinitionClass', ...params],...] - каждое хранимое поле определяется тем классом,
     * который был указан после имени поля. Также возможно задать дополнительные параметры,
     * соответствующие DefinitionClass.
     * @param bool $onlyPublic
     * @return string[]|array
     */
    protected function attributeDefinitions($onlyPublic = false)
    {
        $model = DefinitionModel::model()
            ->byModelName('EventUserData')
            ->byModelId($this->model()->EventId)
            ->ordered();

        if ($onlyPublic) {
            $model->byPublic(true);
        }

        $definitions = $model->findAll();

        $result = [];
        foreach ($definitions as $definition) {
            $row = [];
            $row[] = $definition->Name;
            $row[] = $definition->ClassName;
            $row[] = $definition->GroupId;
            $row['title'] = $definition->Title;
            $row['required'] = $definition->Required;
            $row['secure'] = $definition->Secure;
            foreach ($definition->getParams() as $key => $value) {
                $row[$key] = $value;
            }
            $result[] = $row;
        }
        return $result;
    }

    public function attributeGroups()
    {
        $groups = Group::model()
            ->byModelName('EventUserData')->byModelId($this->model()->EventId)
            ->findAll(['order' => '"t"."Order"']);
        $result = [];
        foreach ($groups as $group) {
            $result[] = [$group->Id, $group->Title, $group->Description];
        }
        return $result;
    }

    public function rules()
    {
        $rules = $this->definitionRules();
        if ($this->model()->EventId == 1375) {
            $rules[] = ['AccountFB', 'checkSocials'];
        }
        return $rules;
    }

    public function checkSocials()
    {
        if (empty($this->AccountFB) && empty($this->AccountOK) && empty($this->AccountVK)) {
            $this->addError('', 'Необходимо заполнить аккаунт хотя бы одной социальной сети.');
        }
    }
}