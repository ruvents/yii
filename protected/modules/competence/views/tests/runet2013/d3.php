<?php
/**
 * @var $question \competence\models\tests\runet2013\D3
 */
?>
<h3>Перед вами список факторов, которые могут оказывать влияние на развитие российского сегмента <strong><?=$question->getSelectSegmentTitle();?></strong> в течение ближайших пяти лет (до 2018 г.). оцените, насколько существенным будет влияние эфтих факторов в указанный период по шкале от 0 (совсем не существенное) до 10 (очень существенное).</h3>
<?php $this->widget('competence\components\ErrorsWidget', array('question' => $question));?>
<table class="table">
  <thead>
    <th>Фактор</th>
    <th>Оценка</th>
  </thead>
  <tbody>
    <?foreach ($question->getFactors() as $factor):?>
    <tr>
      <td style="width: 80%;"><?=$factor;?></td>
      <td>
        <?=\CHtml::activeDropDownList($question, 'value['.$factor.']', [0,1,2,3,4,5,6,7,8,9,10], ['class' => 'input-mini']);?>
      </td>
    </tr>
    <?endforeach;?>
  </tbody>
</table>
