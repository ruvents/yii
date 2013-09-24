<?php
namespace event\widgets;

class Registration extends \event\components\Widget
{
  public function process()
  {
    $request = \Yii::app()->getRequest();
    $product = $request->getParam('product', array());
    if ($request->getIsPostRequest() && sizeof($product) !== 0)
    {
      $this->getController()->redirect(\Yii::app()->createUrl('/pay/cabinet/register', ['eventIdName' => $this->event->IdName]));
    }
  }


  public function run()
  {
    /** @var $account \pay\models\Account */
    $account = \pay\models\Account::model()->byEventId($this->event->Id)->find();
    if ($account === null)
    {
      return;
    }

    if ($account->ReturnUrl === null)
    {
      \Yii::app()->getClientScript()->registerPackage('runetid.event-calculate-price');
      $criteria = new \CDbCriteria();
      $criteria->order = '"t"."Priority" DESC, "t"."Id" ASC';
      $products = \pay\models\Product::model()->byEventId($this->event->Id)
          ->byPublic(true)->findAll($criteria);
      $this->render('registration', ['products' => $products, 'account' => $account]);
    }
    else
    {
      $this->render('registration-external', ['account' => $account]);
    }
  }

  /**
   * @return string
   */
  public function getTitle()
  {
    return \Yii::t('app', 'Регистрация на мероприятии');
  }

  /**
   * @return string
   */
  public function getPosition()
  {
    return \event\components\WidgetPosition::Content;
  }
}