<?php
/**
 * @var \application\components\controllers\PublicMainController $this
 * @var Event $event
 * @var Test $test
 */
use event\models\Event;
use competence\models\Test;
?>
<div class="container interview m-top_30 m-bottom_40">
    <div class="row">
        <div class="span8 offset2 m-top_30 text-center">
            <?php if (!empty($test->AfterText)):?>
                <?=$test->AfterText;?>
            <?php else:?>
                <p class="lead">Здравствуйте!</p>
                <p class="lead">Опрос окончен, спасибо за интерес к мероприятию.</p>
            <?php endif;?>
        </div>
    </div>
</div>