<?php
/**
 * @var \user\models\User $user
 * @var string $code
 */
?>
<div class="container interview m-top_30 m-bottom_40">
    <div class="row">
        <div class="span8 offset2 m-top_30 text-center">
            <p class="lead">Здравствуйте, <?=$user->FirstName;?>!</p>
            <p class="lead">Спасибо за&nbsp;готовность оставить свое мнение о&nbsp;мероприятии, заполнив анкету участника DevCon 2014. Это займет у&nbsp;вас не&nbsp;более <nobr>5-ти</nobr> минут.</p>
            <p class="lead" style="font-weight: 500;">После заполнения анкеты вы&nbsp;можете смело подойти на&nbsp;стойку &laquo;Информация&raquo; в&nbsp;<nobr>Конгресс-холле</nobr> и&nbsp;получить в подарок:</p>

            <ul class="thumbnails">
              <li class="span4">
                <img src="/img/event/devcon14/test/tfs.png" />
                <h3 class="m-top_10">Книга «Настройка Team Foundation Server 2013»</h3>
              </li>
              <li class="span4">
                <img src="/img/event/devcon14/test/passport.png" />
                <h3 class="m-top_10">Стильная обложка на паспорт</h3>
              </li>
            </ul>

            <div class="text-center m-top_30 m-bottom_30">
                <a href="<?=Yii::app()->createUrl('/main/devcon/process', ['code' => $code]);?>" class="btn btn-success" type="submit">Заполнить анкету</a>
            </div>
        </div>
    </div>
</div>