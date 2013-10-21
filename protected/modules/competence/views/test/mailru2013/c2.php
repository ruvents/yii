<?php
/**
 * @var $question \competence\models\tests\mailru2013\C2
 */
?>
<p class="personal-info">В заключение несколько вопросов о Вас и о Вашей семье. Эта информация будет использоваться в обобщённом виде после статистической обработки.</p>


<h3>Укажите, пожалуйста, Ваш пол.</h3>

<?php $this->widget('competence\components\ErrorsWidget', array('question' => $question));?>

<ul class="unstyled">
  <?foreach ($question->values as $key => $value):?>
  <li>
    <label class="radio">
      <?=CHtml::activeRadioButton($question, 'value', array('value' => $key, 'uncheckValue' => null));?>
      <?=$value;?>
    </label>
  </li>
  <?endforeach;?>
</ul>
