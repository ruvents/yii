<?php
/**
 * @var $orderItems \pay\models\OrderItem[]
 */
?>
<div class="row">
  <div class="span12">
    <h2>Последние покупки номеров</h2>
  </div>
</div>


<div class="row">
  <div class="span12">
    <table class="table table-bordered">
      <thead>
      <tr>
        <th>Время оплаты</th>
        <th>Информация о номере</th>
        <th>ФИО</th>
        <th>Даты бронирования</th>
      </tr>
      </thead>
      <tbody>
      <?foreach($orderItems as $item):?>
        <tr>
          <td><?=date('d-m-Y H:i:s', strtotime($item->PaidTime));?></td>
          <td>
            <?
            /** @var $manager \pay\components\managers\RoomProductManager */
            $manager = $item->Product->getManager();
            ?>
            <strong>Номер: <?=$manager->Number;?></strong> <span class="muted">(Id: <?=$item->Product->Id;?>)</span><br>
            <?=$manager->Housing;?>, <?=$manager->Category;?><br>
            Всего мест: <?=$manager->PlaceTotal;?> (основных - <?=$manager->PlaceBasic;?>, доп. - <?=$manager->PlaceMore;?>)<br>
            <em><?=$manager->DescriptionBasic;?>, <?=$manager->DescriptionMore;?></em><br>

          </td>
          <td><?=$item->Payer->getFullName();?>, <em><?=$item->Payer->RunetId;?></em></td>
          <td><?=date('d-m-Y', strtotime($item->getItemAttribute('DateIn')));?> - <?=date('d-m-Y',strtotime($item->getItemAttribute('DateOut')));?></td>
        </tr>
      <?endforeach;?>
      </tbody>
    </table>
  </div>
</div>

