<?php
class ListController extends \application\components\controllers\AdminMainController
{
  public function actionIndex()
  {
    $criteria = new \CDbCriteria();
    $criteria->with = array('UsersActive');
    $criteria->order = '"t"."Title" ASC';
    $commissions = \commission\models\Commission::model()->findAll($criteria);
    $this->setPageTitle(\Yii::t('app', 'Комиссии РАЭК'));
    $this->render('index', array('commissions' => $commissions));
  }
}
