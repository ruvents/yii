<?php
/**
 * @var \event\widgets\Registration $this
 * @var \pay\models\Product[] $products
 * @var \pay\models\Account $account
 * @var \event\models\Participant $participant
 * @var array $productsByGroup
 * @var bool $paidEvent
 */
if (empty($products))
    return;
?>
<?= CHtml::beginForm(Yii::app()->createUrl('/pay/cabinet/register', ['eventIdName' => $this->event->IdName]), 'post', ['class' => 'registration event-registration']); ?>
    <?= CHtml::hiddenField(Yii::app()->getRequest()->csrfTokenName, Yii::app()->getRequest()->getCsrfToken()); ?>

    <h5 class="title"><?=isset($this->RegistrationTitle) ? $this->RegistrationTitle : \Yii::t('app', 'Регистрация');?></h5>
    <?php $this->widget('\event\widgets\Participant', ['event' => $this->getEvent()]); ?>
    <?if (isset($this->RegistrationBeforeInfo)):?>
        <?=$this->RegistrationBeforeInfo;?>
    <?endif;?>

    <table class="table table-condensed">
        <thead>
        <tr>
            <th></th>
            <th class="t-right col-width"><?= Yii::t('app', 'Цена'); ?></th>
            <th class="t-center col-width"><?= Yii::t('app', 'Кол-во'); ?></th>
            <th class="t-right col-width"><?= Yii::t('app', 'Сумма'); ?></th>
        </tr>
        </thead>
        <tbody>

        <?php foreach ($productsByGroup as $groupName => $value): ?>
            <?php if (!is_array($value)): ?>
                <?php $this->render('registration/product', ['product' => $value]); ?>
            <?php else: ?>
                <tr>
                    <td colspan="4">
                        <article>
                            <h4 class="article-title"><?= $groupName; ?></h4>
                        </article>
                    </td>
                </tr>
                <?php foreach ($value as $product): ?>
                    <?php $this->render('registration/product', ['product' => $product, 'groupProduct' => true]); ?>
                <?php endforeach; ?>
            <?php endif; ?>
        <?php endforeach; ?>

        <tr>
            <td colspan="4" class="t-right total">
                <span><?= Yii::t('app', 'Итого'); ?>:</span> <b id="total-price"
                                                                class="number">0</b> <?= Yii::t('app', 'руб.'); ?>
            </td>
        </tr>
        </tbody>
    </table>

<?php if (isset($this->RegistrationNote) && !empty($this->RegistrationNote)): ?>
    <p class="m-bottom_20"><span class="required-asterisk">*</span>
        <small class="muted"><?= $this->RegistrationNote; ?></small>
    </p>
<?php endif; ?>

<?php $this->render('registration/footer', ['participant' => $participant, 'paidEvent' => $paidEvent]); ?>

<?= CHtml::endForm(); ?>