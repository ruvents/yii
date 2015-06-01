<?php
namespace partner\controllers\user;

use api\models\Account;
use api\models\ExternalUser;
use application\models\attribute\Definition;
use event\models\UserData;
use pay\components\OrderItemCollection;
use pay\models\OrderType;

class ExportAction extends \partner\components\Action
{
    private $csvDelimiter = ';';
    private $csvCharset = 'utf8';
    private $language = 'ru';

    public function run()
    {
        if (\Yii::app()->request->getIsPostRequest())
        {
            $this->export();
        }

        /** @var $roles \event\models\Role[] */
        $roles = $this->getEvent()->getRoles();
        $this->getController()->setPageTitle('Экспорт участников в CSV');
        $this->getController()->initActiveBottomMenu('export');
        $this->getController()->render('export', array('roles' => $roles));
    }

    private function export()
    {
        ini_set("memory_limit", "512M");

        if (\Yii::app()->partner->getAccount()->EventId == 391)
        {
            \Yii::app()->language = 'en';
        }

        $request = \Yii::app()->getRequest();
        $this->csvCharset = $request->getParam('charset', $this->csvCharset);
        $this->language = $request->getParam('language', $this->language);
        $roles = $request->getParam('roles');

        \Yii::app()->setLanguage($this->language);

        header('Content-type: text/csv; charset='.$this->csvCharset);
        header('Content-Disposition: attachment; filename=participans.csv');

        $fp = fopen('php://output', '');
        $row = array(
            'RUNET-ID',
            'Фамилия',
            'Имя',
            'Отчество',
            'Компания',
            'Должность',
            'Email',
            'Телефон',
            'Дата рождения',
            'Статус на мероприятии',
            'Приобретенные товары',
            'Cумма оплаты',
            'Тип оплаты',
            'Дата регистрации на мероприятие',
            'Дата оплаты участия',
            'Дата выдачи бейджа'
        );


        $userDataMap = [];

        $data = new UserData();
        $data->EventId = $this->getEvent()->Id;
        foreach ($data->getManager()->getDefinitions() as $definition) {
            $row[] = $definition->title;
            $userDataMap[] = $definition->name;
        }

        $usersData = [];
        $data = UserData::model()->byEventId($this->getEvent()->Id)->byDeleted(false)->findAll();
        foreach ($data as $item) {
            $definitions = $item->getManager()->getDefinitions();
            /** @var Definition $definition */
            foreach ($definitions as $definition) {
                $usersData[$item->UserId][$definition->name][] = $definition->getPrintValue($item->getManager());
            }
        }

        if ($this->hasExternalId()) {
            $row[] = 'Внешний ID';
        }


        fputcsv($fp, $this->rowHandler($row), $this->csvDelimiter);

        $criteria = new \CDbCriteria();
        $criteria->with = array(
            'Participants' => array('on' => '"Participants"."EventId" = :EventId', 'params' => array(
                'EventId' => $this->getEvent()->Id
            ), 'together' => false),
            'Employments' => array('together' => false),
            'Employments.Company' => array('together' => false),
            'LinkPhones.Phone' => array('together' => false)
        );
        $criteria->order = '"t"."LastName" ASC, "t"."FirstName" ASC';

        $command = \Yii::app()->getDb()->createCommand();
        $command->select('EventParticipant.UserId')->from('EventParticipant');
        $command->where('"EventParticipant"."EventId" = '.$this->getEvent()->Id);
        if ($roles !== null)
        {
            $command->andWhere(array('in', 'EventParticipant.RoleId', $roles));
        }
        $criteria->addCondition('"t"."Id" IN ('.$command->getText().')');

        $users = \user\models\User::model()->findAll($criteria);

        foreach ($users as $user)
        {
            /** @var \event\models\Participant $participant */
            $participant = null;
            foreach ($user->Participants as $curP)
            {
                if ($participant == null || $participant->Role->Priority < $curP->Role->Priority)
                {
                    $participant = $curP;
                }
            }

            $phone = $user->PrimaryPhone;
            if (empty($phone) && !empty($user->LinkPhones)) {
                $phone = (string)$user->LinkPhones[0]->Phone;
            }

            $row = array(
                'RUNET-ID' => $user->RunetId,
                'LastName' => $user->LastName,
                'FirstName' => $user->FirstName,
                'FatherName' => $user->FatherName,
                'Company' => '',
                'Position' => '',
                'Email' => $user->Email,
                'Phone' => $phone,
                'Birthday' => $user->Birthday,
                'Role' => $participant != null ? $participant->Role->Title : '-',
                'Products' => '',
                'Price' => 0,
                'PaidType' => '',
                'DateRegister' => \Yii::app()->dateFormatter->format('dd MMMM yyyy H:m', $participant->CreationTime),
                'DatePay' => '',
                'DateBadge' => '',
            );

            if ($user->getEmploymentPrimary() !== null)
            {
                $row['Company'] = $user->getEmploymentPrimary()->Company->Name;
                $row['Position'] = $user->getEmploymentPrimary()->Position;
            }

            $criteria = new \CDbCriteria();
            $criteria->with = [
                'OrderLinks.Order',
                'OrderLinks.Order.ItemLinks'
            ];

            if ($this->getEvent()->Id == 831) {
                $criteria->addInCondition('t."ProductId"', [1451, 1460, 2715]);
            }


            $orderItems = \pay\models\OrderItem::model()
                ->byOwnerId($user->Id)->byChangedOwnerId(null)->byChangedOwnerId($user->Id, false)
                ->byEventId($this->getEvent()->Id)->byPaid(true)->findAll($criteria);

            if (count($orderItems))
            {
                $datePay = [];
                $paidType = [];
                $products = [];
                foreach ($orderItems as $orderItem)
                {
                    $price = 0;
                    $order = $orderItem->getPaidOrder();
                    if ($order !== null) {
                        $collections = OrderItemCollection::createByOrder($order);
                        foreach ($collections as $orderItemCollectable) {
                            if ($orderItemCollectable->getOrderItem()->Id == $orderItem->Id) {
                                $products[] = $orderItemCollectable->getOrderItem()->Product->Title;
                                $price = $orderItemCollectable->getPriceDiscount();
                                break;
                            }
                        }

                        $paidType[] = $order->Type == OrderType::Juridical ? \Yii::t('app', 'Юр. лицо') : \Yii::t('app', 'Физ. лицо');
                    } else {
                        $paidType[] = 'Промо-код';
                    }

                    $row['Price'] += $price;
                    $datePay[] = \Yii::app()->dateFormatter->format('dd MMMM yyyy H:m', strtotime($orderItem->PaidTime));
                }
                $row['Products'] = implode(', ', $products);
                $row['DatePay'] = implode(', ', array_unique($datePay));
                $row['PaidType'] = implode(', ', array_unique($paidType));
            }

            $criteria = new \CDbCriteria();
            $criteria->order = 't."CreationTime"';
            /** @var $badge \ruvents\models\Badge */
            $badge = \ruvents\models\Badge::model()
                ->byEventId($this->getEvent()->Id)->byUserId($user->Id)->find($criteria);
            if ($badge !== null)
            {
                $row['DateBadge'] = $badge->CreationTime;
            }

            foreach ($userDataMap as $name) {
                $row[$name] = isset($usersData[$user->Id][$name]) ? implode(';', $usersData[$user->Id][$name]) : '';
            }

            if ($this->hasExternalId()) {
                $externalUser = ExternalUser::model()->byAccountId($this->getApiAccount()->Id)->byUserId($user->Id)->find();
                $row['ExternalId'] = $externalUser !== null ? $externalUser->ExternalId : '';
            }

            $this->fwritecsv($fp, $this->rowHandler($row), $this->csvDelimiter);
        }

        \Yii::app()->end();
    }

    private function getDyplomInfo(\user\models\User $user)
    {
        $model = \ruvents\models\Badge::model()->byEventId($this->getEvent()->Id)->byUserId($user->Id);
        if ($model->count() > 1)
        {
            $criteria = new \CDbCriteria();
            $criteria->addCondition('t."OperatorId" = 850');
            $criteria->addCondition('t."CreationTime" > \'2014-05-29 00:00:00\'');
            $criteria->order = 't."CreationTime" DESC';
            /** @var $badge \ruvents\models\Badge */
            $badge = \ruvents\models\Badge::model()
                ->byEventId($this->getEvent()->Id)->byUserId($user->Id)->find($criteria);
            if ($badge != null) {
                return $badge->CreationTime;
            }
        }
        return '';
    }

    private function getInfopak(\user\models\User $user) {
        $productGet = \pay\models\ProductGet::model()
            ->byProductId(2758)->byUserId($user->Id)->find();
        if ($productGet != null) {
            return $productGet->CreationTime;
        }
        return '';
    }

    private function getTestResult(\user\models\User $user) {
        $result = \competence\models\Result::model()
            ->byTestId(7)->byUserId($user->Id)->byFinished(true)->find();
        if ($result != null) {
            return $result->UpdateTime;
        }
        return '';
    }

    private function getPrize(\user\models\User $user) {
        $productGet = \pay\models\ProductGet::model()
            ->byProductId(2757)->byUserId($user->Id)->find();
        if ($productGet != null) {
            return $productGet->CreationTime;
        }
        return '';
    }

    /**
     * @param \contact\models\Address $address
     * @return string
     */
    private function getCity($address)
    {
        if ($address == null)
            return '';

        $result = $address->Country->Name . ', ' . $address->Region->Name;
        if ($address->City !== null)
            $result .= ', ' . $address->City->Name;
        return $result;
    }


    private function rowHandler($row)
    {
        foreach ($row as &$item)
        {
            if ($this->csvCharset == 'Windows-1251')
            {
                $item = iconv('utf-8', 'Windows-1251', $item);
            }
        }
        unset($item);
        return $row;
    }

    private function fwritecsv($handle, $fields, $delimiter = ',', $enclosure = '"')
    {
        # Check if $fields is an array
        if (!is_array($fields)) {
            return false;
        }
        # Walk through the data array
        foreach ($fields as $k => $v) {
            $fields[$k] = trim($fields[$k]);
            
            if (!is_numeric($fields[$k]))
                $fields[$k] = $enclosure . str_replace($enclosure, '', $fields[$k]) . $enclosure;
            else
                $fields[$k] = '='.$enclosure . str_replace($enclosure, '', $fields[$k]) . $enclosure;
        }
        # Combine the data array with $delimiter and write it to the file
        $line = implode($delimiter, $fields) . "\n";
        fwrite($handle, $line);
        # Return the length of the written data
        return strlen($line);
    }

    private $hasExternalId = null;

    private function hasExternalId()
    {
        if ($this->hasExternalId === null) {
            $this->hasExternalId = false;
            if ($this->getApiAccount() !== null) {
                $this->hasExternalId = ExternalUser::model()->byAccountId($this->getApiAccount()->Id)->exists();
            }
        }
        return $this->hasExternalId;
    }

    private $apiAccount = false;

    /**
     * @return Account|null
     */
    private function getApiAccount()
    {
        if ($this->apiAccount === false) {
            $this->apiAccount = Account::model()->byEventId($this->getEvent()->Id)->find();
        }
        return $this->apiAccount;
    }
}
