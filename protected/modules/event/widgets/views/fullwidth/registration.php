<?php
/**
 * @var \event\widgets\Registration $this
 * @var \pay\models\Product[] $products
 * @var \pay\models\Account $account
 */
?>

  <?=CHtml::beginForm(\Yii::app()->createUrl('/pay/cabinet/register', array('eventIdName' => $this->event->IdName)), 'POST', array('class' => 'event-registration registration'));?>
  <?=\CHtml::hiddenField(\Yii::app()->request->csrfTokenName, \Yii::app()->request->getCsrfToken()); ?>
  <header>
    <h3 class="title"><?=\Yii::t('app', 'Регистрация');?></h3>
    <?if ($participant !== null && $participant->RoleId != 24):?>
      <p class="text-center"><?=\Yii::t('app', 'Ваш статус');?>: <span class="label label-success"><?=$participant->Role->Title;?></span> <br/><a href="<?=$participant->getTicketUrl();?>" target="_blank"><small><?=\Yii::t('app', 'скачайте путевой лист');?></small></a></p>
      <p><?=\Yii::app()->getUser()->getCurrentUser()->getShortName();?>, вы уже зарегистрированы со статусом <strong>«<?=$participant->Role->Title;?>»</strong> на данное мероприятие. Если вы хотите зарегистрировать коллег, воспользуйтесь формой ниже.</p>
      <hr/>
      <?if (isset($this->RegistrationAfterInfo)):?>
        <?=$this->RegistrationAfterInfo;?>
      <?endif;?>
    <?else:?>
      <?if (isset($this->RegistrationBeforeInfo)):?>
        <?=$this->RegistrationBeforeInfo;?>
      <?endif;?>
    <?endif;?>
  </header>

  <?foreach ($products as $product):?>
    <article>
      <h4 class="article-title"><?=$product->Title;?></h4>
      <p><?=$product->Description;?></p>
    </article>

    <table class="table table-condensed">
      <thead>
      <tr>
        <th></th>
        <th class="t-right"><?=\Yii::t('app', 'Цена');?></th>
        <th class="t-center"><?=\Yii::t('app', 'Кол-во');?></th>
        <th class="t-right"><?=\Yii::t('app', 'Сумма');?></th>
      </tr>
      </thead>
      <tbody>
      <?$dateFormatter = \Yii::app()->dateFormatter;?>
      <?foreach ($product->Prices as $key => $price):
        $curTime = date('Y-m-d H:i:s');
        $isMuted = $curTime < $price->StartTime || ($price->EndTime != null && $curTime > $price->EndTime);
        ?>
        <tr data-price="<?=$price->Price;?>">

          <?if (!$isMuted):?><td><strong><?else:?><td class="muted"><?endif;?>

            <?if ($key == 0 && $price->EndTime != null):?>
              <?=\Yii::t('app', 'При регистрации до');?> <?=$dateFormatter->format('d MMMM', $price->EndTime);?>
            <?elseif ($key != 0 && $price->EndTime != null):?>
              <?=\Yii::t('app', 'При регистрации c');?> <?=$dateFormatter->format('d MMMM', $price->StartTime);?> <?=\Yii::t('app', 'по');?> <?=$dateFormatter->format('d MMMM', $price->EndTime);?>
            <?else:?>
              <?=\Yii::t('app', 'При регистрации с');?> <?=$dateFormatter->format('d MMMM', $price->StartTime);?> <?=\Yii::t('app', 'и на входе');?>
            <?endif;?>

            <?if (!$isMuted):?></strong><?endif;?></td>
          <td class="t-right price <?=$isMuted?'muted':'';?>"><strong><?=$price->Price;?></strong> <?=\Yii::t('app', 'руб.');?></td>
          <td class="t-center">
            <?
            $inpParams = array(
                'class' => 'input-mini'
            );
            if ($isMuted)
            {
              $inpParams['disabled'] = 'disabled';
            }
            echo CHtml::dropDownList('count['.$product->Id.']', 0,array(0,1,2,3,4,5,6,7,8,9,10), $inpParams);?>
          </td>
          <td class="t-right totalPrice <?=$isMuted?'muted':'';?>"><strong class="mediate-price">0</strong> <?=\Yii::t('app', 'руб.');?></td>
        </tr>
      <?endforeach;?>
      </tbody>
    </table>
  <?endforeach;?>

  <div class="t-right total">
    <span><?=\Yii::t('app', 'Итого');?>: </span><strong id="total-price">0</strong> <?=\Yii::t('app', 'руб.');?>
  </div>

  <div class="t-center">
    <button class="btn btn-large btn-success" type="submit"><?=\Yii::t('app', 'Зарегистрироваться');?></button>
  </div>
  <?php echo CHtml::endForm();?>
