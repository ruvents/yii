<?php
/**
 * @var \event\models\Event $event
 * @var \user\models\User $user
 * @var \pay\models\OrderItem[] $orderItems
 * @var \event\models\Participant[] $participants
 * или
 * @var \event\models\Participant $role
 */
?>

<style type="text/css">
  /* Reset */
  html, body, div, span, h1, h2, h3, h4, h5, h6, p, img, b, i, ol, ul, li, table, caption, tbody, tfoot, thead, tr, th, td {
    margin: 0;
    padding: 0;
    border: 0;
    outline: 0;
    font-size: 100%;
    vertical-align: baseline;
    background: transparent;
  }
  /*body {line-height: 1;}*/
  a {
    margin: 0;
    padding: 0;
    font-size: 100%;
    vertical-align: baseline;
    background: transparent;
  }
  table {
    border-collapse: collapse;
    border-spacing: 0;
  }

  /* Styles */
  body {
    margin: 0;
    padding: 0;
    font-size: 13px;
    font-family: Helvetica, Arial, sans-serif;
  }

  a {color: #00a8ca;}
  a:hover {text-decoration: none;}

  h1 {font-size: 50px;}
  h2 {font-size: 35px;}
  h3 {
    font-size: 19px;
    line-height: 1.25;
  }
  h2, h3 {font-weight: normal;}

  p, li {line-height: 1.5;}

  img, td {vertical-align: top;}

  table {
    width: 740px;
    margin: 0 auto;
  }

  ul {
    margin-left: 20px;
  }

  .role {
    margin-top: 15px;
    margin-bottom: 10px;
    display: block;
    /*width: 140px;*/
    text-align: center;
    padding: 5px 0;
    background-color: #4f4f4f;
    color: #ffffff;
    text-transform: uppercase;
  }
  .extra_pay {
    margin-top: 5px;
    display: block;
    /*width: 140px;*/
    text-align: center;
    padding: 2px 0;
    background-color: #4481A4;
    color: #ffffff;
  }
  a.program-btn {
    background: transparent url("http://runet-id.com/images/mail/riw13/program_bg.png") no-repeat;
    color: #FFFFFF;
    display: block;
    font-size: 12px;
    height: 26px;
    line-height: 24px;
    margin-top: 12px;
    text-align: center;
    text-transform: uppercase;
    width: 192px;
  }
</style>

<body>
<table style="width: 660px;  color: #4e4e4e; font-family: tahoma; font-size: 14px; background-color: #F6F6F6; background-repeat: no-repeat; background-position: center -70px; padding-left: 20px; padding-right: 20px; padding-bottom: 20px; border: 20px solid #F6F6F6; page-break-after: always; margin-bottom: 20px;" cellpadding="0" cellspacing="0">
  <tr>
    <td>
      <table style="width: 100%;" cellpadding="0" cellspacing="0">
        <tr>
          <td style="padding-top: 20px; padding-left: 30px; padding-right: 30px; background-color: #ffffff">
            <table style="width: 100%" cellpadding="0" cellspacing="0">
              <tr>
                <td>
                  <table>
                    <?if ($role->Id == 24):?>
                      <tr>
                        <td style="color: #fff; background-color: #e74b44; padding: 10px 20px; text-align: center; font-size: 14px;" colspan="2"><strong>ВНИМАНИЕ!</strong> Ваш статус «Виртуальный Участник» - данный статус не дает права прохода на конференцию. Для получения полноценного статуса «Участник», вам необходимо оплатить свое участие.</td>
                      </tr>
                      <tr><td style="height: 10px;" colspan="2"></td></tr>
                    <?endif;?>
                    <tr>
                      <td>
                        <span style="font-size: 35px; font-weight: bold;"><?if ($role->Id != 24):?>Электронный билет &mdash;<br/><?endif;?><?=$event->Title;?></span>
                        <br><br>
                        <span style="font-size: 20px;"><?$this->widget('event\widgets\Date', ['event' => $event, 'html' => false]);?></span>
                      </td>
                      <td style="text-align: right;">
                        <img src="<?=$event->getLogo()->get120px();?>" alt=""/>
                      </td>
                    </tr>
                  </table>
                </td>
              </tr>
              <tr>
                <td style="padding-top: 10px;">
                  <hr size="1" color="#d2d2d2" />
                </td>
              </tr>
              <tr>
                <td>
                  <table style="width: 100%" cellpadding="0" cellspacing="0">
                    <tr>
                      <td valign="top" style="width: 270px; padding-right: 20px;">
                        <div style="color: #000; font-size: 30px; font-weight: bold; line-height: 35px;"><?=$user->getFullName();?></div>
                        <div style="font-size: 12px; margin-top: 5px;"><?=$user->getEmploymentPrimary();?></div>
                      </td>
                      <td valign="top" style="padding-right: 20px; width: 205px; font-size: 12px;">
                        <div class="role"><?=$role->Title;;?></div>
                      </td>
                      <?if ($role->Id != 24):?>
                      <td valign="top" style="background: #f6f6f6; padding: 10px; text-align: center;" align="right">
                        <img src="<?=\ruvents\components\QrCode::getAbsoluteUrl($user,120);?>" />
                      </td>
                      <?endif;?>
                    </tr>
                  </table>
                </td>
              </tr>
              <?if ($role->Id != 24):?>
              <tr>
                <td align="right" style="padding-top: 5px;">RUNET&mdash;ID <a href="<?=$user->getUrl();?>" style="color: #339dd5;"><?=$user->RunetId;?></a></td>
              </tr>
              <tr>
                <td style="text-align: center; padding-top: 20px;">
                  <img src="http://runet-id.com/images/mail/riw13/warning.png" />
                </td>
              </tr>
              <?endif;?>
              <tr>
                <td style="padding-top: 10px;">
                  <hr size="1" color="#d2d2d2" />
                </td>
              </tr>


              <?if ($event->getContactAddress() != null):?>
                <tr>
                  <td>
                    <span style="font-size: 35px; font-weight: bold;">Место проведения</span>
                  </td>
                </tr>
                <tr>
                  <td style="padding-top: 10px;">
                    <b><?=$event->getContactAddress()->Place;?></b><br>
                    <?=$event->getContactAddress();?>
                  </td>
                </tr>
                <tr>
                  <td style="padding-top: 10px; text-align: center;">
                    <img style="border: 1px solid;  height: 330px;" src="http://static-maps.yandex.ru/1.x/?l=map&ll=<?=$event->getContactAddress()->getLongitude();?>,<?=$event->getContactAddress()->getLatitude();?>&z=14&size=630,330&pt=<?=$event->getContactAddress()->getLongitude();?>,<?=$event->getContactAddress()->getLatitude();?>,pm2ntl" border="0"/>
                  </td>
                </tr>
              <?endif;?>


              <tr>
                <td style="text-align: center; padding: 20px 0px 0; font-size: 11px;">
                  <p><?if ($event->getContactSite() != null):?> Подробная информация: <a href="<?=$event->getContactSite();?>" target="_blank"><?=$event->getContactSite()->Url;?></a> | <?endif;?>Служба поддержки: <a href="mailto:support@runet-id.com">support@runet-id.com</a></p>
                </td>
              </tr>
              <tr>
                <td style="text-align: center; padding: 20px;">
                  <img src="http://runet-id.com/images/mail/spic13/ticket/runet-id_logo.gif" />
                </td>
              </tr>

              <tr>
                <td>
                  <table style="width: 100%; font-size: 15px; margin-bottom: 20px;" cellpadding="0" cellspacing="0">
                    <tr>
                      <td style="padding: 15px; border: 1px dashed #000000; width: 50%;">
                        <img src="/img/event/dg14/ticket/offer_qrcode_paypal.png" alt="" title="" style="float: right;  margin-left: 10px;"/>
                        <p style="padding-bottom: 10px; margin-top: 15px;">Cпециальное предложение для участников Digital Goods<br/>
                          от <img src="http://getlogo.org/img/paypal/375/70x/" alt="PayPal" title="PayPal" style="display: inline-block; margin-top: 5px;"/></p>
                      </td>
                      <td style="padding: 15px; border: 1px dashed #000000; width: 50%;">
                        <img src="/img/event/dg14/ticket/offer_qrcode_vtb24.png" alt="" title="" style="float: right;  margin-left: 10px;"/>
                        <p style="padding-bottom: 10px; margin-top: 15px;">Cпециальное предложение для участников Digital Goods от<br/>
                          <img src="http://getlogo.org/img/vtb24/351/x30/" alt="ВТБ24" title="ВТБ24" style="display: inline-block; margin-top: 5px;"/></p>
                      </td>
                    </tr>
                  </table>
                </td>
              </tr>
            </table>
          </td>
        </tr>
      </table>
    </td>
  </tr>
</table>
<div style="text-align: center; width: 810px; padding: 15px; border: 1px dashed #000000; margin-bottom: 15px;">
  <img src="/img/event/dg14/ticket/banner1.png" />
</div>
<div style="text-align: center; width: 810px; padding: 15px; border: 1px dashed #000000;">
  <img src="/img/event/dg14/ticket/banner2.png" />
</div>
</body>