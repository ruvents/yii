<?php
use application\components\utility\Texts;

/**
 * @var $order \pay\models\Order
 * @var $billData array
 * @var $total int
 * @var $nds int
 * @var $withSign bool
 */
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.1 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<HTML lang=ru xml:lang="ru" xmlns="http://www.w3.org/1999/xhtml">
<HEAD>
  <TITLE>Счёт № <?=$order->Id;?></TITLE>
  <META content="text/html; charset=UTF-8" http-equiv=Content-Type>
</HEAD>
<BODY>
<DIV id=panel></DIV>
<DIV id=content>
<TABLE cellSpacing="0" cellPadding="5" width="720" border="0">
<TBODY>
<TR>
  <TD><b>ЗАО «Эй Пи»</b><BR>607190, г. Саров, Нижегородская обл., ул. Курчатова, д. 3, ком. 303<BR>тел./факс: +7 (495) 689-92-01, 689-30-14, 231-49-95</TD>
  <td align="right"></td>
</tr>
<TR>
  <TD colspan="2">
    <P style="TEXT-ALIGN: center"><B>Образец заполнения платежного поручения</B></P>
    <TABLE style="BORDER-BOTTOM: 1px solid; BORDER-LEFT: 1px solid; WIDTH: 100%; BORDER-TOP: 1px solid; BORDER-RIGHT: 1px solid" cellSpacing="0" cellPadding="3">
      <TBODY>
      <TR>
        <TD style="BORDER-BOTTOM: 1px solid; BORDER-RIGHT: 1px solid">ИНН 5254018931</TD>

        <TD style="BORDER-BOTTOM: 1px solid; BORDER-RIGHT: 1px solid">КПП 525401001</TD>
        <TD style="BORDER-BOTTOM: 1px solid">&nbsp;</TD>
        <TD style="BORDER-BOTTOM: 1px solid">&nbsp;</TD>
      </TR>
      <TR>
        <TD style="BORDER-BOTTOM: 1px solid; BORDER-RIGHT: 1px solid"	colSpan=2>Получатель<BR>ЗАО «Эй Пи»</TD>
        <TD	style="BORDER-BOTTOM: 1px solid; BORDER-RIGHT: 1px solid"><BR>Сч.	№</TD>

        <TD style="BORDER-BOTTOM: 1px solid"><BR>40702810311330010040</TD></TR>
      <TR>
        <TD style="BORDER-RIGHT: 1px solid" colSpan=2>Банк получателя<BR>Московский филиал ОАО КБ «Восточный» г. Москва</TD>
        <TD style="BORDER-RIGHT: 1px solid">БИК<BR>Сч. №</TD>
        <TD>044585682<BR>30101810500000000682</TD>

      </TR>
      </TBODY>
    </TABLE>
  </TD>
</TR>
<TR>
  <TD style="TEXT-ALIGN: center" colspan="2">
    <DIV style="MARGIN-TOP: 20px; FONT-SIZE: 24px"><B>CЧЕТ № <?=$order->Id;?> от <?=date('d.m.Y', strtotime($order->CreationTime));?></B></DIV>

    (Счет действителен в течение 5-и банковских дней)
  </TD>
</TR>
<TR>
  <TD colspan="2">Заказчик: <?=$order->OrderJuridical->Name;?>,
    ИНН / КПП: <?=$order->OrderJuridical->INN;?>/<?=$order->OrderJuridical->KPP;?><BR>
    Плательщик: <?=$order->OrderJuridical->Name;?><BR>
    Адрес: <?=$order->OrderJuridical->Address;?>		</TD>
</TR>

<TR>
  <TD colspan="2">
    <TABLE style="BORDER-BOTTOM: 1px solid; BORDER-LEFT: 1px solid; WIDTH: 100%; BORDER-TOP: 1px solid; BORDER-RIGHT: 1px solid" cellSpacing=0 cellPadding=3>
      <TBODY>
      <TR style="TEXT-ALIGN: center">
        <TD style="BORDER-BOTTOM: 1px solid; BORDER-RIGHT: 1px solid">№</TD>
        <TD	style="BORDER-BOTTOM: 1px solid; BORDER-RIGHT: 1px solid">Наименование<BR>товара (услуги)</TD>

        <TD style="BORDER-BOTTOM: 1px solid; BORDER-RIGHT: 1px solid">Единица<BR>измерения</TD>
        <TD style="BORDER-BOTTOM: 1px solid; BORDER-RIGHT: 1px solid">Кол-во</TD>
        <TD style="BORDER-BOTTOM: 1px solid; BORDER-RIGHT: 1px solid">Цена,<br />руб.</TD>
        <TD style="BORDER-BOTTOM: 1px solid">Сумма,<BR>руб.</TD>
      </TR>

      <?
      $i = 1;
      foreach ($billData as $data):
        ?>
        <TR>
          <TD style="BORDER-BOTTOM: 1px solid; BORDER-RIGHT: 1px solid"><?=$i;?></TD>
          <TD style="BORDER-BOTTOM: 1px solid; BORDER-RIGHT: 1px solid"><?=$data['Title'];?></TD>
          <TD style="BORDER-BOTTOM: 1px solid; TEXT-ALIGN: center; BORDER-RIGHT: 1px solid"><?=$data['Unit'];?></TD>
          <TD style="BORDER-BOTTOM: 1px solid; TEXT-ALIGN: center; BORDER-RIGHT: 1px solid"><?=$data['Count'];?></TD>
          <TD style="BORDER-BOTTOM: 1px solid; TEXT-ALIGN: center; BORDER-RIGHT: 1px solid" nowrap="nowrap"><?=number_format(round($data['DiscountPrice'] / 1.18, 2, PHP_ROUND_HALF_UP), 2, ',', ' ');?></TD>

          <TD style="BORDER-BOTTOM: 1px solid; TEXT-ALIGN: right" nowrap="nowrap"><?=number_format(round($data['DiscountPrice'] * $data['Count'] / 1.18, 2, PHP_ROUND_HALF_UP), 2, ',', ' ');?></TD>
        </TR>
        <?
        $i++;
      endforeach;?>

      <TR>
        <TD style="TEXT-ALIGN: right; FONT-WEIGHT: bold; BORDER-RIGHT: 1px solid" colSpan="4">Итого:<BR>Итого НДС:<BR>Всего к оплате (c учетом НДС):</TD>
        <TD style="TEXT-ALIGN: right; FONT-WEIGHT: bold" colspan="2"><?=number_format($total - $nds, 2, ',', ' ');?><BR><?=number_format($nds, 2, ',', ' ');?><BR><?=number_format($total, 2, ',', ' ');?></TD>

      </TR>
      </TBODY>
    </TABLE>
  </TD>
</TR>
<TR>
  <TD colspan="2">
    Всего на сумму <?=number_format($total, 0, ',', ' ');?> руб. 00 коп.
    <DIV><span><?=Texts::mb_ucfirst(mb_strtolower(Texts::NumberToText($total, true)));?></span> рублей 00 копеек</DIV><BR><BR>

  </TD>
</TR>
<?/*
<TR>
  <TD style="FONT-SIZE: 10px" colspan="2">
    <H2>Публичная оферта на оказание услуг</H2>
    <?=$this->renderPartial('bills/offer/partner');?>
  </TD>
</TR>
 */?>
<?if ($withSign):?>
  <TR>

    <TD colspan="2">
      <IMG	style="BORDER-BOTTOM: medium none; BORDER-LEFT: medium none; BORDER-TOP: medium none; BORDER-RIGHT: medium none"
            src="/img/pay/bill/ap/sign.jpg"
          />
    </TD>
  </TR>
<?else:?>
  <TR>
    <TD colspan="2">
      <table>
        <tr>
          <td>Генеральный директор</td>
          <td>__________________________</td>
          <td>(Аникина М.П.)</td>
        </tr>
        <tr>
          <td>&nbsp;</td>
          <td></td>
          <td></td>
        </tr>
        <tr>
          <td>Финансовый директор</td>
          <td>__________________________</td>
          <td>(Банделюк Р.В.)</td>
        </tr>
      </table>
      <br/><br/><br/><br/><br/>
    </TD>
  </TR>
<?endif;?>
</TBODY>
</TABLE></DIV></BODY></HTML>
