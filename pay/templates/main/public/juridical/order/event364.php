<?
/** @var $orderJuridical OrderJuridical */
$orderJuridical = $this->OrderJuridical;
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.1 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<HTML lang=ru xml:lang="ru" xmlns="http://www.w3.org/1999/xhtml">
<HEAD>
  <TITLE>Счёт № <?=$this->OrderId;?></TITLE>
  <META content="text/html; charset=UTF-8" http-equiv=Content-Type>
</HEAD>
<BODY>
<DIV id=panel></DIV>
<DIV id=content>
  <TABLE cellSpacing="0" cellPadding="5" width="720" border="0">
    <TBODY>
    <TR>
      <TD><b>ООО "Вебпрактик"</b><BR>Адрес: 344022, Ростовская обл, Ростов-на-Дону г, Седова ул, дом № 16, кв.19<BR></TD>
      <td align="right"><img src="/images/bill/rocid.png" width="129" height="48"></td>

    </tr>
    <TR>
      <TD colspan="2">
        <P style="TEXT-ALIGN: center"><B>Образец заполнения платежного поручения</B></P>
        <TABLE style="BORDER-BOTTOM: 1px solid; BORDER-LEFT: 1px solid; WIDTH: 100%; BORDER-TOP: 1px solid; BORDER-RIGHT: 1px solid" cellSpacing="0" cellPadding="3">
          <TBODY>
          <TR>
            <TD style="BORDER-BOTTOM: 1px solid; BORDER-RIGHT: 1px solid">ИНН 6163109767</TD>

            <TD style="BORDER-BOTTOM: 1px solid; BORDER-RIGHT: 1px solid">КПП 616301001</TD>
            <TD style="BORDER-BOTTOM: 1px solid">&nbsp;</TD>
            <TD style="BORDER-BOTTOM: 1px solid">&nbsp;</TD>
          </TR>
          <TR>
            <TD style="BORDER-BOTTOM: 1px solid; BORDER-RIGHT: 1px solid"	colSpan=2>Получатель<BR>ООО "Вебпрактик"</TD>
            <TD	style="BORDER-BOTTOM: 1px solid; BORDER-RIGHT: 1px solid"><BR>Сч.	№</TD>

            <TD style="BORDER-BOTTOM: 1px solid"><BR>40702810201000004154</TD></TR>
          <TR>
            <TD style="BORDER-RIGHT: 1px solid" colSpan=2>Банк получателя<BR>Южный филиал ОАО "Промсвязьбанк" Волгоград</TD>
            <TD style="BORDER-RIGHT: 1px solid">БИК<BR>Сч. №</TD>
            <TD>41806715<BR>30101810100000000715</TD>

          </TR>
          </TBODY>
        </TABLE>
      </TD>
    </TR>
    <TR>
      <TD style="TEXT-ALIGN: center" colspan="2">
        <DIV style="MARGIN-TOP: 20px; FONT-SIZE: 24px"><B>CЧЕТ № <?=$this->OrderId;?> от <?=date('d.m.Y', strtotime($this->CreationTime));?></B></DIV>

        (Счет действителен в течение 5-и банковских дней)
      </TD>
    </TR>
    <TR>
      <TD colspan="2">Заказчик: <?=$orderJuridical->Name;?>,
        ИНН / КПП: <?=$orderJuridical->INN;?>/<?=$orderJuridical->KPP;?><BR>
        Плательщик: <?=$orderJuridical->Name;?><BR>
        Адрес: <?=$orderJuridical->Address;?>		</TD>
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
          foreach ($this->BillOrders as $billOrder):
            ?>
          <TR>
            <TD style="BORDER-BOTTOM: 1px solid; BORDER-RIGHT: 1px solid"><?=$i;?></TD>
            <TD style="BORDER-BOTTOM: 1px solid; BORDER-RIGHT: 1px solid"><?=$billOrder['Title'];?></TD>
            <TD style="BORDER-BOTTOM: 1px solid; TEXT-ALIGN: center; BORDER-RIGHT: 1px solid"><?=$billOrder['Unit'];?></TD>
            <TD style="BORDER-BOTTOM: 1px solid; TEXT-ALIGN: center; BORDER-RIGHT: 1px solid"><?=$billOrder['Count'];?></TD>
            <TD style="BORDER-BOTTOM: 1px solid; TEXT-ALIGN: center; BORDER-RIGHT: 1px solid" nowrap="nowrap"><?=number_format(round($billOrder['DiscountPrice'] / 1.18, 2, PHP_ROUND_HALF_UP), 2, ',', ' ');?></TD>

            <TD style="BORDER-BOTTOM: 1px solid; TEXT-ALIGN: right" nowrap="nowrap"><?=number_format(round($billOrder['DiscountPrice'] * $billOrder['Count'] / 1.18, 2, PHP_ROUND_HALF_UP), 2, ',', ' ');?></TD>
          </TR>
          <?
          $i++;
          endforeach;?>

          <TR>
            <TD style="TEXT-ALIGN: right; FONT-WEIGHT: bold; BORDER-RIGHT: 1px solid" colSpan="4">Итого:<BR>Итого НДС:<BR>Всего к оплате (c учетом НДС):</TD>
            <TD style="TEXT-ALIGN: right; FONT-WEIGHT: bold" colspan="2"><?=number_format($this->Total - $this->NDS, 2, ',', ' ');?><BR><?=number_format($this->NDS, 2, ',', ' ');?><BR><?=number_format($this->Total, 2, ',', ' ');?></TD>

          </TR>
          </TBODY>
        </TABLE>
      </TD>
    </TR>
    <TR>
      <TD colspan="2">
        Всего на сумму <?=number_format($this->Total, 0, ',', ' ');?> руб. 00 коп.
        <DIV><span><?=Texts::mb_ucfirst(mb_strtolower(Texts::NumberToText($this->Total, true)));?></span> рублей 00 копеек</DIV><BR><BR>

      </TD>
    </TR>

    <TR>

      <TD colspan="2">
        <?if ($this->WithSign):?>
        <IMG	style="BORDER-BOTTOM: medium none; BORDER-LEFT: medium none; BORDER-TOP: medium none; BORDER-RIGHT: medium none"
              src="/images/bill/event364/bill_withsign.png"
            />
        <?else:?>
        <IMG	style="BORDER-BOTTOM: medium none; BORDER-LEFT: medium none; BORDER-TOP: medium none; BORDER-RIGHT: medium none"
                      src="/images/bill/event364/bill_nosign.png"
                    />
        <?endif;?>
      </TD>
    </TR>

    </TBODY>
  </TABLE></DIV></BODY></HTML>