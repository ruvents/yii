<?php
/**
 * @var \pay\models\Account $account
 * @var int $total
 */

$hideJuridical = $account->OrderLastTime !== null && $account->OrderLastTime < date('Y-m-d H:i:s') || !$account->OrderEnable;
$hideReceipt = $account->ReceiptLastTime !== null && $account->ReceiptLastTime < date('Y-m-d H:i:s') || !$account->ReceiptEnable;

$paysystems = ['uniteller', 'payonline', 'yandexmoney', 'paypal', 'cloudpayments'];
$onlinemoney = ['yandexmoney', 'paypal'];

$systembuttons = [];
$paybuttons = [];
if ($account->Uniteller) {
    $systembuttons[] = 'uniteller';
}
if ($account->PayOnline) {
    $systembuttons[] = 'payonline';
    $paybuttons[] = 'yandexmoney';
}
if ($account->CloudPayments) {
    $systembuttons[] = 'cloudpayments';
}
$paybuttons[] = 'paypal';
if ($account->MailRuMoney) {
    $paybuttons[] = 'mailrumoney';
}
?>

<div class="pay-buttons clearfix">
    <div class="pull-left">
        <h5><?=\Yii::t('app', 'Для юридических лиц');?></h5>
        <?if (!$account->OrderEnable):?>
            <p class="text-error">
                <?if ($account->OrderDisableMessage !== null):?>
                    <?=$account->OrderDisableMessage;?>
                <?else:?>
                    <?=\Yii::t('app', 'Оплата недоступна. Оплата возможна только банковскими картами и электронными деньгами');?>
                <?endif;?>
            </p>
        <?elseif (!empty($account->OrderMinTotal) && $total < $account->OrderMinTotal):?>
            <p class="text-error">
                <?=$account->OrderMinTotalMessage;?>
            </p>
        <?elseif ($hideJuridical && $account->OrderEnable):?>
            <p class="text-error">
                <?if ($account->OrderDisableMessage !== null):?>
                    <?=$account->OrderDisableMessage;?>
                <?else:?>
                    Окончен период выставления счетов юридическими лицами. Оплата возможна только банковскими картами и электронными деньгами.
                <?endif;?>
            </p>
        <?elseif(!$hideJuridical):?>
            <?$this->renderPartial('index/buttons/juridical', ['account' => $account]);?>
        <?endif;?>
    </div>
    <div class="pull-right">
        <h5><?=\Yii::t('app', 'Для физических лиц');?></h5>
        <ul class="clearfix actions pay-systems">
            <?foreach ($systembuttons as $button):?>
                <li>
                    <?$this->renderPartial('index/buttons/'. $button, ['account' => $account, 'system' => $button]);?>
                </li>
            <?endforeach;?>
        </ul>

        <h5><?=\Yii::t('app', 'Электронные деньги');?></h5>
        <ul class="clearfix actions">
            <?foreach ($paybuttons as $button):?>
                <li>
                    <?$this->renderPartial('index/buttons/'.(in_array($button, $paysystems) ? 'onlinemoney' : $button), ['account' => $account, 'system' => $button]);?>
                </li>
            <?endforeach;?>
        </ul>

        <?if (!$hideReceipt):?>
            <h5><?=\Yii::t('app', 'Квитанцией в банке');?></h5>
            <ul class="clearfix actions">
                <li>
                    <?$this->renderPartial('index/buttons/receipt', ['account' => $account, 'system' => $button]);?>
                </li>
            </ul>
        <?endif;?>
    </div>
</div>

<div class="nav-buttons">
    <?$this->renderPartial('index/buttons/back', ['account' => $account]);?>
</div>
