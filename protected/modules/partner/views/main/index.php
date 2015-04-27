<?php
/**
 * @var \event\models\Role[] $roles
 * @var \event\models\Event $event
 * @var \partner\components\Controller $this
 * @var \event\components\Statistics $statistics
 * @var array $timeSteps
 * @var array $textStatistics
 */

$this->setPageTitle('Статистика мероприятия');
$this->titleIcon = 'area-chart';
$this->initActiveBottomMenu('index');
$statRegistrationsByRoles = $statistics->getRegistrationsAll();
$statRegistrationDeltaByRoles = $statistics->getRegistrationsDelta();
?>
<div class="row">
    <div class="col-sm-12">
        <?=$this->renderPartial(
            'index/text-stat',
            [
                'timeSteps' => $timeSteps,
                'roles' => $roles,
                'textStatistics' => $textStatistics
            ]
        );?>

        <?if (sizeof($event->Parts) > 0):?>
            <?$this->renderPartial('index/parts', [
                'statistics' => $textStatistics,
                'event' => $event,
                'timeSteps' => $timeSteps
            ]);?>
        <?endif;?>
    </div>
</div>

<div class="row">
    <div class="col-sm-12">
        <?=$this->renderPartial('index/charts', ['statistics' => $statistics]);?>
    </div>
</div>