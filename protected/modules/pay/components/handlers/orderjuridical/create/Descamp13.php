<?php
namespace pay\components\handlers\orderjuridical\create;

class Descamp13 extends Base
{
  public function getSubject()
  {
    if (!$this->order->Receipt)
    {
      return 'Счет на оплату '.$this->event->Title;
    }
    else
    {
      return 'Квитанция на оплату '.$this->event->Title;
    }
  }

  public function getFrom()
  {
    return 'event@runet-id.com';
  }

  public function getFromName()
  {
    return 'Design Camp';
  }

  protected function getViewPath()
  {
    return 'pay.views.mail.orderjuridical.create.descamp13';
  }
}