<?php

use event\models\Event;
use event\models\forms\admin\Promo as PromoForm;

class EditController extends \application\components\controllers\AdminMainController
{
    public function actions()
    {
        return [
            'index' => '\event\controllers\admin\edit\IndexAction',
            'widget' => '\event\controllers\admin\edit\WidgetAction',
            'product' => '\event\controllers\admin\edit\ProductAction',

            'parts' => '\event\controllers\admin\edit\part\IndexAction',
            'partedit' => '\event\controllers\admin\edit\part\EditAction',
            'partdelete' => '\event\controllers\admin\edit\part\DeleteAction',
        ];
    }

    /**
     * Настройки промо-блока мероприятия
     * @param int $id
     * @throws CHttpException
     */
    public function actionPromo($id)
    {
        $event = Event::model()->findByPk($id);
        if ($event === null) {
            throw new \CHttpException(404);
        }

        $form = new PromoForm($event);
        if (\Yii::app()->getRequest()->getIsPostRequest()) {
            $form->fillFromPost();
            if ($form->updateActiveRecord() !== null) {

            }
        }

        $this->render('promo', [
            'form' => $form
        ]);
    }
}
