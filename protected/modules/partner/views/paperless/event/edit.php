<?php

use application\helpers\Flash;
use application\models\paperless\Device;
use application\models\paperless\Event;

/**
 * @var \partner\models\forms\paperless\Event $form
 * @var Event $event
 * @var $this \partner\components\Controller
 * @var $activeForm CActiveForm
 */

$this->setPageTitle(Yii::t('app', 'Добавление события'));

$possibleRoles = $form->getRoles();
$possibleDevices = $form->getDevices();
$possibleMaterials = $form->getMaterials();

?>

<? $activeForm = $this->beginWidget('CActiveForm', ['htmlOptions' => ['enctype' => 'multipart/form-data']]) ?>
<div class="panel panel-info">
    <div class="panel-heading">
        <span class="panel-title"><span class="fa fa-plus"></span> <?= Yii::t('app', 'Правила обработки (фильтры для входящих сигналов)') ?></span>
    </div>
    <div class="panel-body">
        <?=$activeForm->errorSummary($form, '<div class="alert alert-danger">', '</div>')?>
        <?=Flash::html()?>
        <div class="row">
			<div class="col-md-6">
				<h4><label>Основные параметры:</label></h4>
				<div class="form-group">
					<div class="checkbox">
						<label><b><?=$activeForm->checkBox($form, 'Active') ?> <?= $form->getAttributeLabel('Active')?></b></label>
					</div>
					<div class="checkbox">
						<label>
                            <?= $activeForm->checkBox($form, 'SendOnce') ?> <?= $form->getAttributeLabel('SendOnce') ?>
						</label>
					</div>
				</div>
				<div class="form-group">
					<h4><?=$activeForm->label($form, 'Devices')?><br></h4>
                    <?if(!empty($possibleDevices)):?>
                        <?=$activeForm->checkBoxList($form, 'Devices', CHtml::listData($possibleDevices, 'Id', function (Device $device) { return "{$device->Name} #{$device->DeviceNumber}".($device->Active ? '' : ' <font color="silver">(неактивно)</font>'); }))?>
                    <?else:?>
						<div class="alert">На мероприятии не найдено ни одного устройства. Добавьте их, или приложите к ним бейдж.</div>
                    <?endif?>
				</div>
			</div>
			<div class="col-md-6">
				<div class="form-group">
					<h4><?=$activeForm->label($form, 'Roles')?></h4>
                    <?if(!empty($possibleRoles)):?>
                        <?=$activeForm->checkBoxList($form, 'Roles', CHtml::listData($possibleRoles, 'Id', 'Title'))?>
                    <?else:?>
						<div class="alert alert-danger">На мероприятии не найдено ни одного статуса участия.</div>
                    <?endif?>
				</div>
			</div>
        </div>
		<br><br>
        <div class="row">
            <div class="col-md-12">
                <div class="form-group">
                    <div class="checkbox">
                        <label>
                            <?= $activeForm->checkBox($form, 'ConditionLike') ?> <?= $form->getAttributeLabel('ConditionLike') ?>
                        </label>
                        <?= $activeForm->textArea($form, 'ConditionLikeString', ['style' => 'width:100%', 'rows' => 6]) ?>
                    </div>
                </div>
                <div class="form-group">
                    <div class="checkbox">
                        <label>
                            <?= $activeForm->checkBox($form, 'ConditionNotLike') ?> <?= $form->getAttributeLabel('ConditionNotLike') ?>
                        </label>
                        <?= $activeForm->textArea($form, 'ConditionNotLikeString', ['style' => 'width:100%', 'rows' => 6]) ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="panel-footer">
        <?=CHtml::submitButton($event->isNewRecord ? 'Создать' : 'Сохранить', ['class' => 'btn btn-primary'])?>
		<?if(false === $event->isNewRecord):?>
            <?=CHtml::submitButton('Применить', ['name' => 'apply', 'class' => 'btn btn-default'])?>
		<?endif?>
    </div>
</div>

<div class="panel panel-info">
    <div class="panel-heading">
        <span class="panel-title"><span class="fa fa-plus"></span> <?=Yii::t('app', 'Действия')?></span>
    </div>
    <div class="panel-body">
        <div class="row">
            <div class="col-md-6">
                <div class="form-group">
					<div class="form-group">
						<div class="checkbox">
							<?=$activeForm->checkBox($form, 'Send')?>
							<h4><?=$form->getAttributeLabel('Send')?></h4>
						</div>
					</div>
					<div class="form-group">
                        <?=$activeForm->label($form, 'Subject')?>
                        <?=$activeForm->textField($form, 'Subject', ['class' => 'form-control'])?>
					</div>
					<div class="form-group">
                        <?=$activeForm->label($form, 'Text')?>
                        <?=$activeForm->textArea($form, 'Text', ['class' => 'form-control', 'rows' => '17'])?>
					</div>
					<div class="form-group">
                        <?=$activeForm->label($form, 'File')?>
                        <?=$activeForm->fileField($form, 'File', ['class' => 'form-control'])?>
					</div>
                </div>
            </div>
            <div class="col-md-6">
                <div class="form-group">
					<h4><?=$activeForm->label($form, 'Materials')?>:</h4>
					<div class="alert alert-light-green">При удовлетворении всех условий обработки, отображение выбранных материалов в личном кабинете будет форсировано.</div>
					<div class="form-group">
                        <?if(!empty($possibleMaterials)):?>
                            <?=$activeForm->checkBoxList($form, 'Materials', CHtml::listData($possibleMaterials, 'Id', 'Name'))?>
                        <?else:?>
							<div class="alert">На мероприятии не найдено ни одного материала. Добавьте их что бы задействовать механики активации их отображения для посетителей по считыванию бейджа.</div>
                        <?endif?>
					</div>
                </div>
            </div>
        </div>
    </div>
    <div class="panel-footer">
        <?=CHtml::submitButton($event->isNewRecord ? 'Создать' : 'Сохранить', ['class' => 'btn btn-primary'])?>
		<?if(false === $event->isNewRecord):?>
            <?=CHtml::submitButton('Применить', ['name' => 'apply', 'class' => 'btn btn-default'])?>
		<?endif?>
    </div>
</div>
<? $this->endWidget() ?>
