<?php
/**
 * @var $this \event\widgets\Registration
 * @var $products \pay\models\Product[]
 * @var $account \pay\models\Account
 */
if (empty($products))
{
  return;
}
?>
<form method="post" action="<?=\Yii::app()->createUrl('/pay/cabinet/register', array('eventIdName' => $this->event->IdName));?>" class="registration">
  <h5 class="title"><?=Yii::t('app', 'Регистрация');?></h5>
  <table class="table table-condensed">
    <thead>
    <tr>
      <th><?=Yii::t('app', 'Тип билета');?></th>
      <th class="t-right col-width"><?=Yii::t('app', 'Цена');?></th>
      <th class="t-center col-width"><?=Yii::t('app', 'Кол-во');?></th>
      <th class="t-right col-width"><?=Yii::t('app', 'Сумма');?></th>
    </tr>
    </thead>
    <tbody>
    <?foreach ($products as $product):?>
      <?if (sizeof($product->Prices) > 1):?>
        <tr>
          <td style="padding-top: 25px;"><strong><?=$product->Title;?></strong></td>
          <td colspan="3"></td>
        </tr>
      <?endif;?>
      <?foreach ($product->Prices as $price):?>
        <?
        $curTime = date('Y-m-d H:i:s');
        $isMuted = $curTime < $price->StartTime || ($price->EndTime != null && $curTime > $price->EndTime);
        $mutedClass = $isMuted ? 'muted' : '';
        ?>
        <tr data-price="<?=$price->Price;?>">

          <?if (sizeof($product->Prices) > 1):?>
            <td class="<?=$mutedClass?>">
              <?
                $title = $price->Title;
                if (empty($title))
                {
                  if ($price->EndTime !== null)
                    $title = \Yii::t('app', 'При регистрации до').' '.\Yii::app()->dateFormatter->format('d MMMM', $price->EndTime);
                  else
                    $title = \Yii::t('app', 'При регистрации с').' '.\Yii::app()->dateFormatter->format('d MMMM', $price->StartTime).' '.\Yii::t('app', 'и на входе');
                }
              ?>
              <?=$title;?>
            </td>
          <?else:?>
            <td style="padding-top: 20px; padding-bottom: 20px;" class="<?=$mutedClass?>">
              <strong style="margin-bottom: 15px;"><?=$product->Title;?></strong>
            </td>
          <?endif;?>

          <td class="t-right <?=$mutedClass?>">
            <?if ($price->Price != 0):?>
            <span class="number"><?=$price->Price;?></span> <?=Yii::t('app', 'руб.');?>
            <?else:?>
              <?=Yii::t('app', 'бесплатно');?>
            <?endif;?>
          </td>
          <td class="t-center <?=$mutedClass?>">
            <select <?if($isMuted):?>disabled="disabled"<?endif;?> name="count[<?=$product->Id;?>]" class="input-mini form-element_select">
              <option value="0" selected>0</option>
              <option value="1">1</option>
              <option value="2">2</option>
              <option value="3">3</option>
              <option value="4">4</option>
              <option value="5">5</option>
              <option value="6">6</option>
              <option value="7">7</option>
              <option value="8">8</option>
              <option value="9">9</option>
            </select>
          </td>
          <td class="t-right <?=$mutedClass?>"><b class="number mediate-price">0</b> <?=Yii::t('app', 'руб.');?></td>
        </tr>
      <?endforeach;?>
    <?endforeach;?>
    <tr>
      <td colspan="4" class="t-right total">
        <span><?=Yii::t('app', 'Итого');?>:</span> <b id="total-price" class="number">0</b> <?=Yii::t('app', 'руб.');?>
      </td>
    </tr>
    </tbody>
  </table>
  <div class="clearfix">
    <img src="/img/pay/pay-methods.png" class="pull-left" alt="Поддерживаемые способы оплаты"/>
    <?if ($account->PayOnline):?>
    <a style="margin-top: -2px; display: inline-block;" href="http://money.yandex.ru" target="_blank"><img src="http://money.yandex.ru/img/yamoney_logo88x31.gif " alt="Я принимаю Яндекс.Деньги" title="Я принимаю Яндекс.Деньги" border="0" /></a>
    <?endif;?>
    <button type="submit" class="btn btn-info pull-right"><?=Yii::t('app', 'Зарегистрироваться');?></button>
  </div>
</form>