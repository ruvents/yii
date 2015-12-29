<?php
use partner\components\Controller;
use partner\models\forms\user\Participant;

/**
 * @var Controller $this
 * @var Participant $form
 */

$this->setPageTitle('Редактирование участника мероприятия');
$clientScript = \Yii::app()->getClientScript();
$clientScript->registerPackage('angular');
$clientScript->registerScript('init', '
    new CUserEdit(' . $form->getParticipantJson() . ');
', \CClientScript::POS_HEAD);
?>
<div ng-controller="UserEditController">
    <?=$this->renderPartial('edit/info', ['user' => $form->getActiveRecord(), 'event' => $this->getEvent()]);?>

    <div class="panel panel-warning" ng-if="data">
        <div class="panel-heading">
            <span class="panel-title"><i class="fa fa-list-alt"></i> <?=\Yii::t('app', 'Атрибуты пользователей');?></span>
        </div> <!-- / .panel-heading -->
        <table class="table table-bordered table-striped">
            <thead>
            <tr>
                <th ng-repeat="title in data[0].titles">{{title}}</th>
                <th></th>
            </tr>
            </thead>
            <tbody>
            <tr ng-repeat="row in data" ng-class="{'editable-data' : row.edit}">
                <td ng-repeat="attribute in row.attributes">
                    <span ng-bind-html="attribute.value" ng-show="!row.edit"></span>
                    <div ng-bind-html="attribute.edit" ng-show="row.edit"></div>
                </td>
                <td style="width: 200px;">
                    <div class="btn-group btn-group-xs">
                        <button class="btn" ng-class="{'btn btn-success' : row.edit, 'btn' : !row.edit}" ng-click="updateDataValues(row)" type="button">{{!row.edit ? '<?=\Yii::t('app', 'Редактировать');?>' : '<?=\Yii::t('app', 'Сохранить');?>'}}</button>
                    </div>
                    <div class="{{row.class ? 'text-' + row.class : '' }}" ng-if="row.class"><small>{{row.message}}</small></div>
                </td>
            </tr>
            </tbody>
        </table>
    </div>

    <div class="panel panel-danger">
        <div class="panel-heading">
            <span class="panel-title">&nbsp;</span>
            <ul class="nav nav-tabs nav-tabs-xs nav-tabs-left">
                <li class="active">
                    <a href="#user-participant" data-toggle="tab"><?=\Yii::t('app', 'Роль на мероприятии');?></a>
                </li>
                <li ng-if="products.length > 0">
                    <a href="#user-products" data-toggle="tab"><?=\Yii::t('app', 'Опции');?></a>
                </li>
            </ul> <!-- / .nav -->
        </div> <!-- / .panel-heading -->
        <div class="panel-body">
            <div class="tab-content">
                <div class="tab-pane active" id="user-participant">
                    <div class="form-group {{participant.class ? 'has-' + participant.class : '' }}" ng-repeat="participant in participants">
                        <label class="control-label" ng-if="participant.Title != undefined">{{participant.Title}}</label>
                        <select class="form-control" ng-model="participant.role" ng-options='role.Id as role.Title for role in <?=$form->getRoleDataJson();?>'>
                            <option value="">Роль не задана</option>
                        </select>
                        <p class="help-block" ng-if="participant.message">{{participant.message}}</p>
                    </div>
                </div>
                <div class="tab-pane" id="user-products" ng-if="products.length > 0">
                    <div class="{{product.class ? 'has-' + product.class : '' }}" ng-repeat="product in products">
                        <div class="checkbox">
                            <label>
                                <input type="checkbox" ng-model="product.Paid" ng-change="changeProduct(product)" /> {{product.Title}}
                            </label>
                        </div>
                        <p class="help-block" ng-if="product.message">{{product.message}}</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>


<?$this->beginWidget('\application\widgets\bootstrap\Modal', [
    'id' => 'participant-message',
    'header' => 'Укажите комментарий',
    'footer' => \CHtml::button(\Yii::t('app', 'Сохранить'), ['class' => 'btn btn-primary']),
]);?>
    <textarea class="form-control"></textarea>
<?$this->endWidget();?>
