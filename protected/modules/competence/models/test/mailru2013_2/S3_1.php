<?php
namespace competence\models\test\mailru2013_2;

class S3_1 extends \competence\models\form\Single {
  
  public function getPrev()
  {
    $s5 = $this->getQuestionByCode('S5');
    if (!in_array($s5->getResult()['value'], [1, 2, 3]))
    {
      return $this->getQuestionByCode('S6');
    }
    return parent::getPrev();
  }
}
