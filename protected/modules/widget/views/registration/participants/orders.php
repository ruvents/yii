<?php
/**
 * @var \widget\components\Controller $this
 * @var \pay\components\collection\Finder $finder
 */

use pay\models\OrderType;

$collection = $finder->getUnpaidOrderCollections();
$formatter = \Yii::app()->getDateFormatter();
?>

<?php if (sizeof($collection) > 0):?>
    <hr/>
    <table class="table orders">
        <thead>
            <tr>
                <th colspan="3"><h4><?=\Yii::t('app', 'Выставленные счета');?></h4></th>
            </tr>
        </thead>
        <tbody>
        <?php foreach ($collection as $item):?>
            <?php
            /** @var \pay\models\Order $order */
            $order = $item->getOrder();
            if ($order === null) continue;?>
            <tr>
                <td><?=OrderType::getTitle($order->Type);?> № <?=$order->Number;?> <?=\Yii::t('app', 'от');?> <?=$formatter->format('d MMMM yyyy', $order->CreationTime);?></td>
                <td>
                    <?php if ($order->Paid):?>
                        <span class="label label-success"><?=Yii::t('app', 'Оплачен');?></span>
                    <?php endif;?>
                </td>
                <td class="text-right"><?=\CHtml::link(OrderType::getTitleViewOrder($order->Type), $order->getUrl(), ['target' => '_blank']);?></td>
            </tr>
        <?php endforeach;?>
        </tbody>
    </table>
<?php endif;?>
