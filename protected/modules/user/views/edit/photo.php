<?=$this->renderPartial('parts/title');?>

<div class="user-account-settings">
  <div class="clearfix">
    <div class="container">
      <div class="row">
        <div class="span3">
          <?=$this->renderPartial('parts/nav', array('current' => $this->getAction()->getId()));?>
        </div>
        <div class="span9">

          <?=\CHtml::form('','POST',array('class' => 'b-form', 'enctype' => 'multipart/form-data'));?>

            <div class="form-header">
              <h4>Фотография профиля</h4>
            </div>
            
            <?=$this->renderPartial('parts/form-alert', array('form' => $form));?>

            <div class="form-row b-avatar-upload">
              <?=\CHtml::image($user->getPhoto()->get238px(), $user->getFullName(), array('class' => 'avatar-upload-240'));?>
              <?=\CHtml::image($user->getPhoto()->get90px(), $user->getFullName(), array('class' => 'avatar-upload-140'));?>
              <?=\CHtml::image($user->getPhoto()->get18px(), $user->getFullName(), array('class' => 'avatar-upload-60'));?>
            </div>

            <div class="form-row">
              <?=\CHtml::button(\Yii::t('app', 'Выбрать фотографию'), array('class' => 'btn btn-primary', 'id' => 'avatar-upload-button'));?>
              <?=\CHtml::activeFileField($form, 'Image', array('id' => 'avatar-upload-input', 'style' => 'visibility: hidden; width: 0; height: 0;'));?>
            </div>

            <div class="form-info"><?=\Yii::t('app', 'Выберите фотографию размером до 2 мегабайт в формате *.jpg, *.gif или *.png.');?></div>

            <div class="form-help"><?=\Yii::t('app', 'Используйте только свои фотографии, другие изображения будут удаляться.');?></div>

            <div class="form-footer">
              <?=\CHtml::submitButton(\Yii::t('app', 'Сохранить'), array('class' => 'btn btn-info'));?>
            </div>

          <?=\CHtml::endForm();?>

        </div>
      </div>
    </div>
  </div>
</div>