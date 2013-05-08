<?php
namespace user\models;

/**
 * @throws \Exception
 *
 * @property int $Id
 * @property int $RunetId
 * @property string $LastName
 * @property string $FirstName
 * @property string $FatherName
 * @property string $Gender
 * @property string $Birthday
 * @property string $Email
 * @property string $CreationTime
 * @property string $UpdateTime
 * @property string $LastVisit
 * @property string $Password
 * @property string $OldPassword
 * @property bool $Visible
 * @property bool $Temporary
 *
 *
 *
 * Внешние связи
 * @property LinkEmail $LinkEmail
 * @property LinkAddress $LinkAddress
 * @property LinkSite $LinkSite
 * @property LinkPhone[] $LinkPhones
 * @property LinkServiceAccount[] $LinkServiceAccounts
 *
 * @property Employment[] $Employments
 *
 * @property \commission\models\Commission[] $Commissions
 *
 * @property \event\models\Participant[] $Participants

 * @property Settings $Settings Настройки аккаунта пользователя
 *
 * @property \CEvent $onRegister
 *
 *
 * Вспомогательные описания методов методы
 * @method \user\models\User find()
 * @method \user\models\User findByPk()
 * @method \user\models\User[] findAll()
 *
 */
class User extends \application\models\translation\ActiveRecord 
  implements \search\components\interfaces\ISearch
{

  //Защита от перегрузки при поиске
  const MaxSearchFragments = 500;

  const PasswordLengthMin = 6;

  /**
   * @param string $className
   * @return User
   */
  public static function model($className=__CLASS__)
  {
    return parent::model($className);
  }

  public function tableName()
  {
    return 'User';
  }

  public function primaryKey()
  {
    return 'Id';
  }

  public function relations()
  {
    return array(
      'LinkEmail' => array(self::HAS_ONE, '\user\models\LinkEmail', 'UserId'),
      'LinkAddress' => array(self::HAS_ONE, '\user\models\LinkAddress', 'UserId'),
      'LinkSite' => array(self::HAS_ONE, '\user\models\LinkSite', 'UserId'),
      'LinkPhones' => array(self::HAS_MANY, '\user\models\LinkPhone', 'UserId'),
      'LinkServiceAccounts' => array(self::HAS_MANY, '\user\models\LinkServiceAccount', 'UserId'),
      'LinkProfessionalInterests' => array(self::HAS_MANY, '\user\models\LinkProfessionalInterest', 'UserId'),


      'Employments' => array(self::HAS_MANY, '\user\models\Employment', 'UserId',
        'with' => 'Company',
        'order' => '"Employments"."Primary" DESC, "Employments"."EndYear" DESC, "Employments"."StartYear" DESC'
      ),

      'Commissions' => array(self::HAS_MANY, '\commission\models\User', 'UserId', 'with' => array('Commission', 'Role')),
      'CommissionsActive' => array(self::HAS_MANY, '\commission\models\User', 'UserId', 'with' => array('Commission', 'Role'), 'on' => '"CommissionsActive"."ExitTime" IS NULL OR "CommissionsActive"."ExitTime" > NOW()'),  
        
      'Participants' => array(self::HAS_MANY, '\event\models\Participant', 'UserId'),

      'Settings' => array(self::HAS_ONE, '\user\models\Settings', 'UserId',),

    );
  }

  /**
   * @return string[]
   */
  public function getTranslationFields()
  {
    return array('LastName', 'FirstName', 'FatherName');
  }

  /**
   * @param int $runetId
   * @param bool $useAnd
   * @return User
   */
  public function byRunetId($runetId, $useAnd = true)
  {
    $criteria = new \CDbCriteria();
    $criteria->condition = '"t"."RunetId" = :RunetId';
    $criteria->params = array(':RunetId' => (int)$runetId);
    $this->getDbCriteria()->mergeWith($criteria, $useAnd);
    return $this;
  }

  /**
   * @param int[] $runetIdList
   * @param bool $useAnd
   * @return User
   */
  public function byRunetIdList($runetIdList, $useAnd = true)
  {
    $criteria = new \CDbCriteria();
    $criteria->addInCondition('"t"."RunetId"', $runetIdList);
    $this->getDbCriteria()->mergeWith($criteria, $useAnd);
    return $this;
  }

  /**
   * @param string $email
   * @param bool $useAnd
   * @return User
   */
  public function byEmail($email, $useAnd = true)
  {
    $criteria = new \CDbCriteria();
    $criteria->condition = '"t"."Email" = :Email';
    $criteria->params = array(':Email' => $email);
    $this->getDbCriteria()->mergeWith($criteria, $useAnd);
    return $this;
  }

  /**
   * @param string $startTime
   * @param string $endTime
   * @param bool $useAnd
   * @return User
   */
  public function byRegisterTime($startTime = null, $endTime = null, $useAnd = true)
  {
    if ($startTime == null && $endTime == null)
    {
      return $this;
    }
    $criteria = new \CDbCriteria();
    if ($startTime != null)
    {
      $criteria->addCondition('t.CreationTime >= :StartTime');
      $criteria->params['StartTime'] = $startTime;
    }
    if ($endTime != null)
    {
      $criteria->addCondition('t.CreationTime <= :EndTime');
      $criteria->params['EndTime'] = $endTime;
    }
    $this->getDbCriteria()->mergeWith($criteria, $useAnd);
    return $this;
  }

  /**
   * @param int $eventId
   * @param bool $useAnd
   * @return User
   */
  public function byEventId($eventId, $useAnd = true)
  {
    $criteria = new \CDbCriteria();
    $criteria->with = array('Participants' => array('together' => true));
    $criteria->addCondition('"Participants"."EventId" = :EventId');
    $criteria->params['EventId'] = $eventId;
    $this->getDbCriteria()->mergeWith($criteria, $useAnd);
    return $this;
  }

  /**
   *
   * @param bool $visible
   * @param bool $useAnd
   *
   * @return \user\models\User
   */
  public function byVisible($visible = true, $useAnd = true)
  {
    $criteria = new \CDbCriteria();
    if ($visible)
    {
      $criteria->addCondition('"t"."Visible"');
    }
    else
    {
      $criteria->addCondition('NOT "t"."Visible"');
    }
    $this->getDbCriteria()->mergeWith($criteria, $useAnd);
    return $this;
  }

  /**
   * @param string $searchTerm
   * @param string $locale
   * @param bool $useAnd
   * @param bool $useVisible
   *
   * @return User
   */
  public function bySearch($searchTerm, $locale = null, $useAnd = true, $useVisible = true)
  {
    if ($useVisible)
    {
      $this->byVisible(true);
    }

    $searchTerm = trim($searchTerm);

    if (empty($searchTerm))
    {
      $criteria = new \CDbCriteria();
      $criteria->addCondition('0=1');
      $this->getDbCriteria()->mergeWith($criteria, $useAnd);
      return $this;
    }

    if (is_numeric($searchTerm) && intval($searchTerm) != 0)
    {
      return $this->byRunetId($searchTerm, $useAnd);
    }

    $parts = preg_split('/[, .]/', $searchTerm, self::MaxSearchFragments, PREG_SPLIT_NO_EMPTY);
    if (is_numeric($parts[0]) && intval($parts[0]) != 0)
    {
      return $this->bySearchNumbers($parts, $useAnd);
    }
    else
    {
      return $this->bySearchFullName($parts, $locale, $useAnd);
    }
  }

  /**
   * @param array $numbers
   * @param bool $useAnd
   * @return User
   */
  private function bySearchNumbers($numbers, $useAnd = true)
  {
    foreach ($numbers as $key => $value)
    {
      $numbers[$key] = intval($value);
    }
    return $this->byRunetIdList($numbers, $useAnd);
  }

  /**
   * @param string[] $names
   * @param string $locale
   * @param bool $useAnd
   * @throws \application\components\Exception
   * @return User
   */
  private function bySearchFullName($names, $locale = null, $useAnd = true)
  {
    if ($locale === null || $locale == \Yii::app()->sourceLanguage)
    {
      $criteria = new \CDbCriteria();
      $size = sizeof($names);
      if ($size == 1)
      {
        $criteria->condition = '"t"."LastName" ILIKE :Part0';
        $criteria->params = array(':Part0' => \Utils::PrepareStringForLike($names[0]) . '%');
      }
      elseif ($size == 2)
      {
        $criteria->condition = '("t"."LastName" ILIKE :Part0 AND "t"."FirstName" ILIKE :Part1 OR ' .
          '"t"."LastName" ILIKE :Part1 AND "t"."FirstName" ILIKE :Part0)';
        $criteria->params = array(':Part0' => \Utils::PrepareStringForLike($names[0]) . '%',
          ':Part1' => \Utils::PrepareStringForLike($names[1]) . '%');
      }
      else
      {
        $criteria->condition = '"t"."LastName" ILIKE :Part0 AND "t"."FirstName" ILIKE :Part1 AND ' .
          '"t"."FatherName" ILIKE :Part2';
        $criteria->params = array(':Part0' => \Utils::PrepareStringForLike($names[0]) . '%',
          ':Part1' => \Utils::PrepareStringForLike($names[1]) . '%',
          ':Part2' => \Utils::PrepareStringForLike($names[2]) . '%');
      }
      $this->getDbCriteria()->mergeWith($criteria, $useAnd);
      return $this;
    }
    else
    {
      $fields = array();
      $keys = array('LastName', 'FirstName', 'FatherName');
      foreach ($keys as $key => $field)
      {
        if (isset($names[$key]))
        {
          $fields[$field] = $names[$key];
        }
      }
      return $this->byTranslationFields($locale, $fields);
    }
  }

  /**
   *
   * @param bool $notify
   *
   * @return User
   */
  public function register($notify = true)
  {
    if (empty($this->Password))
    {
      $this->Password = \Utils::GeneratePassword();
    }
    $password = $this->Password;
    $pbkdf2 = new \application\components\utility\Pbkdf2();
    $this->Password = $pbkdf2->createHash($password);
    $this->save();
    $this->refresh();

    if ($notify)
    {
      $event = new \CModelEvent($this, array('password' => $password));
      $this->onRegister($event);
    }

    return $this;
  }

  public function onRegister($event)
  {
    $mail = new \ext\mailer\PHPMailer(false);
    $mail->AddAddress($this->Email);
    $mail->SetFrom('reg@'.RUNETID_HOST, \Yii::t('app', 'RUNET-ID'), false);
    $mail->CharSet = 'utf-8';
    $mail->Subject = '=?UTF-8?B?'. base64_encode(\Yii::t('app', 'Регистрация на сайте www.runet-id.com')) .'?=';
    $mail->IsHTML(true);
    $template = !$this->Temporary ? 'user.views.mail.register' : 'user.views.mail.register-temporary';
    $mail->MsgHTML(
      \Yii::app()->controller->renderPartial($template, array('user' => $this, 'password' => $event->params['password']), true)
    );
    $mail->Send();
    
    $this->raiseEvent('onRegister', $event);
  }

  /**
   * Проверяет пароль пользователя и обновляет хэш - если хэш старого образца
   * @param string $password
   * @return bool
   */
  public function checkLogin($password)
  {
    if (empty($this->Password))
    {
      $password2 = iconv('utf-8', 'Windows-1251', $password);
      $lightHash = md5($password);
      $lightHash2 = md5($password2);
      if ($this->OldPassword == $lightHash || $this->OldPassword == $lightHash2)
      {
        $pbkdf2 = new \application\components\utility\Pbkdf2();
        $this->Password = $pbkdf2->createHash($password);
        $this->OldPassword = null;
        $this->save();
        return true;
      }
      else
      {
        return false;
      }
    }
    else
    {
      return \application\components\utility\Pbkdf2::validatePassword($password, $this->Password);
    }
  }

  /** @var Photo */
  private $photo = null;
  /**
   * @return Photo
   */
  public function getPhoto()
  {
    if ($this->photo === null)
    {
      $this->photo = new Photo($this->RunetId);
    }
    return $this->photo;
  }


  /**
   * Добавляет пользователю адрес электронной почты
   *
   * @param string $email
   * @param bool $verified
   * @return \contact\models\Email
   */
  public function setContactEmail($email, $verified = false)
  {
    $contactEmail = $this->getContactEmail();
    if (empty($contactEmail))
    {
      $contactEmail = new \contact\models\Email();
      $contactEmail->Email = $email;
      $contactEmail->Verified = $verified;
      $contactEmail->save();

      $linkEmail = new LinkEmail();
      $linkEmail->UserId = $this->Id;
      $linkEmail->EmailId = $contactEmail->Id;
      $linkEmail->save();
    }
    elseif ($contactEmail->Email != $email)
    {
      $contactEmail->Email = $email;
      $contactEmail->Verified = $verified;
      $contactEmail->save();
    }

    return $contactEmail;
  }

  /**
   * @return \contact\models\Email|null
   */
  public function getContactEmail()
  {
    return !empty($this->LinkEmail) ? $this->LinkEmail->Email : null;
  }

  /**
   * @return \contact\models\Address|null
   */
  public function getContactAddress()
  {
    return !empty($this->LinkAddress) ? $this->LinkAddress->Address : null;
  }

  /**
   * Добавляет пользователю адрес электронной почты
   *
   * @param string $url
   * @param bool $secure
   * @return \contact\models\Site
   */
  public function setContactSite($url, $secure = false)
  {
    $contactSite = $this->getContactSite();
    if (empty($contactSite))
    {
      $contactSite = new \contact\models\Site();
      $contactSite->Url = $url;
      $contactSite->Secure = $secure;
      $contactSite->save();

      $linkSite = new LinkSite();
      $linkSite->UserId = $this->Id;
      $linkSite->SiteId = $contactSite->Id;
      $linkSite->save();
    }
    elseif ($contactSite->Url != $url || $contactSite->Secure != $secure)
    {
      $contactSite->Url = $url;
      $contactSite->Secure = $secure;
      $contactSite->save();
    }

    return $contactSite;
  }

  /**
   * @return \contact\models\Site|null
   */
  public function getContactSite()
  {
    return !empty($this->LinkSite) ? $this->LinkSite->Site : null;
  }

  /**
   * @param string $type
   * @return \contact\models\Phone|null
   */
  public function getContactPhone($type = \contact\models\PhoneType::Mobile)
  {
    foreach ($this->LinkPhones as $linkPhone)
    {
      if ($linkPhone->Phone->Type == $type)
      {
        return $linkPhone->Phone;
      }
    }
    return null;
  }

  /**
   * @param string $number
   * @param string $type
   *
   * @return \contact\models\Phone|null
   */
  public function setContactPhone($number, $type = \contact\models\PhoneType::Mobile)
  {
    $isNew = false;
    $phone = $this->getContactPhone($type);
    if ($phone === null)
    {
      $phone = new \contact\models\Phone();
      $phone->Type = $type;
      $isNew = true;
    }
    $phone->parsePhone($number);
    $phone->save();

    if ($isNew)
    {
      $linkPhone = new LinkPhone();
      $linkPhone->UserId = $this->Id;
      $linkPhone->PhoneId = $phone->Id;
      $linkPhone->save();
    }

    return $phone;
  }


  /**
   * @param string $account
   * @param \contact\models\ServiceType $type
   * @return \contact\models\ServiceAccount
   */
  public function setContactServiceAccount($account, $type)
  {
    $serviceAccount = $this->getContactServiceAccount($type);
    if (empty($serviceAccount))
    {
      $serviceAccount = new \contact\models\ServiceAccount();
      $serviceAccount->TypeId = $type->Id;
      $serviceAccount->Account = $account;
      $serviceAccount->save();

      $linkServiceAccount = new LinkServiceAccount();
      $linkServiceAccount->UserId = $this->Id;
      $linkServiceAccount->ServiceAccountId = $serviceAccount->Id;
      $linkServiceAccount->save();
    }
    else
    {
      $serviceAccount->Account = $account;
      $serviceAccount->save();
    }

    return $serviceAccount;
  }

  /**
   * @param \contact\models\ServiceType $type
   * @return \contact\models\ServiceAccount|null
   */
  public function getContactServiceAccount($type)
  {
    foreach ($this->LinkServiceAccounts as $linkServiceAccount)
    {
      if ($linkServiceAccount->ServiceAccount->TypeId == $type->Id)
      {
        return $linkServiceAccount->ServiceAccount;
      }
    }
    return null;
  }

  /**
   * Возвращает основное место работы (либо последнее место работы, если по каким то причинам основное не указано)
   * @return Employment|null
   */
  public function getEmploymentPrimary()
  {
    return !empty($this->Employments) ? $this->Employments[0] : null;
  }

  /**
   * @return string
   */
  public function getFullName ()
  {
    $fullName = $this->getName();
    if ($this->getIsShowFatherName()) {
      $fullName .= ' '. $this->FatherName;
    }
    return $fullName;
  }

  /**
   * @return bool
   */
  public function getIsShowFatherName()
  {
    return !empty($this->FatherName) && ($this->Settings->HideFatherName == 0);
  }

  /**
   * @return string
   */
  public function getName()
  {
    return $this->LastName .' '. $this->FirstName;
  }
  
  /**
   * 
   * @return string
   */
  public function getShortName()
  {
    $name = $this->FirstName;
    if ($this->getIsShowFatherName())
    {
      $name .= ' '.$this->FatherName;
    }
    return $name;
  }

  /**
   * Изменяет пароль пользователю
   * @param string $password
   * @return string
   */
  public function changePassword($password = null)
  {
    if ($password == null)
    {
      $password = \Utils::GeneratePassword();
    }
    $pbkdf2 = new \application\components\utility\Pbkdf2();
    $this->Password = $pbkdf2->createHash($password);
    $this->save();
    return $password;
  }
  
  /**
   * @return string
   */
  public function getBirthDate()
  {
    if ($this->Birthday == null)
    {
      return 0;
    }
    $birthDate = new \DateTime($this->Birthday);
    $birthDateDay = $birthDate->format('j');
    $birthDateMonth = \Yii::app()->locale->getMonthName($birthDate->format('n'));
    return sprintf('%d %s', $birthDateDay, $birthDateMonth);
  }

  /**
   * Уставливает место работы пользователю
   * @param $companyFullName
   * @param string $position
   *
   * @return \user\models\Employment|null
   */
  public function setEmployment($companyFullName, $position = '')
  {
    $employment = new \user\models\Employment();
    $employment->chageCompany($companyFullName);
    $employment->UserId = $this->Id;
    $employment->Position = $position;
    $employment->Primary = true;
    $employment->save();
    return $employment;
  }

  /**
   * @return string
   */
  public function getHash()
  {
    return substr(md5($this->Id.'L2qLLQWpZWYcKbjharsx'.$this->RunetId), 0, 16);
  }
  
  public function getRecoveryHash($date = null)
  {
    if ($date == null)
    {
      $date = date('Y-m-d');
    }
    return substr(md5($date.'L2qLLQWpZWYcKbjharsx'.$this->RunetId), 0, 6);
  }

  public function checkRecoveryHash($hash)
  {
    $date1 = date('Y-m-d');
    $date2 = date('Y-m-d', time() - (24*60*60));
    if ($hash == $this->getRecoveryHash($date1)
      || $hash == $this->getRecoveryHash($date2))
    {
      return true;
    }
    return false;
  }
  
  public function getFastauthUrl($redirectUrl = '')
  {
    $params = array(
      'runetId' => $this->RunetId,
      'hash' => $this->getHash(),
    );
    if (!empty($redirectUrl))
    {
      $params['redirectUrl'] = $redirectUrl;
    }
    return \Yii::app()->createAbsoluteUrl('/main/fastauth/index', $params);
  }

  /**
   * @return string
   */
  public function getProfileUrl()
  {
    return \Yii::app()->createUrl('/user/view/index', array('runetId' => $this->RunetId));
  }

  /**
   * @param string $hash
   *
   * @return bool
   */
  public function checkHash($hash)
  {
    return $hash == $this->getHash();
  }

  public function updateLastVisit()
  {
    $this->LastVisit = date('Y-m-d H:i:s');
    $this->save();
  }

  protected function beforeSave()
  {
    if (!$this->getIsNewRecord())
    {
      $this->UpdateTime = date('Y-m-d H:i:s');
    }
    return parent::beforeSave();
  }

  
  public function getUrl()
  {
    return \Yii::app()->createAbsoluteUrl('/user/view/index', array('runetId' => $this->RunetId));
  }

}
