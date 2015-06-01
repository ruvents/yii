<?php
namespace competence\models\form;

use competence\models\Result;

/**
 * Class Single
 * @package competence\models\form
 *
 * @property \competence\models\form\attribute\RadioValue[] $Values
 *
 */
class Single extends Base
{
    public $other;

    protected function getFormData()
    {
        return ['value' => $this->value, 'other' => $this->other];
    }

    public function rules()
    {
        $rules = [];
        $rules[] = $this->question->Required ? ['value', 'required', 'message' => 'Выберите один ответ из списка'] : ['value', 'safe'];
        $rules[] = ['other', 'checkOtherValidator'];
        return $rules;
    }

    public function checkOtherValidator($attribute, $params)
    {
        foreach ($this->Values as $value)
        {
            if ($value->key != $this->value)
                continue;
            if (!$value->isOther)
                break;
            $this->other = trim($this->other);
            if (empty($this->other))
            {
                $this->addError('', $this->getOtherValidatorErrorMessage());
                return false;
            }
        }
        return true;
    }

    public function getOtherValidatorErrorMessage()
    {
        return 'Необходимо заполнить текстовое поле рядом с ответом';
    }

    protected function getFormAttributeNames()
    {
        return ['Values'];
    }

    protected function getDefinedViewPath()
    {
        return 'competence.views.form.single';
    }

    public function getAdminView()
    {
        return 'competence.views.form.admin.single';
    }

    public function processAdminPanel()
    {
        parent::processAdminPanel();

        $single = \Yii::app()->getRequest()->getParam('Single');
        /** @var \competence\models\form\attribute\RadioValue[] $values */
        $values = [];
        $maxSort = 0;
        foreach ($single as $key => $row)
        {
            if (empty($row['key']) && empty($row['title']))
                continue;

            $values[] = new \competence\models\form\attribute\RadioValue($row['key'], $row['title'], isset($row['isOther']), (int)$row['sort'], $row['description'], $row['suffix']);
            $maxSort = max((int)$row['sort'], $maxSort);
        }

        foreach ($values as $value)
        {
            if ($value->sort > 0)
                continue;
            $maxSort += 10;
            $value->sort = $maxSort;
        }
        usort($values, function($a, $b) {return $a->sort < $b->sort ? -1 : 1;});

        foreach ($values as $key => $value)
        {
            if (empty($value->key))
            {
                $this->question->addError('Title', 'Строка ' . ($key+1) . ': не задан ключ для варианта ответа');
            }
        }

        $this->question->setFormData(['Values' => $values]);
    }

    public function getInternalExportValueTitles()
    {
        $titles = [];
        foreach ($this->Values as $value) {
            $titles[] = $value->title;
        }
        $titles[] = 'Свое значение';
        return $titles;
    }

    public function getInternalExportData(Result $result)
    {
        $questionData = $result->getQuestionResult($this->question);
        $data = [];
        foreach ($this->Values as $value) {
            $data[] = !empty($questionData) && $questionData['value'] == $value->key ? 1 : 0;
        }
        $data[] = !empty($questionData) ? $questionData['other'] : '';
        return $data;
    }
}