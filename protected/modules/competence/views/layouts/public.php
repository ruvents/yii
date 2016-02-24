<?php
/**
 * @var MainController $this
 */

use event\components\WidgetPosition;
use event\models\Event;

$event = $this->test->EventId && $this->renderEventHeader ? Event::model()->findByPk($this->test->EventId) : null;
?>
<?php $this->beginContent('//layouts/public') ?>
    <?php if (isset($event)): ?>
        <?php
            foreach($event->Widgets as $widget) {
                if ($widget->getPosition() === WidgetPosition::Header) {
                    $widget->run();
                }
            }
        ?>
    <?php endif ?>

    <div class="container m-top_40">
        <h3 class="text-center competence-title"><?= $this->test->Title ?></h3>
    </div>

    <?php if (!empty($this->question)): ?>
        <div class="container">
            <?php $percent = $this->question->getForm()->getPercent() ?>
            <?php if ($percent !== null): ?>
                <p style="text-align: center;">Опрос пройден на <strong><?= $percent; ?>%</strong></p>
                <div class="row">
                    <div class="span8 offset2">
                        <div class="progress progress-success progress-striped">
                            <div class="bar" style="width: <?= intval($percent) ?>%"></div>
                        </div>
                    </div>
                </div>
            <?php endif ?>
        </div>
    <?php endif ?>

    <div class="container interview m-top_30 m-bottom_40">
        <?= $content ?>
    </div>
<?php $this->endContent() ?>