<div class="navbar-inner">
  <div class="container">
    <a class="brand" href="<?=Yii::app()->createUrl('/main/default/index');?>">
      <img src="/images/logo-small.png" width="115" height="10" alt="-RUNET-ID-">
    </a>
    <ul class="nav">
      <li class="item"><a href="<?=Yii::app()->createUrl('/event/list/index');?>">Мероприятия</a></li>
      <li class="item"><a target="_blank" href="http://therunet.com/">Новости</a></li>
      <li class="item"><a href="/#competences">Компетенции</a></li>
      <li class="item"><a href="<?=Yii::app()->createUrl('/job/default/index');?>">Работа</a></li>
      <li class="divider-vertical"></li>
      <?if (Yii::app()->user->getCurrentUser() === null):?>
        <li class="login"><a id="NavbarLogin" href="#">Войти / Зарегистрироваться</a></li>
      <?else:?>
        <li class="account dropdown">
          <a data-toggle="dropdown" class="dropdown-toggle" href="#">
            <img width="18" height="18" class="avatar" alt="" src="<?=Yii::app()->user->getCurrentUser()->getPhoto()->get18px();?>">
            <?=Yii::app()->user->getCurrentUser()->getName();?>
            <b class="caret"></b>
          </a>
          <ul class="dropdown-menu pull-right">
            <li><a href="<?=Yii::app()->createUrl('/user/view/index', array('runetId' => Yii::app()->user->getCurrentUser()->RunetId));?>">Мой профиль</a></li>
            <li><a href="<?=Yii::app()->createUrl('/user/edit/index');?>">Редактирование профиля</a></li>
            <li><a href="<?=Yii::app()->createUrl('/user/setting/password');?>">Настройки профиля</a></li>
            <li><a href="<?=Yii::app()->createUrl('/user/logout/index');?>">Выйти</a></li>
          </ul>
        </li>
      <?endif;?>
      <li class="divider-vertical"></li>
    </ul>
    <ul class="nav pull-right">
      <li class="lang dropdown">
        <a href="<?=Yii::app()->createUrl('/'.Yii::app()->getController()->route, array_merge($_GET, array('lang' => Yii::app()->getLanguage())));?>" class="dropdown-toggle" data-toggle="dropdown">
          <?=mb_strtoupper(Yii::app()->getLanguage());?>
          <b class="caret"></b>
        </a>
        <ul class="dropdown-menu">
          <?foreach (Yii::app()->params['Languages'] as $lang):?>
            <?if ($lang != Yii::app()->getLanguage()):?>
              <li>
                <a href="<?=Yii::app()->createUrl('/'.Yii::app()->getController()->route, array_merge($_GET, array('lang' => $lang)));?>"><?=mb_strtoupper($lang);?></a>
              </li>
            <?endif;?>
          <?endforeach;?>
        </ul>
      </li>
    </ul>
  </div>
</div>

