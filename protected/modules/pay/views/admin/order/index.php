<?php
/**
 * @var $form \partner\models\forms\OrderSearch
 * @var $orders \pay\models\Order[]
 * @var $paginator \application\components\utility\Paginator
 */
?>
<div class="row">

  <div class="span12">
    <?=CHtml::beginForm();?>
    <div class="row">
      <div class="span4">
        <?=CHtml::activeLabel($form, 'Order');?>
        <?=CHtml::activeTextField($form, 'Order');?>
      </div>
      <div class="span4">
        <?=CHtml::activeLabel($form, 'Paid');?>
        <?=CHtml::activeDropDownList($form, 'Paid', $form->getListValues());?>
      </div>
      <div class="span4">
        <?=CHtml::activeLabel($form, 'Deleted');?>
        <?=CHtml::activeDropDownList($form, 'Deleted', $form->getListValues());?>
      </div>
    </div>

    <div class="row">
      <div class="span4">
        <?=CHtml::activeLabel($form, 'Company');?>
        <?=CHtml::activeTextField($form, 'Company');?>
      </div>
      <div class="span4">
        <?=CHtml::activeLabel($form, 'INN');?>
        <?=CHtml::activeTextField($form, 'INN');?>
      </div>
    </div>

    <div class="row">
      <div class="span4">
        <?=CHtml::activeLabel($form, 'Payer');?>
        <?=CHtml::activeTextField($form, 'Payer', array('placeholder' => 'RUNET-ID'));?>
      </div>
      <div class="offset4 span4">
        <button class="btn btn-large" type="submit"><i class="icon-search"></i> Искать</button>
      </div>
    </div>
    <?=CHtml::endForm();?>
  </div>

  <div class="span12">
    <?if ($paginator->getCount() > 0):?>
      <table class="table table-striped">
        <thead>
        <tr>
          <th>Номер счета/заказа</th>
          <th class="span4">Краткие данные</th>
          <th class="span3">Выставил</th>
          <th>Дата</th>
          <th>Сумма</th>
          <th class="span2">Управление</th>
        </tr>
        </thead>

        <tbody>
        <?foreach ($orders as $order):?>
          <tr>
            <td><h3><?=$order->Id;?></h3></td>
            <td>
              <?if ($order->Juridical):?>
              <strong><?=$order->OrderJuridical->Name;?></strong><br>
              ИНН/КПП:&nbsp;<?=$order->OrderJuridical->INN;?>&nbsp;/&nbsp;<?=$order->OrderJuridical->KPP;?>
              <?else:?>
                  <p class="text-warning"><strong>Через платежную систему</strong></p>
              <?endif;?>
            </td>
            <td>
              <?php echo $order->Payer->RunetId;?>, <strong><?php echo $order->Payer->getFullName();?></strong>
              <p>
                <em><?=$order->Payer->Email;?></em>
              </p>
              <?foreach ($order->Payer->LinkPhones as $link):?>
                <?if ($link->Phone == null) { continue; }?>
                  <p><em><?=urldecode($link->Phone);?></em></p>
              <?endforeach;?>
            </td>
            <td><?=Yii::app()->locale->getDateFormatter()->format('d MMMM y', strtotime($order->CreationTime));?><br>
              <?if ($order->Paid):?>
                <span class="label label-success">ОПЛАЧЕН</span>
              <?else:?>
                <span class="label label-important">НЕ ОПЛАЧЕН</span>
              <?endif;?>
            </td>
            <td><?=$order->getPrice();?> руб.</td>
            <td>
              <form action="<?=\Yii::app()->createUrl('/pay/admin/order/view', array('orderId' => $order->Id));?>" method="post">
              <a class="btn btn-info" href="<?=\Yii::app()->createUrl('/pay/admin/order/view', array('orderId' => $order->Id));?>"><i class="icon-list icon-white"></i></a>

              <?if (!$order->Paid && $order->Juridical):?>
                <button class="btn btn-success" type="submit" onclick="return confirm('Вы уверены, что хотите отметить данный счет оплаченным?');" name="SetPaid"><i class="icon-ok icon-white"></i></button>
              <?endif;?>

                <?if ($order->Juridical):?>
                <a class="btn" target="_blank" href="<?=$order->getUrl(true);?>"><i class="icon-print"></i></a>
                <?endif;?>
              </form>
              
            </td>
          </tr>
        <?endforeach;?>
        </tbody>

      </table>


      <?$this->widget('\application\widgets\Paginator', array('paginator' => $paginator));?>

    <?else:?>
      <div class="alert">
        <strong>Внимание!</strong> Нет ни одного счета с заданными параметрами.
      </div>
    <?endif;?>
  </div>

  <div class="span12 indent-bottom3"></div>
</div>