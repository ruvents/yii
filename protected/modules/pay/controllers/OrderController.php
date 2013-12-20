<?php

class OrderController extends \application\components\controllers\MainController
{
  public $layout = '/layouts/bill';

  public function actionIndex($orderId, $hash = null, $clear = null)
  {
    /** @var $order \pay\models\Order */
    $order = \pay\models\Order::model()->findByPk($orderId);
    if ($order === null || (!$order->Juridical && !$order->Receipt))
      throw new \CHttpException(404);

    $checkHash = $order->checkHash($hash);
    if (!$checkHash && (\Yii::app()->user->getCurrentUser() === null || \Yii::app()->user->getCurrentUser()->Id != $order->PayerId))
    {
      throw new \CHttpException(404);
    }

    $billData = array();
    $total = 0;
    $collection = \pay\components\OrderItemCollection::createByOrder($order);
    foreach ($collection as $item)
    {
      $orderItem = $item->getOrderItem();
      $price = $item->getPriceDiscount($order->CreationTime);
      if (! isset($billData[$orderItem->ProductId.$price]))
      {
        $billData[$orderItem->ProductId.$price] = array(
          'Title' => $orderItem->Product->getManager()->GetTitle($orderItem),
          'Unit' => $orderItem->Product->Unit,
          'Count' => 0,
          'DiscountPrice' => $price,
          'ProductId' => $orderItem->ProductId
        );
      }
      $billData[$orderItem->ProductId.$price]['Count'] += 1;
      $total += $price;
    }

    /** @var $account \pay\models\Account */
    $account = \pay\models\Account::model()->byEventId($order->EventId)->find();

    $template = null;
    if (!$order->Receipt)
    {
      $template = $order->Template != null ? $order->Template : $account->OrderTemplate;
      if ($template->OrderTemplateName === null)
      {
        $viewName = 'template';
      }
      else
      {
        $viewName = $template->OrderTemplateName;
      }
      $viewName = 'bills/'.$viewName;
    }
    else
    {
      $template = $order->Template != null ? $order->Template : $account->ReceiptTemplate;
      $viewName = 'receipt/template';
    }


    $this->setPageTitle('Счёт № ' . $order->Number);
    $this->render($viewName, [
      'order' => $order,
      'billData' => $billData,
      'total' => $total,
      'nds' => $total - round($total / 1.18, 2, PHP_ROUND_HALF_DOWN),
      'withSign' => $clear===null,
      'template' => $template
    ]);
  }
}
