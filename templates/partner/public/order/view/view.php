<?php
/** @var $order Order */
$order = $this->Order;
?>
<ul class="nav nav-pills">
  <li <?if ($order->OrderJuridical->Paid == 0):?>class="active"<?endif;?>>
    <a href="<?=RouteRegistry::GetUrl('partner', 'order', 'index');?>">Не активированные счета</a>
  </li>
  <li <?if ($order->OrderJuridical->Paid != 0):?>class="active"<?endif;?>>
    <a href="<?=RouteRegistry::GetUrl('partner', 'order', 'index');?>?filter=active">Активированные счета</a>
  </li>
  <li>
    <a href="<?=RouteRegistry::GetUrl('partner', 'order', 'search');?>">Поиск</a>
  </li>
  <li>
    <a href="<?=RouteRegistry::GetUrl('partner', 'order', 'create');?>">Выставить счет</a>
  </li>
</ul>

<div class="row">
  <div class="span12 indent-bottom3">
    <h2>Счет №<?=$order->OrderId;?></h2>

    <p>
      <?if ($order->OrderJuridical->Paid != 0):?>
      <span class="label label-success">ОПЛАЧЕН</span>
      <?else:?>
        <?if ($order->OrderJuridical->Deleted == 0):?>
        <span class="label label-warning">НЕ ОПЛАЧЕН</span>
        <?else:?>
        <span class="label label-important">УДАЛЕН</span>
        <?endif;?>
      <?endif;?>
    </p>
  </div>

  <div class="span12 indent-bottom3">
    <?if (!empty($this->Error)):?>
    <div class="alert alert-error">
      <p><strong>Возникла ошибка!</strong> <?=$this->Error;?></p>

      <p>Отправьте данное сообщение на email: <a href="mailto:users@rocid.ru">users@rocid.ru</a></p>
    </div>
    <?elseif (! empty($this->Result)):?>
    <div class="alert alert-success">
      <p><strong>Выполнено!</strong> <br> <?=$this->Result;?></p>
    </div>
    <?endif;?>
  </div>


  <div class="span6 indent-bottom3">
    <h3>Данные заказчика</h3>

    <p></p>

    <p><strong>Название компании:</strong> <?=$order->OrderJuridical->Name;?></p>

    <p><strong>Адрес:</strong> <?=$order->OrderJuridical->Address;?></p>

    <p><strong>ИНН/КПП:</strong> <?=$order->OrderJuridical->INN;?> / <?=$order->OrderJuridical->KPP;?></p>

    <p><strong>Телефон:</strong> <?=$order->OrderJuridical->Phone;?></p>
  </div>

  <div class="span6 indent-bottom3">
    <h3>Данные пользователя</h3>

    <p></p>

    <p><strong>rocID:</strong> <a target="_blank" href="/<?=$order->Payer->RocId;?>/"><?=$order->Payer->RocId;?></a></p>

    <p><strong>ФИО:</strong> <?=$order->Payer->GetFullName();?></p>

    <?$employment = $order->Payer->EmploymentPrimary();?>
    <p><strong>Компания:</strong> <?=$employment != null ? $employment->Company->Name : 'не указана';?></p>

    <p><strong>Email:</strong> <?=!empty($order->Payer->Emails) ? $order->Payer->Emails[0]->Email : $order->Payer->Email;?></p>

    <p><strong>Телефон:</strong> <?=!empty($order->Payer->Phones) ? urldecode($order->Payer->Phones[0]->Phone) : 'не указан';?></p>
  </div>

  <div class="span12 indent-bottom3">
    <h3>Состав счета</h3>

    <table class="table table-striped">
      <thead>
      <tr>
        <th>Наименование</th>
        <th>ФИО плательщика</th>
        <th>ФИО получателя</th>
        <th>Стоимость</th>
      </tr>
      </thead>
      <tbody>
      <?foreach ($order->Items as $item):
      ?>
      <tr>
        <td><?=$item->Product->ProductManager()->GetTitle($item);?></td>
        <td><?=$item->Payer->GetFullName();?> (<?=$item->Payer->RocId;?>)</td>
        <td><?=$item->Owner->GetFullName();?> (<?=$item->Owner->RocId;?>)</td>
        <td><?=$item->PriceDiscount();;?> руб.</td>
      </tr>
      <?endforeach;?>
      </tbody>
    </table>
  </div>

  <div class="span12 indent-bottom3">
    <h3>Сумма счета: <?=$order->Price();?> руб.</h3>
  </div>

  <div class="span12">
    <form action="" method="post">
      <fieldset>
        <div class="clearfix">
          <button type="submit" class="btn btn-success"
            <?if ($order->OrderJuridical->Paid != 0):?>
                  onclick="return confirm('Счет уже отмечен как оплаченный. Повторить?');"
            <?else:?>
                  onclick="return confirm('Вы уверены, что хотите отметить данный счет оплаченным?');"
            <?endif;?>
                  name="SetPaid"><i class="icon-ok icon-white"></i> Отметить как оплаченный</button>

          <?if ($order->OrderJuridical->Deleted == 0):?>
          <button class="btn btn-danger" type="submit" name="SetDeleted" onclick="return confirm('Вы уверены, что хотите удалить счет?');"><i class="icon-remove icon-white"></i> Удалить</button>
          <?endif;?>

          <a href="http://pay.<?=RouteRegistry::GetUrl('main', 'juridical', 'order', array('orderId' => $order->OrderId, 'hash' => $order->OrderJuridical->GetHash()), RouteRegistry::SectionDirPublic, '');?>" class="btn" target="_blank"><i class="icon-print"></i> Счет с печатью</a>
          <a href="http://pay.<?=RouteRegistry::GetUrl('main', 'juridical', 'order', array('orderId' => $order->OrderId, 'hash' => $order->OrderJuridical->GetHash(), 'clear' => 'clear'), RouteRegistry::SectionDirPublic, '');?>" class="btn" target="_blank"><i class="icon-print"></i> Счет без печати</a>
        </div>
      </fieldset>
    </form>
  </div>
</div>