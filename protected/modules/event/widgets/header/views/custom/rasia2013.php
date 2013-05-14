<?php
/**
 * @var $this \event\widgets\header\PHDays
 */
$event = $this->event;
?>
<div class="b-event-promo" style="background: url('/img/event/rasia13/header.png') repeat-x scroll 0 0 transparent;">
  <div class="container" style="background: none;">
    <div class="row">
      <div class="side left span4">
        <a target="_blank" href="http://rasia.com/"><img src="/img/event/rasia13/logo.png" alt="<?=CHtml::encode($event->Title);?>" style="margin-top: 35px;"></a>
      </div>

      <div class="details span4 offset4">
        <a target="_blank" href="http://rasia.com/"><img style="margin-top: 10px; margin-bottom: 50px" src="/img/event/rasia13/title.png" alt="<?=CHtml::encode($event->Title);?>"></a>

        <div class="duration">
          <span class="datetime">
            <span class="date">
              <?$this->widget('\event\widgets\Date', array('event' => $event));?>
            </span>
          </span>
        </div>
        <?if ($event->getContactAddress() != null && !empty($event->getContactAddress()->Place)):?>
          <div class="location"><?=$event->getContactAddress()->Place;?></div>
        <?endif;?>
      </div>

      <?if ($this->eventPage):?>
        <div class="side right span4">
          <div class="actions">
            <div class="calendar img-circle">
              <a href="<?=  str_replace('http://', 'webcal://', \Yii::app()->getController()->createAbsoluteUrl('/event/share/ical', array('idName' => $event->IdName)));?>" class="pseudo-link">
                <i class="icon-calendar"></i><br><?=\Yii::t('app', 'Добавить в&nbsp;календарь');?>
              </a>
            </div>
            <nav class="social">
              <script type="text/javascript" src="//yandex.st/share/share.js" charset="utf-8"></script>
              <div class="yashare-auto-init" data-yashareL10n="ru" data-yashareType="none" data-yashareQuickServices="vkontakte,facebook,twitter"></div>
            </nav>
          </div>
        </div>
      <?endif;?>
    </div>

    <?if ($this->eventPage):?>
      <span class="all">
      <a href="<?=Yii::app()->createUrl('/event/list/index');?>"><?=Yii::t('app', 'Все мероприятия');?></a>
    </span>
    <?endif;?>
  </div>
</div>