<?php
namespace pay\controllers\cabinet;

class IndexAction extends \pay\components\Action
{
  public function run($eventIdName)
  {
    $this->getController()->setPageTitle('Оплата  / ' .$this->getEvent()->Title . ' / RUNET-ID');

    \partner\models\PartnerCallback::start($this->getEvent());
    if ($this->getUser() != null)
    {
      \partner\models\PartnerCallback::registration($this->getEvent(), $this->getUser());
    }

    $finder = \pay\components\collection\Finder::create($this->getEvent()->Id, $this->getUser()->Id);
    $unpaidItems = new \stdClass();
    $unpaidItems->all = [];
    $unpaidItems->tickets = [];
    foreach ($finder->getUnpaidFreeCollection() as $item)
    {
      $key = $item->getOrderItem()->Product->ManagerName == 'Ticket' ? 'tickets' : 'all';
      if (!isset($unpaidItems->{$key}[$item->getOrderItem()->ProductId]))
      {
        $unpaidItems->{$key}[$item->getOrderItem()->ProductId] = [];
      }
      $unpaidItems->{$key}[$item->getOrderItem()->ProductId][] = $item;
    }

    $allPaidCollections = array_merge($finder->getPaidOrderCollections(), $finder->getPaidFreeCollections());

    $hasRecentPaidItems = false;
    foreach ($allPaidCollections as $collection)
    {
      foreach ($collection as $item)
      {
        /** @var $item \pay\components\OrderItemCollectable */
        if ($item->getOrderItem()->PaidTime > date('Y-m-d H:i:s', time() - 10*60*60))
        {
          $hasRecentPaidItems = true;
          break;
        }
      }
      if ($hasRecentPaidItems)
        break;
    }

    $this->getController()->render('index', array(
      'finder' => $finder,
      'unpaidItems' => $unpaidItems,
      'hasRecentPaidItems' => $hasRecentPaidItems,
      'account' => $this->getAccount()
    ));
  }
}