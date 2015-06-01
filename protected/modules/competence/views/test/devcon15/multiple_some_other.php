<?php
/**
 * @var $form \competence\models\form\Multiple
 */
$left = [];
$right = [];
if (count($form->Values) > 6)
{
  $half = count($form->Values) / 2;
  foreach ($form->Values as $value)
  {
    if (count($left) >= $half)
    {
      $right[] = $value;
    }
    else
    {
      $left[] = $value;
    }
  }
}

if (!function_exists('printCheckBoxSomeOther'))
{
  function printCheckBoxSomeOther(\competence\models\form\Multiple $form, \competence\models\form\attribute\CheckboxValue $value, $wide = true)
  {
    $attrs = [
      'value' => $value->key,
      'uncheckValue' => null,
      'data-group' => $form->getQuestion()->Code,
      'data-unchecker' => (int)$value->isUnchecker,
      'checked' => in_array($value->key, $form->value)
    ];
    if ($value->isOther)
    {
      $attrs['data-target'] = '#'.$form->getQuestion()->Code.'_'.$value->key;
    }
    ?>
    <li>
      <label class="checkbox">
        <?=CHtml::activeCheckBox($form, 'value[]', $attrs);?>
        <?=$value->title;?>

          <?if (!empty($value->description)):?>
              <div class="value-description">
                  <?=$value->description;?>
              </div>
          <?endif;?>
      </label>
      <?if ($value->isOther):?>
          <?if (empty($value->suffix)):?>
              &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<?=CHtml::activeTextField($form, ('other_' . $value->key), ['class' => $wide ? 'span4' : 'span3', 'data-group' => $form->getQuestion()->Code, 'id' => $form->getQuestion()->Code.'_'.$value->key]);?>
          <?else:?>
              <div style="margin-left: 18px;" class="input-append">
                  <?=CHtml::activeTextField($form, ('other_' . $value->key), ['class' => $wide ? 'span4' : 'span3', 'data-group' => $form->getQuestion()->Code, 'id' => $form->getQuestion()->Code.'_'.$value->key]);?>
                  <span class="add-on"><?=$value->suffix;?></span>
              </div>
          <?endif;?>
      <?endif;?>
    </li>
  <?
  }
}
?>

<?if (empty($left)):?>
  <ul class="unstyled">
    <?
    foreach ($form->Values as $value)
    {
        printCheckBoxSomeOther($form, $value);
    }
    ?>
  </ul>
<?else:?>
  <div class="row">
    <div class="span4">
      <ul class="unstyled">
        <?
        foreach ($left as $value)
        {
            printCheckBoxSomeOther($form, $value, false);
        }
        ?>
      </ul>
    </div>
    <div class="span4 offset1">
      <ul class="unstyled">
        <?
        foreach ($right as $value)
        {
            printCheckBoxSomeOther($form, $value, false);
        }
        ?>
      </ul>
    </div>
  </div>
<?endif;?>


