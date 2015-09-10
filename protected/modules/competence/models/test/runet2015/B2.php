<?php
namespace competence\models\test\runet2015;

use competence\models\Question;
use \competence\models\form\Multiple;

class B2 extends Multiple
{
    protected $codes = [];

    public function getNext()
    {
        foreach ($this->codes as $value => $code) {
            if (in_array($value, $this->value)) {
                return Question::model()->byTestId($this->getQuestion()->TestId)->byCode($code)->find();
            }
        }
        return parent::getNext();
    }
} 