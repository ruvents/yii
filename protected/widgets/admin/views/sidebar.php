<?php
/**
 * @var $this \application\widgets\admin\Sidebar
 */
?>

<div class="sidebar-nav">
  <form class="search form-inline">
    <input type="text" placeholder="Search...">
  </form>

  <a data-toggle="collapse" class="nav-header" href="#menu-users"><i class="icon-user icon-white"></i>Пользователи</a>
  <ul class="nav nav-list collapse" id="menu-users">
    <li><a href="index.html">Список пользователей</a></li>
    <li><a href="users.html">Объединение</a></li>
    <li><a href="user.html">Видимость</a></li>
    <li><a href="media.html">Быстрая авторизация</a></li>
    <li><a href="calendar.html">Контакты</a></li>
    <li><a href="">Статистика</a></li>
  </ul>

  <a data-toggle="collapse" class="nav-header" href="#menu-events"><i class="icon-calendar icon-white"></i><?=\Yii::t('app', 'Мероприятия');?> <?if($counts->Event != 0):?><span class="label label-info">+<?=$counts->Event?></span><?endif;?></a>
  <ul class="nav nav-list collapse" id="menu-events">
    <li><a href="<?=Yii::app()->createUrl('/event/admin/list/index');?>"><?=\Yii::t('app', 'Список мероприятий');?></a></li>
    <li><a href="<?=Yii::app()->createUrl('/event/admin/list/index', array('Approved' => \event\models\Approved::Yes));?>"><?=\Yii::t('app','Принятые');?></a></li>
    <li><a href="<?=Yii::app()->createUrl('/event/admin/list/index', array('Approved' => \event\models\Approved::None));?>"><?=\Yii::t('app','На одобрение');?> <?if($counts->Event != 0):?><span class="label label-info pull-right">+<?=$counts->Event?></span><?endif;?></a></li>
    <li><a href="<?=Yii::app()->createUrl('/event/admin/list/index', array('Approved' => \event\models\Approved::No));?>"><?=\Yii::t('app','Отклоненные');?></a></li>
  </ul>

  <a data-toggle="collapse" class="nav-header collapsed" href="#menu-companies"><i class="icon-briefcase icon-white"></i>Компании</i></a>
  <ul class="nav nav-list collapse" id="menu-companies">
    <li><a href="403.html">Список компаний</a></li>
    <li><a href="404.html">Объединение</a></li>
  </ul>

  <!--<a class="nav-header" href="help.html"><i class="icon-question-sign"></i>Help</a>
  <a class="nav-header" href="faq.html"><i class="icon-comment"></i>Faq</a>-->
</div>