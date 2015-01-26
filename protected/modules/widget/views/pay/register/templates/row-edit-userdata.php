<?php

?>

<script type="text/template" id="row-userdataedit-tpl">
    <tr>
        <td colspan="4" class="last-child">
            <?=CHtml::beginForm('', 'POST', array('class' => 'user-register form-user-register'));?>
            <?= \CHtml::hiddenField(\Yii::app()->request->csrfTokenName, \Yii::app()->request->getCsrfToken());?>
            <header><h4 class="title"><%=userInfo%></h4></header>
            <div class="alert alert-error" style="display: none;"></div>
            <%=editArea%>
            <div class="form-actions">
                <button class="btn btn-cancel"><?=\Yii::t('app', 'Отмена');?></button>
                <button class="btn btn-inverse btn-submit"><?=\Yii::t('app', 'Добавить');?></button>
            </div>
            <?CHtml::endForm();?>
        </td>
    </tr>
</script>