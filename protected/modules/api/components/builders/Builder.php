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

    $this->user->RunetId = $user->RunetId;
    $this->user->LastName = $user->LastName;
    $this->user->FirstName = $user->FirstName;
    $this->user->FatherName = $user->FatherName;

    $this->user->Photo = new \stdClass();
    $this->user->Photo->Small = $user->getPhoto()->get50px();
    $this->user->Photo->Medium = $user->getPhoto()->get90px();
    $this->user->Photo->Large = $user->getPhoto()->get200px();

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
    //todo: Продумать работу выдачи данных по мероприятию
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

}
