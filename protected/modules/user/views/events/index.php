<?php
/**
 * @var \application\components\controllers\PublicMainController $this
 */
$this->setPageTitle(\Yii::t('app', 'Мои мероприятия'));
?>
<h2 class="b-header_large light">
    <div class="line"></div>
    <div class="container">
        <div class="title">
            <span class="backing runet">Runet</span>
            <span class="backing text">Мои мероприятия</span>
        </div>
    </div>
</h2>
<div class="user-account-settings">
    <div class="clearfix">
        <div class="container">
            <div class="row">
                <div class="span3">
                    <?=$this->renderPartial('parts/nav', ['current' => $this->getAction()->getId()]); ?>
                </div>
                <div class="span9">
                    <?php if (empty($futureEvents) && empty($pastEvents)): ?>
                        <div class="alert alert-danger text-center">Вы не принимали участие ни в одном мероприятии</div>
                    <?php endif; ?>
                    <div class="tabs" id="user-account-settings-tabs">
                        <ul class="nav">
                            <?php if (!empty($futureEvents)):?>
                                <li><a href="#user-account-settings_events-future"><?=\Yii::t('app', 'Предстоящие');?></a></li>
                            <?php endif;?>
                            <?php if (!empty($pastEvents)):?>
                                <li><a href="#user-account-settings_events-past"><?=\Yii::t('app', 'Прошедшие');?></a></li>
                            <?php endif;?>
                        </ul>
                        <?php if (!empty($futureEvents)): ?>
                            <div class="tab" id="user-account-settings_events-future">
                                <table class="table">
                                    <?php  foreach ($futureEvents as $event): ?>
                                        <tr>
                                            <td>
                                                <a href="<?=$event->Event->getUrl();?>" target="_blank">
                                                    <?=\CHtml::image($event->Event->getLogo()->getMini());?>
                                                </a>
                                            </td>
                                            <td>
                                                <a href="<?=$event->Event->getUrl();?>" target="_blank"><?= $event->Event->Title ?></a>
                                                <br />
                                                <?= $event->Role->Title ?>
                                            </td>
                                            <td>
                                                <a class="ticket" href="<?= $event->getTicketUrl(); ?>" target="_blank"><img src="/images/receipt12.png" alt="Билет" /></a>
                                            </td>
                                        </tr>
                                    <?php endforeach; ?>
                                </table>
                            </div>
                        <?php endif; ?>
                        <?php if (!empty($pastEvents)): ?>
                            <div class="tab" id="user-account-settings_events-past">
                                <table class="table">
                                    <?php foreach ($pastEvents as $event): ?>
                                        <tr>
                                            <td>
                                                <a href="<?=$event->Event->getUrl();?>" target="_blank">
                                                    <?=\CHtml::image($event->Event->getLogo()->getMini());?>
                                                </a>
                                            </td>
                                            <td>
                                                <a href="<?=$event->Event->getUrl();?>" target="_blank"><?= $event->Event->Title ?></a>
                                                <br />
                                                <?= $event->Role->Title ?>
                                            </td>
                                            <td>
                                                <a class="ticket" href="<?= $event->getTicketUrl(); ?>" target="_blank"><img src="/images/receipt12.png" alt="Билет" /></a>
                                            </td>
                                        </tr>
                                    <?php endforeach ?>
                                </table>
                            </div>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    $(document).ready(function(){
        $(".tabs").lightTabs();
    });
</script>



