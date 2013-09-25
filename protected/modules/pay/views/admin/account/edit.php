<?
/**
 * @var \pay\models\forms\admin\Acccount $form
 * @var \pay\models\Account $account
 */
?>
<?=\CHtml::form('','POST',['class' => 'form-horizontal', 'enctype' => 'multipart/form-data']);?>
<?=\CHtml::activeHiddenField($form, 'EventId');?>
<div class="btn-toolbar clearfix">
  <a href="<?=$this->createUrl('/pay/admin/account/index');?>" class="btn"><i class="icon-arrow-left"></i> <?=\Yii::t('app', 'Вернуться к списку');?></a>
  <?=\CHtml::submitButton(\Yii::t('app', 'Сохранить изменения'), ['class' => 'btn btn-success pull-right']);?>
</div>
<div class="well">
<?=\CHtml::errorSummary($form, '<div class="alert alert-error">', '</div>');?>
<?if (\Yii::app()->getUser()->hasFlash('success')):?>
  <div class="alert alert-success"><?=\Yii::app()->getUser()->getFlash('success');?></div>
<?endif;?>
<div class="control-group">
  <?=\CHtml::activeLabel($form, 'EventTitle', ['class' => 'control-label']);?>
  <div class="controls">
    <?=\CHtml::activeTextField($form, 'EventTitle', ['readonly' => !$form->getAccount()->getIsNewRecord()]);?>
  </div>
</div>
<div class="control-group">
  <?=\CHtml::activeLabel($form, 'Own', ['class' => 'control-label']);?>
  <div class="controls">
    <?=\CHtml::activeCheckBox($form, 'Own');?>
  </div>
</div>
<div class="control-group">
  <?=\CHtml::activeLabel($form, 'OrderTemplateName', ['class' => 'control-label']);?>
  <div class="controls">
    <?=\CHtml::activeDropDownList($form, 'OrderTemplateName', $form->getOrderTemplateNameData());?>
  </div>
</div>
<div class="control-group">
  <?=\CHtml::activeLabel($form, 'ReturnUrl', ['class' => 'control-label']);?>
  <div class="controls">
    <?=\CHtml::activeTextField($form, 'ReturnUrl');?>
  </div>
</div>
<div class="control-group">
  <?=\CHtml::activeLabel($form, 'Offer', ['class' => 'control-label']);?>
  <div class="controls">
    <div class="m-bottom_5">
      <?=\CHtml::activeDropDownList($form, 'Offer', $form->getOfferData());?>
    </div>
    <?=\CHtml::activeFileField($form, 'OfferFile');?>
  </div>
</div>
<div class="control-group">
  <?=\CHtml::activeLabel($form, 'OrderLastTime', ['class' => 'control-label']);?>
  <div class="controls">
    <?=\CHtml::activeTextField($form, 'OrderLastTime');?>
  </div>
</div>
<div class="control-group">
  <?=\CHtml::activeLabel($form, 'OrderEnable', ['class' => 'control-label']);?>
  <div class="controls">
    <?=\CHtml::activeCheckBox($form, 'OrderEnable');?>
  </div>
</div>
<div class="control-group">
  <?=\CHtml::activeLabel($form, 'Uniteller', ['class' => 'control-label']);?>
  <div class="controls">
    <?=\CHtml::activeCheckBox($form, 'Uniteller');?>
  </div>
</div>
  <div class="control-group">
  <?=\CHtml::activeLabel($form, 'PayOnline', ['class' => 'control-label']);?>
  <div class="controls">
    <?=\CHtml::activeCheckBox($form, 'PayOnline');?>
  </div>
</div>
</div>
<?=\CHtml::endForm();?>
