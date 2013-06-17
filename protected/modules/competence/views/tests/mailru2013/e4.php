<?php
/**
 * @var $question \competence\models\tests\mailru2013\E4
 */
$fullData = $question->getFullData();

$base = new \competence\models\tests\mailru2013\E1($question->getTest());
$baseData = $fullData[get_class($base)];

$base1_1 = new \competence\models\tests\mailru2013\E1_1($question->getTest());
$baseData1_1 = $fullData[get_class($base1_1)];
?>

<h3>Отметьте, пожалуйста, насколько Вы доверяете информации, представленной в каждом из перечисленных источников?</h3>

<?php $this->widget('competence\components\ErrorsWidget', ['question' => $question]);?>

<ul class="unstyled">
  <?foreach ($base->getOptions() as $key => $value):
    if (!in_array($key, $baseData1_1['value']))
    {
      continue;
    }
  ?>
  <li>
    <?if ($key != 10):?>
    <h4><?=$value;?></h4>
    <?else:?>
    <h4>Другое<br>(<em>добавлен свой вариант</em>: <strong><?=$baseData['other'];?></strong>)</h4>
    <?endif;?>
    <?=CHtml::activeDropDownList($question, 'value['.$key.']', $question->values, ['class' => 'span4']);?>
  </li>
  <?endforeach;?>
</ul>
