<?php
namespace api\components\builders;

/**
 * Методы делятся на 2 типа:
 * 1. Методы вида createXXX - создают объект с основными данными XXX, сбрасывают предыдущее заполнение объекта XXX
 * 2. Методы вида buildXXXSomething - дополняют созданный объект XXX новыми данными. Какими именно, можно понять по названию Something
 */
class Builder
{
  /**
   * @var \api\models\Account
   */
  protected $account;

  /**
   * @param \api\models\Account $account
   */
  public function __construct($account)
  {
    $this->account = $account;
  }

  protected $user;
  /**
   * @param \user\models\User $user
   * @return \stdClass
   */
  public function createUser(\user\models\User $user)
  {
    $this->user = new \stdClass();

    $this->user->RocId = $user->RunetId; //todo: deprecated
    $this->user->RunetId = $user->RunetId;
    $this->user->LastName = $user->LastName;
    $this->user->FirstName = $user->FirstName;
    $this->user->FatherName = $user->FatherName;

    $this->user->Photo = new \stdClass();
    $this->user->Photo->Small  = rtrim(\Yii::app()->getController()->createAbsoluteUrl($user->getPhoto()->get50px()), '/');
    $this->user->Photo->Medium = rtrim(\Yii::app()->getController()->createAbsoluteUrl($user->getPhoto()->get90px()), '/');;
    $this->user->Photo->Large  = rtrim(\Yii::app()->getController()->createAbsoluteUrl($user->getPhoto()->get200px()), '/');

    return $this->user;
  }

  /**
   * @param \user\models\User $user
   * @return \stdClass
   */
  public function buildUserEmail(\user\models\User $user)
  {
    if ($user->getContactEmail() !== null)
    {
      $this->user->Email = $user->getContactEmail()->Email;
    }
    else
    {
      $this->user->Email = $user->Email;
    }

    return $this->user;
  }

  /**
   * @param \user\models\User $user
   * @return \stdClass
   */
  public function buildUserEmployment($user)
  {
    $employment = $user->getEmploymentPrimary();
    if ($employment !== null)
    {
      $this->user->Work = new \stdClass();
      $this->user->Work->Position = $employment->Position;
      $this->user->Work->Company = $this->createCompany($employment->Company);
      $this->user->Work->StartYear = $employment->StartYear;
      $this->user->Work->StartMonth = $employment->StartMonth;
      $this->user->Work->EndYear = $employment->EndYear;
      $this->user->Work->EndMonth = $employment->EndMonth;
    }

    return $this->user;
  }

  /**
   * @param \user\models\User $user
   * @return \stdClass
   */
  public function buildUserEvent(\user\models\User $user)
  {
    $isOnePart = empty($this->account->Event->Parts);
    foreach ($user->Participants as $participant)
    {
      if ($this->account->EventId != null && $participant->EventId == $this->account->EventId)
      {
        if ($isOnePart)
        {
          $this->user->Status = new \stdClass();
          $this->user->Status->RoleId = $participant->RoleId;
          $this->user->Status->RoleName = $participant->Role->Title;
          $this->user->Status->RoleTitle = $participant->Role->Title;
          $this->user->Status->UpdateTime = $participant->UpdateTime;
        }
        else
        {
          if (!isset($this->user->Status))
          {
            $this->user->Status = array();
          }
          $status = new \stdClass();
          $status->PartId = $participant->PartId;
          $status->RoleId = $participant->RoleId;
          $status->RoleName = $participant->Role->Title;
          $status->RoleTitle = $participant->Role->Title;
          $status->UpdateTime = $participant->UpdateTime;
          $this->user->Status[] = $status;
        }
      }
      elseif ($this->account->EventId == null)
      {
        if (!$participant->Event->Visible)
        {
          continue;
        }
        $status = new \stdClass();
        $status->RoleId = $participant->RoleId;
        $status->RoleName = $participant->Role->Title;
        $status->RoleTitle = $participant->Role->Title;
        $status->UpdateTime = $participant->UpdateTime;
        $status->Event = $this->CreateEvent($participant->Event);
        $this->user->Status[] = $status;
      }
    }

    return $this->user;
  }

  protected $company;
  /**
   * @param \company\models\Company $company
   * @return \stdClass
   */
  public function createCompany(\company\models\Company $company)
  {
    $this->company = new \stdClass();
    $this->company->CompanyId = $company->Id;
    $this->company->Name = $company->Name;

    return $this->company;
  }


  protected $role;
  /**
   * @param \event\models\Role $role
   * @return \stdClass
   */
  public function createRole(\event\models\Role $role)
  {
    $this->role = new \stdClass();

    $this->role->RoleId = $role->Id;
    $this->role->Name = $role->Title;
    $this->role->Priority = $role->Priority;

    return $this->role;
  }

  protected $event;
  /**
   * @param \event\models\Event $event
   * @return \stdClass
   */
  public function createEvent($event)
  {
    $this->event = new \stdClass();

    $this->event->EventId = $event->Id;
    $this->event->IdName = $event->IdName;
    $this->event->Name = html_entity_decode($event->Title); //todo: deprecated
    $this->event->Title = html_entity_decode($event->Title);
    $this->event->Info = $event->Info;
    if ($event->getContactAddress() !== null)
    {
      $this->event->Place = $event->getContactAddress()->__toString();
    }
    $this->event->Url = $event->Url;
    $this->event->UrlRegistration = $event->UrlRegistration;
    $this->event->UrlProgram = $event->UrlProgram;
    $this->event->DateStart = $event->DateStart;
    $this->event->DateEnd = $event->DateEnd;
/*
    $this->event->Image = new \stdClass();

    $webRoot = \Yii::getPathOfAlias('webroot');
    $miniLogo = $event->GetMiniLogo();
    $logo = $event->GetLogo();
    $this->event->Image->Mini = 'http://rocid.ru' . $miniLogo;
    $this->event->Image->MiniSize = $this->getImageSize($webRoot . $miniLogo);
    $this->event->Image->Normal = 'http://rocid.ru' . $logo;
    $this->event->Image->NormalSize = $this->getImageSize($webRoot . $logo);
*/
    return $this->event;
  }
  
  
  protected $product;
  /**
  * @param Product $product
  * @param string $time
  * @return stdClass
  */
  public function createProduct($product, $time = null)
  {
    $this->product = new \stdClass();
    $this->product->Id = $product->Id; 
    $this->product->ProductId = $product->Id; /** DEPLICATED **/
    $this->product->Manager = $product->ManagerName;
    $this->product->Title = $product->Title;
    $this->product->Price = $product->getPrice($time);
    $this->product->Attributes = array();
    foreach ($product->Attributes as $attribute)
    {
      $this->product->Attributes[$attribute->Name] = $attribute->Value;
    }
    return $this->product;
  }

  
  protected $orderItem;
  /**
  * @param OrderItem $orderItem
  * @return stdClass
  */
  public function createOrderItem($orderItem)
  {
    $this->orderItem = new stdClass();
    
    $this->orderItem->OrderItemId = $orderItem->Id; /** DEPLICATED **/
    $this->orderItem->Id = $orderItem->Id;
    $this->orderItem->Product = $this->CreateProduct($orderItem->Product, $orderItem->PaidTime);
    $this->createUser($orderItem->Payer);
    $this->orderItem->Payer = $this->buildUserEmployment($orderItem->Payer);
    $this->createUser($orderItem->Owner);
    $this->orderItem->Owner = $this->buildUserEmployment($orderItem->Owner);
    $this->orderItem->PriceDiscount = $orderItem->getPriceDiscount();
    $this->orderItem->Paid = $orderItem->Paid == 1;
    $this->orderItem->PaidTime = $orderItem->PaidTime;
    $this->orderItem->Booked = $orderItem->Booked;

    $this->orderItem->Params = array(); /** DEPLICATED **/
    $this->orderItem->Attributes = array();
    foreach ($orderItem->Attributes as $attribute)
    {
      $this->orderItem->Params[$attribute->Name] = $attribute->Value; /** DEPLICATED **/
       $this->orderItem->Attributes[$attribute->Name] = $attribute->Value;
    }

    $couponActivation = $orderItem->getCouponActivation();
    $this->orderItem->Discount = !empty($couponActivation) && !empty($couponActivation->Coupon) ? $couponActivation->Coupon->Discount : 0;
    $this->orderItem->CouponCode = !empty($couponActivation) && !empty($couponActivation->Coupon) ? $couponActivation->Coupon->Code : null;
    return $this->orderItem;
  }
  
  
  
  private function getImageSize($path)
  {
    $size = null;
    if (file_exists($path))
    {
      $key = md5($path);
      $size = \Yii::app()->getCache()->get($key);
      if ($size === false)
      {
        $size = new \stdClass();
        $image = imagecreatefrompng($path);
        $size->Width = imagesx($image);
        $size->Height = imagesy($image);
        imagedestroy($image);
        \Yii::app()->getCache()->add($key, $size, 3600 + mt_rand(10, 500));
      }
    }
    return $size;
  }
}
