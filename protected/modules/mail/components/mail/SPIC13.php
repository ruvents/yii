<?php
namespace mail\components\mail;

class SPIC13 extends \mail\components\Mail 
{
  public $user = null;
  
  public function getFrom()
  {
    return 'users@sp-ic.ru';
  }
  
  public function getFromName()
  {
    return 'СПИК-2013';
  }
  
  public function getSubject()
  {
    return 'Приглашаем на СПИК 2013! ';
  }
  
  public function getBody()
  {
    return \Yii::app()->getController()->renderPartial('mail.views.partner.spic13-1', array('user' => $this->user, 'personalLink' => $this->getPersonalLink()), true);
  }
  
  private function getPersonalLink()
  {
    $hash = substr(md5($this->user->RunetId.'xggMpIQINvHqR0QlZgZa'), 0, 16);;
    return 'http://2013.sp-ic.ru/my/'.$this->user->RunetId.'/'.$hash.'/';
  }
}

?>
