<?php
/**
 * @var $question \competence\models\tests\mailru2013\S3_1
 */
?>
<p class="personal-info">В заключение несколько вопросов о Вас и о Вашей семье. Эта информация будет использоваться в обобщённом виде после статистической обработки.</p>


<h3>Укажите, пожалуйста, сферу Вашей деятельности?</h3>

<?php $this->widget('competence\components\ErrorsWidget', array('question' => $question));?>

<ul class="unstyled">
  <?foreach ($question->values as $key => $value):?>
  <li>
    <label class="radio">
      <?=CHtml::activeRadioButton($question, 'value', array('value' => $key, 'uncheckValue' => null, 'data-target' => $key==98 ? '[name*="other"]' : null, 'data-group' => 's3_1'));?>
      <?=$value;?>
    </label>
    <?if ($key == 98):?>
    &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<?=CHtml::activeTextField($question, 'other', array('class' => 'span4', 'data-other' => 'input', 'data-group' => 's3_1'));?>
    <?endif;?>
  </li>
  <?endforeach;?>
</ul>
