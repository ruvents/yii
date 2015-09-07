<?php
/**
 * @var \application\components\controllers\AdminMainController $this
 * @var Template $form
 * @var \application\widgets\ActiveForm $activeForm
 */

use \mail\models\forms\admin\Template;
?>

<div id="attach">
    <div class="control-group">
        <?=$activeForm->label($form, 'Attachments', ['class' => 'control-label']);?>
        <div class="controls">
            <?php $this->widget('CMultiFileUpload', [
                'model' => $form,
                'attribute' => 'Attachments',
                'htmlOptions' => ['class' => 'form-control', 'id' => 'Attachments']
            ]);?>
            <?php if ($form->isUpdateMode() && file_exists($form->getPathAttachments())):?>
                <?php $files = CFileHelper::findFiles($form->getPathAttachments());?>
                <table class="table table-striped table-bordered m-top_30">
                    <?php foreach($files as $file):?>
                        <?php $name = basename($file);?>
                        <tr>
                            <td><?=\CHtml::link($name);?></td>
                            <td style="width: 1px;">
                                <?=\CHtml::link('<i class="icon-remove"></i>', ['deleteattachment', 'id' => $form->getActiveRecord()->Id, 'file' => $name], ['class' => 'btn btn-mini']);?>
                            </td>
                        </tr>
                    <?php endforeach;?>
                </table>
            <?php endif;?>
        </div>
    </div>
</div>
