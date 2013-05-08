<?php
class EditController extends \application\components\controllers\AdminMainController
{
  public function actionIndex($commissionId = null)
  {
    $form = new \commission\models\forms\Edit();
    if ($commissionId !== null)
    {
      $commission = \commission\models\Commission::model()->findByPk($commissionId);
      if ($commission == null)
      {
        throw new \CHttpException(404);
      }
      $form->Title = $commission->Title;
      $form->Description = $commission->Description;
      $form->Url = $commission->Url;
    }
    else
    {
      $commission = new \commission\models\Commission();
    }
    
    $request = \Yii::app()->getRequest();
    $form->attributes = $request->getParam(get_class($form));
    if ($request->getIsPostRequest() && $form->validate())
    { 
      $commission->Title = $form->Title;
      $commission->Description = $form->Description;
      $commission->Url = $form->Url;
      $commission->save();
      \Yii::app()->user->setFlash('success', \Yii::t('app', 'Информация о комиссии успешно сохранена!'));
      $this->redirect(
        $this->createUrl('/commission/admin/edit/index', array('commissionId' => $commission->Id))
      );
    }
    $this->setPageTitle(\Yii::t('app', 'Редактирование комиссии РАЭК'));
    $this->render('index', array('form' => $form));
  }
}
