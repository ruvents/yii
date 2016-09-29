<?php
/**
 * @var $this \event\widgets\Header
 */
$event = $this->event;
?>
<div class="b-event-promo" style="background: #E5E1BE url('/img/event/pravodownload2013/header_bg940px.jpg') no-repeat center center;">
  <div class="container">
    <div class="row">
      <div class="details span8 offset2">
        <h2 class="title"><?=$event->Title?></h2>
        <div class="type">
          <?=$event->Type->Title?>
        </div>
        <div class="duration">
          <span class="datetime">
            <span class="date">
              <?$this->widget('\event\widgets\Date', array('event' => $event))?>
            </span>
          </span>
        </div>
        <?if($event->getContactAddress() != null && !empty($event->getContactAddress()->Place)):?>
          <div class="location">
            <?=$event->getContactAddress()->Place?></div>
        <?endif?>
      </div>

      <?if($this->eventPage):?>
      <div class="side right span2">
        <div class="actions img-circle">
          <div class="calendar">
            <div class="calendar">
              <span><i class="icon-calendar"></i><br/>В календарь</span><br/>
              <a href="<?=\Yii::app()->createUrl('/event/view/share', ['targetService' => 'Google', 'idName' => $event->IdName])?>" class="pseudo-link">Google Calendar</a>
              <a href="<?=\Yii::app()->createUrl('/event/view/share', ['targetService' => 'iCal', 'idName' => $event->IdName])?>" class="pseudo-link">iCalendar (.ics)</a>
            </div>
          </div>
          <nav class="social">
            <script type="text/javascript" src="//yandex.st/share/share.js" charset="utf-8"></script>
            <div class="yashare-auto-init" data-yashareL10n="ru" data-yashareType="none" data-yashareQuickServices="vkontakte,facebook,twitter"></div>
          </nav>
        </div>
      </div>
      <?endif?>
    </div>

    <?if($this->eventPage):?>
    <span class="all">
      <a href="<?=Yii::app()->createUrl('/event/list/index')?>"><?=Yii::t('app', 'Все мероприятия')?></a>
    </span>
    <?endif?>
  </div>
</div>
