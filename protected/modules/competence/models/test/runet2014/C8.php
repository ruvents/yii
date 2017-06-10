<?php
namespace competence\models\test\runet2014;

use competence\models\Question;
use competence\models\Result;

class C8 extends \competence\models\form\Base
{
    use RouteMarket;

    protected $baseCode;

    protected $nextCodeToCompany;

    protected $nextCodeToComment;

    public $subMarkets = [];

    protected function getDefinedViewPath()
    {
        return 'competence.views.test.runet2014.c8';
    }

    public function getNext()
    {
        $baseQuestion = Question::model()->byTestId($this->getQuestion()->TestId)->byCode($this->baseCode)->find();
        $baseQuestion->setTest($this->getQuestion()->Test);
        $result = $baseQuestion->getResult();
        if (!empty($result) && $result['value'] == '1') {
            return Question::model()->byTestId($this->getQuestion()->TestId)->byCode($this->nextCodeToCompany)->find();
        } else {
            return Question::model()->byTestId($this->getQuestion()->TestId)->byCode($this->nextCodeToComment)->find();
        }
    }

    public function rules()
    {
        return [
            ['value', 'validateValue']
        ];
    }

    public function validateValue($attribute, $params)
    {
        $valid = false;
        if (is_array($this->$attribute)) {
            $sum = 0;
            foreach ($this->$attribute as $val) {
                $sum += $val;
            }

            if ($sum == 100)
                $valid = true;
        }

        if (!$valid)
            $this->addError($attribute, 'Сумма оборота всех компаний должна быть равной 100%');
    }

    public function getInternalExportValueTitles()
    {
        return array_values($this->subMarkets);
    }

    public function getInternalExportData(Result $result)
    {
        $questionData = $result->getQuestionResult($this->question);
        $data = [];
        foreach ($this->subMarkets as $key => $market) {
            $data[] = isset($questionData['value'][$key]) ? $questionData['value'][$key] : '';
        }
        return $data;
    }

}