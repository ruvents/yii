<?php
/**
 * @var \application\components\controllers\AdminMainController $this
 * @var Template $form
 */
use mail\models\forms\admin\Template;

$template = $form->getActiveRecord();
?>

<?php if ($template !== null):?>
    <?php if ($template->Active && $template->Success):?>
        <h3 class="text-success"><?=\Yii::t('app', 'Рассылка ушла. Всего писем: {count}', ['{count}' => $form->getSentCount()]); ?></h3>
    <?php elseif ($template->Active && !$template->Success):?>
        <h3><?=\Yii::t('app', 'Рассылка отправляется. Отправилось {count} писем из {total}.', ['{count}' => $form->getSentCount(), '{total}' => $form->getRecipientsCount()]); ?></h3>
    <?php else:?>
        <h3><?=\Yii::t('app', 'Получателей'); ?>: <?=$form->getRecipientsCount();?></h3>
    <?php endif;?>
<?php endif;?>