<?php
/**
 * @var PublicMainController $this
 * @var BaseDocument[] $forms
 */

use \application\components\controllers\PublicMainController;
use user\models\forms\document\BaseDocument;
use application\helpers\Flash;

$this->setPageTitle(\Yii::t('app', 'Документы удостоверяющие личность'));
?>
<?=$this->renderPartial('parts/title');?>

<script type="text/javascript">
    $(function () {
        var $tabs = $("#user-account-settings-tabs");
        var index = $tabs.find('ul.nav li.active').index();
        if (index != -1) {
            $tabs.tabs({'active' : index});
        }
    });
</script>

<div class="user-account-settings">
    <div class="clearfix">
        <div class="container">
            <div class="row">
                <div class="span3">
                    <?=$this->renderPartial('parts/nav', ['current' => $this->getAction()->getId()]);?>
                </div>
                <div class="span9">
                    <div class="b-form">
                        <div class="form-header">
                            <h4><?=\Yii::t('app', 'Документы удостоверяющие личность');?></h4>
                        </div>
                        <?=Flash::html();?>
                        <div class="tabs" id="user-account-settings-tabs">
                            <ul class="nav">
                                <?php foreach($forms as $form):?>
                                    <li <?if ($form->isActive()):?>class="active"<?php endif;?>><a href="#user-account-settings_document-<?=$form->getDocumentType()->FormName;?>"><?=$form->getTitle();?></a></li>
                                <?php endforeach;?>
                            </ul>
                            <?php foreach($forms as $form):?>
                                <div class="tab" id="user-account-settings_document-<?=$form->getDocumentType()->FormName;?>">
                                    <?php $form->renderEditView($this);?>
                                </div>
                            <?php endforeach;?>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>