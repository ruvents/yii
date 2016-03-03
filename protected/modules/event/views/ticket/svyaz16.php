<?php
/**
 * @var User $user
 * @var Event $event
 * @var Participant|Participant[] $participant
 */

use event\models\UserData;
use user\models\User;
use event\models\Event;
use event\models\Participant;

$data = UserData::model()->find([
    'condition' => '"EventId" = :eventId AND "UserId" = :userId',
    'params' => [
        ':eventId' => $event->Id,
        ':userId' => $user->Id
    ]
]);

$customNumber = null;
if ($data) {
    $customNumber = $data->getManager()->Custom_Number;
}

?>
<style>
    main {
        display: block;
        height: 200px;
        padding: 10px;
        margin: 0;
        border: 3px solid #ccc;
        font-family: "Helvetica Neue", "Helvetica", Helvetica, Arial, sans-serif;
        font-size: 12px;
    }

    .text-uppercase {
        text-transform: uppercase;
    }

    h1, h2, h3 {
        display: block;
        padding-left: 6px;
        padding-right: 6px;
        background-color: #0C6BB2;
        text-transform: uppercase;
        color: #ffffff;
        margin: 0 0 10px;
    }

    h1 {
        padding-top: 14px;
        padding-bottom: 14px;
    }

    h2 {
        padding-top: 8px;
        padding-bottom: 8px;
        font-size: 20px;
    }

    h3 {
        padding-top: 8px;
        padding-bottom: 8px;
        font-size: 12px;
    }

    p {
        margin: 0 0 10px;
    }

    .text-lg {
        font-size: 14px;
    }

    .text-xl {
        font-size: 16px;
    }

    .text-xxl {
        font-size: 18px;
    }

    .text-right {
        text-align: right;
    }

    .p-a-2 {
        padding: 2px !important;
    }

    .p-t-30 {
        padding-top: 30px !important;
    }

    .p-a-10 {
        padding: 10px !important;
    }

    .m-b-10 {
        margin-bottom: 10px !important;
    }

    .m-b-0 {
        margin-bottom: 0 !important;
    }

    .m-b-20 {
        margin-bottom: 20px !important;
    }

    .m-b-50 {
        margin-bottom: 50px !important;
    }

    .m-l-10 {
        margin-left: 10px !important;
    }

    .m-l-20 {
        margin-left: 20px !important;
    }

    .b-l-3-solid-blue {
        border-left: 3px solid #0C6BB2;
    }

    .col-1, .col-2, .col-3, .col-4, .col-5, .col-6, .col-7, .col-8, .col-9, .col-10, .col-11 {
        position: relative;
        display: block;
        float: left;
    }

    .col-1 {
        width: 8.33333%;
    }

    .col-offset-1 {
        margin-left: 8.33333%;
    }

    .col-2 {
        width: 16.66667%;
    }

    .col-3 {
        width: 25%;
    }

    .col-4 {
        width: 33.33333%;
    }

    .col-5 {
        width: 41.66667%;
    }

    .col-6 {
        width: 50%;
    }

    .col-7 {
        width: 58.33333%;
    }

    .col-8 {
        width: 66.66667%;
    }

    .col-9 {
        width: 75%;
    }

    .col-10 {
        width: 83.33333%;
    }

    .col-11 {
        width: 91.66667%;
    }

    .age {
        font-size: 60px;
        line-height: 60px;
    }

    .text-blue {
        color: #0C6BB2;
    }

    .pull-left {
        float: left;
    }

    .text-center {
        text-align: center;
    }
</style>
<main>
    <header class="m-b-20">
        <div class="col-7">
            <div class="p-a-2">
                <h1>Ваш электронный билет</h1>
                <strong><span class="text-uppercase">Внимание</span>! Вход на выставку только для специалистов</strong>
            </div>
        </div>
        <div class="col-5 m-l-20">
            <div class="p-a-2">
                <img src="/img/ticket/svyaz16/svyaz.png" alt="" class="pull-left">
                <div class="age text-blue m-l-20">12+</div>
            </div>
        </div>
    </header>
    <section class="m-b-10">
        <div class="col-5">
            <div class="p-a-2">
                <h2>Имя посетителя</h2>
                <strong class="text-uppercase text-xl"><?= $user->getFullName() ?></strong>
            </div>
        </div>
        <div class="col-7">
            <div class="p-a-2">
                <h2>Персональный штрихкод</h2>
                <div>
                    <div class="col-8 text-center">
                        <barcode code="<?=$customNumber?>" type="C128A" class="barcode" size="1" height="2" text="1"/>
                       <?=$customNumber?>
                    </div>
                    <div class="col-4">
                        <strong><span
                                class="text-uppercase">Внимание</span>! Электронный билет продаже не подлежит!</strong>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <section class="m-b-50">
        <div class="col-5">
            <div class="p-a-2">
                <h2>Название мероприятия</h2>
                <strong class="text-xl"><?= $event->Title ?></strong>
            </div>
        </div>
        <div class="col-4">
            <div class="p-a-2">
                <h2>Даты проведения</h2>
                <strong
                    class="text-xl"><?= $event->StartDay ?>-<?= $event->EndDay ?> мая <?= $event->StartYear ?>г.</strong>
            </div>
        </div>
        <div class="col-3">
            <div class="p-a-2">
                <h2>Павильоны</h2>
            </div>
            <strong class="text-xl">2, 8</strong>
        </div>
    </section>
    <section>
        <div class="col-5">
            <div class="p-a-10 b-l-3-solid-blue">
                <p>
                    <strong>
                        <?= $event->getContactAddress()->Place ?>,
                        <?= $event->getContactAddress()->Country->Name ?>,
                        <?= $event->getContactAddress()->City->Name ?>,
                        <?= $event->getContactAddress()->Street ?>,
                        <?= $event->getContactAddress()->House ?>
                    </strong>
                </p>
                <p>
                    Схема проезда на ЦВК и время работы размещены на сайте:<br>
                    <a href="http://www.expocentr.ru/">www.expocentr.ru</a>
                </p>
            </div>
        </div>
        <div class="col-6">
            <div class="p-a-10 b-l-3-solid-blue">
                <p><strong>Для входа на выставочный комплекс необходимо:</strong></p>
                <ol>
                    <li>На стойке онлайн-регистрации предъявить оператору билет для сканирования штрихкода</li>
                    <li>Получить Ваш персональный именной бейдж посетителя выставки.</li>
                </ol>
            </div>
        </div>
    </section>
    <footer>
        <div class="col-5">

            <div class="p-a-2">
                <img src="/img/ticket/svyaz16/expo.png" alt="" width="260">
            </div>
        </div>
        <div class="col-4">

            <div class="p-t-30">
                <p class="text-uppercase text-xl">Добро пожаловать <br>в &laquo;Экспоцентр&raquo;!</p>
            </div>
        </div>
        <div class="col-3">
            <div class="p-a-2">
                <h3 class="m-b-0">Дата и время создания электронного билета</h3>
                <div class="p-a-10 b-l-3-solid-blue">
                    <?= date('Y-m-d H:i:s') ?>
                </div>
            </div>
        </div>
    </footer>
</main>
