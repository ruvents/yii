<?php
namespace pay\controllers\admin\booking;

class ListAction extends \CAction
{
    private $hotel = null;
    private $list = [];
    public function run()
    {

        $this->hotel = \Yii::app()->getRequest()->getParam('hotel', \pay\components\admin\Rif::HOTEL_P);

        $partnerBooking = $this->partnerBooking($this->list, $this->hotel);
        $rifBooking = $this->rifBooking($this->list);
        $mainBooking = $this->mainBooking($this->list, $this->hotel);

        $result = array_merge($partnerBooking, $rifBooking, $mainBooking);

        usort($result, [$this, 'sort']);
        $this->getController()->render('list', ['list' => $result, 'hotel' => $this->hotel]);

    }

    /**
     * @param ListItem $item1
     * @param ListItem $item2
     * @return int
     */
    private function sort($item1, $item2)
    {
        $name1 = trim($item1->UserName);
        $name2 = trim($item2->UserName);

        if ($name1 > $name2)
            return 1;
        else if ($name1 < $name2)
            return -1;
        return 0;
    }

    /**
     * @param array $list
     * @param string $hotel
     * @return array
     */

    private function partnerBooking($list, $hotel)
    {
        $bookings = \pay\models\RoomPartnerBooking::model()->findAll();
        foreach ($bookings as $booking)
        {
            $manager = $booking->Product->getManager();
            $people = json_decode($booking->People);
            if (!empty($people) && $manager->Hotel == $hotel)
            {
                foreach ($people as $name)
                {
                    if (!empty($name))
                    {
                        $item = new ListItem();
                        $item->UserName = $name;
                        $item->Housing = $manager->Housing;
                        $item->Number = $manager->Number;
                        $list[] = $item;
                    }
                }
            }
        }
        return $list;
    }

    /**
     * @param array $list
     * @return array
     */

    private function rifBooking($list)
    {
        $command = \pay\components\admin\Rif::getDb()->createCommand();
        $together = $command->select('*')->from('ext_booked_person_together')->queryAll();
        foreach ($together as $row)
        {
            if (array_key_exists($row['ownerRunetId'], $list))
            {
                $item = new ListItem();
                $item->UserName = $row['userName'];
                $item->Housing = $list[$row['ownerRunetId']]->Housing;
                $item->Number = $list[$row['ownerRunetId']]->Number;
                $list[] = $item;
            }
        }
        return $list;
    }

    /**
     * @param array $list
     * @param string $hotel
     * @return array
     */

    private function mainBooking($list, $hotel)
    {
        $criteria = new \CDbCriteria();
        $criteria->with = ['Product.Attributes', 'Owner.Settings'];
        $criteria->addCondition('"Product"."ManagerName" = \'RoomProductManager\'');
        $orderItems = \pay\models\OrderItem::model()->byEventId(\Yii::app()->params['AdminBookingEventId'])->byPaid(true)->findAll($criteria);
        foreach ($orderItems as $orderItem)
        {
            $manager = $orderItem->Product->getManager();
            if ($manager->Hotel == $hotel)
            {
                $item = new ListItem();
                $item->UserName = $orderItem->Owner->getFullName();
                $item->Housing = $manager->Housing;
                $item->Number = $manager->Number;
                $list[] = $item;
            }
        }
        return $list;
    }
}

class ListItem {
    public $UserName;
    public $Housing;
    public $Number;
}