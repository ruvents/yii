<?php
namespace application\models\user;

/**
 * @throws Exception
 * @property int $UserId
 * @property int $RocId
 * @property string $LastName
 * @property string $FirstName
 * @property string $FatherName
 * @property int $Sex
 * @property string $Birthday
 * @property string $Password
 * @property string $Email
 * @property int $CreationTime
 * @property int $UpdateTime
 * @property int $LastVisit
 * @property string $Referral
 *
 * @property ContactAddress[] $Addresses
 * @property ContactPhone[] $Phones
 * @property ContactSite[] $Sites
 * @property ContactServiceAccount[] $ServiceAccounts
 * @property ContactEmail[] $Emails
 *
 * @property EventUser[] $EventUsers
 * @property Event[] $Events
 * @property EventSubscription[] $EventSubscriptions
 * @property EventProgramHereService $EventProgramHere Функционал мобильной версии, позволяет пользователю отметиться на секции
 * @property EventProgramUserLink[] $EventProgramUserLink
 *
 * @property UserSettings $Settings Настройки аккаунта пользователя
 * @property UserConnect[] $Connects
 *
 * @property CoreGroup[] $Groups
 *
 * @property UserEmployment[] Employments
 */
class User extends \CActiveRecord
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
		return 'UserId';
  }

  public function relations()
	{
		return array(
		//Contacts
			'Addresses' => array(self::MANY_MANY, 'ContactAddress', 'Link_User_ContactAddress(UserId, AddressId)',
				'with' => array('City')),
			'Phones' => array(self::MANY_MANY, 'ContactPhone', 'Link_User_ContactPhone(UserId, PhoneId)'),
			'ServiceAccounts' => array(self::MANY_MANY, 'ContactServiceAccount', 'Link_User_ContactServiceAccount(UserId, ServiceId)',
				'with' => array('ServiceType')),
			'Sites' => array(self::MANY_MANY, 'ContactSite', 'Link_User_ContactSite(UserId, SiteId)'),
			'Emails' => array(self::MANY_MANY, 'ContactEmail', 'Link_User_ContactEmail(UserId, EmailId)'),
		//Event  
			'EventUsers' => array(self::HAS_MANY, 'EventUser', 'UserId'),//, 'with' => array('Event', 'EventRole')),
			'Events' => array(self::MANY_MANY, 'Event', 'EventUser(UserId, EventId)'),
			'EventSubscriptions' => array(self::HAS_MANY, 'EventSubscription', 'UserId', 'with' => array('Event')),
			'EventProgramHere' => array(self::HAS_ONE, 'EventProgramHereService', 'UserId',),
      'EventProgramUserLink' =>array(self::HAS_MANY, 'EventProgramUserLink', 'UserId', 'with' => array('EventProgram', 'Role')),
		//User  
			'Activities' => array(self::HAS_MANY, 'UserActivity', 'UserId'),
			'Employments' => array(self::HAS_MANY, 'UserEmployment', 'UserId', 'with' => 'Company' ,
				'order' => 'Employments.Primary DESC, Employments.FinishWorking DESC, Employments.StartWorking DESC'),
			'Settings' => array(self::HAS_ONE, '\user\models\Settings', 'UserId',),
			'Connects' => array(self::HAS_MANY, 'UserConnect', 'UserId'),
			//'InterestPersons' => array(self::HAS_MANY, 'UserInterestPerson', 'UserId', 'with' => array('InterestPerson')),   
			'InterestPersons' => array(self::MANY_MANY, 'User', 'UserInterestPerson(UserId, InterestPersonId)'),
			'MobilePassword' => array(self::HAS_ONE, 'UserMobilePassword', 'UserId',),

    //Access
      'Groups' => array(self::MANY_MANY, 'CoreGroup', 'Core_Link_UserGroup(UserId, GroupId)', 'with' => array('Masks')),
		);
	}

//   Не подтвердил свою полезность, проще в каждом конкретном случае описывать 
//   дерево необходимых классов  
//  /**
//  * Возвращает требуемую глубину загрузки пользователя.
//  * В зависимости от глубины, определяется объем данных, загружаемых "за раз".
//  * В случае LoadOnlyUser - все данные кроме данных таблицы User получаются с помощью ленивой подгрузки
//  * 
//  * @param int $loadingDepth
//  * @return User Модель пользователя, для последующего обращения к БД.
//  */
//  protected static function GetLoadingDepth($loadingDepth)
//  {    
//    switch ($loadingDepth)
//    {  
//      case self::LoadFullInfo:
//        $user = User::model()->with('Addresses', 'Phones', 'ServiceAccounts', 
//          'Sites', 'EventUsers', 'Activities', 'Employments')->together();
//        return $user;
//      case self::LoadOnlyUser:
//      default:
//        $user = User::model();
//        return $user;
//    }
//  }

  /**
   * @static
   * @param int $userId
   * @param array $loadWith
   * @return User
   */
  public static function GetById($userId, $loadWith = array())
	{
    $with = array_merge(array('Settings'), $loadWith);
		$user = User::model()->with($with);
		return $user->findByPk($userId);
	}
	
	/**
	* Загружает пользователя по заданному rocId.
	* 
	* @param int $rocid
	* @param array[string] $loadWith Массив сущностей, которые должны загружаться одним запросом вместе с User
	* @return User
	*/
	public static function GetByRocid($rocId, $loadWith = array())
	{        
		$user = User::model()->with($loadWith);//->together();
		$criteria = new CDbCriteria();
		$criteria->condition = 't.RocId = :RocId';
		$criteria->params = array(':RocId' => $rocId);
		return $user->find($criteria);
	}

  /**
   * @static
   * @param string $email
   * @param array $loadWith
   * @return User|null
   */
  public static function GetByEmail($email, $loadWith = array())
  {
    $user = User::model()->with($loadWith);
		$criteria = new CDbCriteria();
		$criteria->condition = 't.Email = :Email';
		$criteria->params = array(':Email' => $email);
		return $user->find($criteria);
  }

  /**
   * @static
   * @param  $start
   * @param null $end
   * @return User[]
   */
  public static function GetByRegisterTime($start, $end = null)
  {
    if ($end === null)
    {
      $end = time();
    }
    $model = User::model();

    $criteria = new CDbCriteria();
    $criteria->condition = 't.CreationTime > :Start AND t.CreationTime < :End';
    $criteria->params = array(':Start' => $start, ':End' => $end);

    return $model->findAll($criteria);
  }
	
	/**
	* Загружает пользователей по заданному первому символу фамилии, региону и номеру страницы индексации
	* 
	* @param string $letter
	* @param string $location
	* @param int $currentPage
	* @return array[mixed] Возвращает ассоциативный массив, users - пользователи текущей страницы, count - суммарное количество пользователей
	*/
	public static function GetUserListByLocation($letter, $location, $currentPage = 1)
	{
		$currentPage = max(1, $currentPage);
		$userPerPage = SettingManager::GetSetting('UserPerPage');
		
		$with = array('Addresses.City');//, 'Employments.Company');
		
		$user = User::model()->with($with)->together();

		$criteria = new CDbCriteria();
		$criteria->condition = '';
		$criteria->params = array();
		if ($location['city'] != 0)
		{
  		$criteria->condition =  'Addresses.CityId = :CityId';
			$criteria->params = array(':CityId' => $location['city']);
		}
		else if ($location['region'] != 0)
		{
			$criteria->condition =  'City.RegionId = :RegionId';
			$criteria->params = array(':RegionId' => $location['region']);
		}
		else if ($location['country'] != 0)
		{
			$criteria->condition = 'City.CountryId = :CountryId';
			$criteria->params = array(':CountryId' => $location['country']);      
		}
		
		if ($letter != 'all')
		{
			$criteria->condition .= ! empty($criteria->condition) ? ' AND ' : ' ';
			$criteria->condition .= 't.LastName LIKE :Letter';
			$criteria->params[':Letter'] = $letter . '%';
		}
		$criteria->limit = $userPerPage;    
		
		$count = $user->count($criteria);
		
		$criteria->offset = ($currentPage - 1) * $userPerPage;
		$criteria->order = 'LastName, FirstName';
		$users = $user->findAll($criteria);
		
		$userListId = array();
		foreach ($users as $u)
		{
			$userListId[] = $u->GetUserId();
		}
		
		$result = array();
		$result['users'] = array();
		$result['count'] = $count;
		if (! empty($userListId))
		{
			$with = array('Addresses.City.Country', 'Employments.Company');
			$user = User::model()->with($with)->together();
			$criteria = new CDbCriteria();
			$criteria->condition =  't.UserId IN (' . implode(',', $userListId) . ')';
			$criteria->order = 'LastName, FirstName';
			$result['users'] = $user->findAll($criteria);
		}
		
		return $result;
	}
	
	/**
	* Загружает пользователей по заданному первому символу фамилии, региону и номеру страницы индексации
	* 
	* @param string $nameSeq
	* @param int $eventId
	* @param int $currentPage
	* @return array[mixed] Возвращает ассоциативный массив, users - пользователи текущей страницы, count - суммарное количество пользователей
	*/
	public static function GetUserListByEvent($nameSeq, $eventId, $currentPage = 1)
	{
		$currentPage = max(1, $currentPage);
		$userPerPage = SettingManager::GetSetting('UserPerPage');
		
		$with = array('EventUsers', 'Settings');
		
		$user = User::model()->with($with)->together();

		$criteria = new CDbCriteria();

    $criteria->condition = 'Settings.Visible = \'1\' AND EventUsers.EventId = :EventId';
		$criteria->params = array(':EventId' => $eventId);    
		
		if (! empty($nameSeq))
		{
			$criteria->condition .= ! empty($criteria->condition) ? ' AND ' : ' ';
			
			$parts = preg_split('/[, .]/', $nameSeq, -1, PREG_SPLIT_NO_EMPTY);
			
			$size = sizeof($parts);
			if ($size > self::MaxSearchFragments)
			{
				throw new Exception('Попытка найти слишком много фрагментов RocId');
			}
			if (is_numeric($parts[0]))
			{
				for ($i = 0; $i < $size; $i++)
				{
					if (! is_numeric($parts[$i]))
					{
						unset($parts[$i]);
					}
				}
				$data = Lib::TransformDataArray($parts);
				$criteria->condition .= 't.RocId IN (' . implode(',', $data[0]) . ')';
				$criteria->params = array_merge($criteria->params, $data[1]);
			}
			else
			{
				if ($size == 1)
				{
					$criteria->condition .= 't.LastName LIKE :Part0';
					$criteria->params[':Part0'] = Lib::PrepareSqlStringForLike($parts[0]) . '%';
				} 
				elseif ($size == 2)
				{          
					$criteria->condition .= '(t.LastName LIKE :Part0 AND t.FirstName LIKE :Part1 OR ' .
						't.LastName LIKE :Part1 AND t.FirstName LIKE :Part0)';
					$criteria->params[':Part0'] = Lib::PrepareSqlStringForLike($parts[0]) . '%';
					$criteria->params[':Part1'] = Lib::PrepareSqlStringForLike($parts[1]) . '%';          
				}
				else
				{
					$criteria->condition .= 't.LastName LIKE :Part0 AND t.FirstName LIKE :Part1 AND ' .
						't.FatherName LIKE :Part2';
					$criteria->params[':Part0'] = Lib::PrepareSqlStringForLike($parts[0]) . '%';
					$criteria->params[':Part1'] = Lib::PrepareSqlStringForLike($parts[1]) . '%'; 
					$criteria->params[':Part2'] = Lib::PrepareSqlStringForLike($parts[2]) . '%';
				}
			}      
		}
		
		$count = $user->count($criteria);

    $user = User::model()->with($with)->together();
    $criteria->limit = $userPerPage;
		$criteria->offset = ($currentPage - 1) * $userPerPage;
		$criteria->order = 't.LastName, t.FirstName';
		$users = $user->findAll($criteria);

		
		$userListId = array();
		foreach ($users as $u)
		{
			$userListId[] = $u->GetUserId();
		}
		
		$result = array();
		$result['users'] = array();
		$result['count'] = $count;
		if (! empty($userListId))
		{
			$with = array('Addresses.City.Country', 'Employments.Company');
			$user = User::model()->with($with);
			$criteria = new CDbCriteria();
			$criteria->condition =  't.UserId IN (' . implode(',', $userListId) . ')';
			$criteria->order = 'LastName, FirstName';
			$result['users'] = $user->findAll($criteria);
		}
		
		return $result;
	}


	public static $GetBySearchCount = 0;
	/**
	 * @static
	 * @param string $searchTerm
	 * @param int $count
	 * @param int $page
	 * @param bool $calcCount
	 * @param bool $onlyCount
	 * @return User[]
	 */
	public static function GetBySearch($searchTerm, $count = 20, $page = 1,
		$calcCount = false, $onlyCount = false)
	{
		$user = User::model()->with('Settings');

		$criteria = self::GetSearchCriteria($searchTerm);
		if ($calcCount || $onlyCount)
		{
			self::$GetBySearchCount = $user->count($criteria);
			if (self::$GetBySearchCount == 0 || $onlyCount)
			{
				return array();
			}
		}
		$user = $user->with('Employments.Company', 'Settings');//->together();
		$criteria->offset = ($page - 1) * $count;
		$criteria->limit = $count;
		return $user->findAll($criteria);
	}
	
	/**
	* Создает CDbCriteria для осуществления поиска по заданным параметрам
	* 
	* @param string $searchTerm
	* @param string $sortBy
	* @return CDbCriteria|null
	*/
	public static function GetSearchCriteria($searchTerm, $sortBy = '')
	{
		$searchTerm = trim($searchTerm);
		if (empty($searchTerm))
		{
			return null;
		}
		$criteria = new CDbCriteria();
		if (is_numeric($searchTerm))
		{
			$rocId = intval($searchTerm);
			$criteria->condition = 't.RocId = :RocId';
			$criteria->params = array(':RocId' => $rocId);
		}
		else
		{
			$parts = preg_split('/[, .]/', $searchTerm, -1, PREG_SPLIT_NO_EMPTY);
			$size = sizeof($parts);
			if ($size > self::MaxSearchFragments)
			{
				throw new Exception('Попытка найти слишком много фрагментов RocId');
			}
			if (is_numeric($parts[0]))
			{
				for ($i = 0; $i < $size; $i++)
				{
					if (! is_numeric($parts[$i]))
					{
						unset($parts[$i]);
					}
					else
					{
						$parts[$i] = intval($parts[$i]);
					}
				}
				$data = Lib::TransformDataArray($parts);
				$criteria->condition = 't.RocId IN (' . implode(',', $data[0]) . ')';
				$criteria->params = $data[1];
			}
			else
			{
				if ($size == 1)
				{
					$criteria->condition = 't.LastName LIKE :Part0';
					$criteria->params = array(':Part0' => Lib::PrepareSqlStringForLike($parts[0]) . '%');
				} 
				elseif ($size == 2)
				{          
					$criteria->condition = '(t.LastName LIKE :Part0 AND t.FirstName LIKE :Part1 OR ' .
						't.LastName LIKE :Part1 AND t.FirstName LIKE :Part0)';
					$criteria->params = array(':Part0' => Lib::PrepareSqlStringForLike($parts[0]) . '%',
						':Part1' => Lib::PrepareSqlStringForLike($parts[1]) . '%');          
				}
				else
				{
					$criteria->condition = 't.LastName LIKE :Part0 AND t.FirstName LIKE :Part1 AND ' .
					  't.FatherName LIKE :Part2';
					$criteria->params = array(':Part0' => Lib::PrepareSqlStringForLike($parts[0]) . '%',
						':Part1' => Lib::PrepareSqlStringForLike($parts[1]) . '%',
						':Part2' => Lib::PrepareSqlStringForLike($parts[2]) . '%');
				}
			}
		}
		$criteria->condition .= ' AND Settings.Visible = \'1\'';
    //$criteria->params[':Visible'] = 1;
		$criteria->order = 't.LastName DESC, t.FirstName DESC, t.FatherName DESC, t.RocId DESC ';

		return $criteria;
	}
	
	/**
	* put your comment there...
	* 
	* @param string $email
	* @param string $password
	* @return User
	*/
	public static function Register($email, $password)
	{
		//$countEM = ContactEmail::GetCountEmails($email);
    $userByEmail = User::GetByEmail($email);
		if ($userByEmail == null)
		{
			$db = Registry::GetDb();
			$sql = "SELECT MAX(`RocId`) as MaxRocId FROM " . self::$TableName . " WHERE 1";
			$row = $db->createCommand($sql)->queryRow();
			if (isset($row['MaxRocId']))
			{
				$rocId = intval($row['MaxRocId']) + 1;
				$user = new User();
				$user->SetRocId($rocId);
				$user->SetPassword(self::GetPasswordHash($password));
				$user->SetCreationTime(time());
				$user->SetLastVisit(time());
				$user->SetUpdateTime(time());
				$user->Email = $email;
				$user->save();
				
				$user->CreateSettings();
				$user->AddEmail($email, 1);

        self::SendRegisterEmail($email, $rocId, $password);

				return $user;
			}
		}
		
		return null;
	}

  public static function SendRegisterEmail($email, $rocID, $password)
  {
    AutoLoader::Import('library.mail.*');

    $view = new View();
    $view->SetTemplate('regmail', 'core', 'general', '', 'public');
    $view->Email = $email;
    $view->RocID = $rocID;
    $view->Password = $password;
    $view->Host = $_SERVER['HTTP_HOST'];

    $mail = new PHPMailer(false);

    $mail->AddAddress($email);
    $mail->SetFrom('register@rocid.ru', 'rocID', false);
    $mail->CharSet = 'utf-8';
    $subject = Registry::GetWord('mail');
    $subject = (string)$subject['regsubject'];
    $mail->Subject = '=?UTF-8?B?'. base64_encode($subject) .'?=';
    $mail->AltBody = 'Для просмотра этого сообщения необходимо использовать клиент, поддерживающий HTML';
    $mail->MsgHTML($view);
    $mail->Send();
  }
	
	/**
	* Генерирует хеш-значение для проверки целостности cookie
	* 
	* @param string $userId
	* @return string
	*/
	public static function GenerateCookieHash($userId)
	{
		return md5($userId . microtime());
	}

  /**
   * Генерирует хеш-значение для хранения пароля
   * @static
   * @param  $password
   * @return string
   */
  public static function GetPasswordHash($password)
  {
    return md5($password);
  }
	
	/**
	* Проверяет, соответствуют ли rocId и password данному пользователю
	* 
	* @param string $rocId
	* @param string $password
	* @return bool
	*/
	public function CheckLogin($rocId, $password)
	{
    $password2 = iconv('utf-8', 'Windows-1251', $password);

    if ($this->RocId !== $rocId)
    {
      return false;
    }

    if ($this->Password === self::GetPasswordHash($password))
    {
      return true;
    }

    if ($this->Password === self::GetPasswordHash($password2))
    {
      $this->Password = self::GetPasswordHash($password);
      $this->save();
      return true;
    }

		return false;
	}

  /**
   * @param $hash Хэш пароля
   * @param $hash2 Хэш пароля в кодировке cp1251
   * @return bool
   */
  public function CheckLoginByHash($hash, $hash2)
  {
    if ($this->Password === $hash)
    {
      return true;
    }

    if ($this->Password === $hash2)
    {
      $this->Password = $hash;
      $this->save();
      return true;
    }

    return false;
  }

  /**
   * @return void
   */
  public function CreateSecretKey()
  {
    $key = User::GenerateCookieHash($this->UserId);
    $session = Registry::GetSession();
    $session->add('rocid_hash', $key);
    $cookie = new CHttpCookie('rocid_hash', $key);
    $cookie->expire = time() + GeneralIdentity::GetExpire();
    Cookie::Set($cookie);
  }

  public function CheckSecretKey()
  {
    $cookieKey = Cookie::Get('rocid_hash');
    $sessionKey = Registry::GetSession()->get('rocid_hash');
    if ( $cookieKey  == $sessionKey)
    {
      $cookie = new CHttpCookie('rocid_hash', $cookieKey);
      $cookie->expire = time() + GeneralIdentity::GetExpire();
      Cookie::Set($cookie);
      return true;
    }
    return false;
  }
	
	/**
	* Разлогинивает данного пользователя
	* 
	*/
//	public function Logout()
//	{
//		Cookie::ResetCookie('RocId');
//		Cookie::ResetCookie('Password');
//		Cookie::ResetCookie('Hash');
//
//		$session = Session::GetInstance();
//		$session->Delete();
//	}
	
	/**
	* Проверяет, есть ли у данного пользователя админ доступ
	* 
	*/
	public function IsHaveAdminPermissions()
	{
		$adminIds = array(337, 454, 12953, 12959, 35287, 15648, 39948, 16670);
		if (in_array($this->GetRocId(), $adminIds))
		{
			return true;
		}
		return false;
	}

  /**
   * @return bool
   */
  public function CheckAccess()
  {
    $userGroup = CoreGroup::GetById(CoreGroup::UserGroupId);
    if (! empty($userGroup) && $userGroup->CheckAccess())
    {
      return true;
    }
    foreach ($this->Groups as $group)
    {
      if ($group->CheckAccess())
      {
        return true;
      }
    }
    return false;
  }
	
	/**
	 * @param bool $onServerDisk
	 * @return string
	 */
	public function GetPhotoDir($onServerDisc = false)
	{
		$rocId = $this->GetRocId();
		$folder = $rocId / 10000;
		$folder = (int)$folder;
		$result = Registry::GetVariable('UserPhotoDir') . $folder . '/';
		if ($onServerDisc)
		{
			$result = $_SERVER['DOCUMENT_ROOT'] . $result;
		}
		return $result;
	}
	
	/**
	* Возвращает путь к мини изображению пользователя, для шапки сайта, отображения в компаниях и тп
	* @return string
	*/
	public function GetMiniPhoto()
	{    
		$rocId = $this->GetRocId();
		$folder = $this->GetPhotoDir();
		$photo = $rocId . '_50.jpg';
		if (file_exists(Registry::GetVariable('PublicPath') . $folder . $photo))
		{
			return $folder . $photo;
		}
		else
		{
			return Registry::GetVariable('UserPhotoDir') . 'nophoto_50.png';
		}
	}

	/**
	* Возвращает путь к мини изображению пользователя, для шапки сайта, отображения в компаниях и тп
	* @return string
	*/
	public function GetMediumPhoto()
	{
		$rocId = $this->GetRocId();
		$folder = $this->GetPhotoDir();
		$photo = $rocId . '_90.jpg';
		if (file_exists(Registry::GetVariable('PublicPath') . $folder . $photo))
		{
			return $folder . $photo;
		}
		else
		{
			return Registry::GetVariable('UserPhotoDir') . 'nophoto_90.png';
		}
	}

	/**
	* Возвращает путь к изображению пользователя для профиля и тп
	* @return string
	*/  
	public function GetPhoto()
	{
		$rocId = $this->GetRocId();
		$folder = $this->GetPhotoDir();
		$photo = $rocId . '_200.jpg';
		if (file_exists(Registry::GetVariable('PublicPath') . $folder . $photo))
		{
			return $folder . $photo;
		}
		else
		{
			return Registry::GetVariable('UserPhotoDir') . 'nophoto_200.png';
		}
	}

	/**
	 * @param  $data
	 * @return void
	 */
	public function SavePhoto($image)
	{
		$tmpName = DIRECTORY_SEPARATOR . 'tmp' . DIRECTORY_SEPARATOR .
											 md5('user' . microtime()) . '.jpg';
		file_put_contents($tmpName, $image);

		$path = $this->GetPhotoDir(true);
		if (! is_dir($path))
		{
			mkdir($path);
		}

		$img = Graphics::GetImage($tmpName);

		$namePrefix = $this->RocId;
		$clearSaveTo = $path . $namePrefix . '_clear.jpg';
		imagejpeg($img, $clearSaveTo, 100);
		$newImage = $path . $namePrefix . '.jpg';
		imagejpeg($img, $newImage, 100);
		imagedestroy($img);
		$newImage = $path . $namePrefix . '_200.jpg';
		Graphics::ResizeAndSave($clearSaveTo, $newImage, 200, 0, array('x1'=>0, 'y1'=>0));
		$newImage = $path . $namePrefix . '_90.jpg';
		Graphics::ResizeAndSave($clearSaveTo, $newImage, 90, 90, array('x1'=>0, 'y1'=>0));
		$newImage = $path . $namePrefix . '_50.jpg';
		Graphics::ResizeAndSave($clearSaveTo, $newImage, 50, 50, array('x1'=>0, 'y1'=>0));
	}
	
	/**
	* Добавляет пользователю адрес электронной почты
	* 
	* @param string $email
	*/
	public function AddEmail($email, $primary = 0, $personal = 0)
	{
		$contactEmail = new ContactEmail();
		$contactEmail->Email = $email;
		$contactEmail->Primary = $primary;
		$contactEmail->IsPersonal = $personal;
		$contactEmail->save();
		
		$db = Registry::GetDb();
		$sql = 'INSERT INTO Link_User_ContactEmail ( UserId , EmailId) VALUES (' . $this->UserId . ',' . $contactEmail->EmailId . ')';
		$db->createCommand($sql)->execute();
	}

	/**
	 * @param ContactSite $site
	 * @return void
	 */
	public function AddSite($site)
	{
		$db = Registry::GetDb();
		$sql = 'INSERT INTO Link_User_ContactSite ( UserId , SiteId) VALUES (' . $this->UserId . ',' . $site->SiteId . ')';
		$db->createCommand($sql)->execute();
	}

	/**
	 * @param ContactAddress $address
	 * @return void
	 */
	public function AddAddress($address)
	{
		$db = Registry::GetDb();
		$sql = 'INSERT INTO Link_User_ContactAddress ( UserId , AddressId) VALUES (' . $this->UserId . ',' . $address->AddressId . ')';
		$db->createCommand($sql)->execute();
	}

	/**
	 * @param ContactPhone $phone
	 * @return void
	 */
	public function AddPhone($phone)
	{
		$db = Registry::GetDb();
		$sql = 'INSERT INTO Link_User_ContactPhone ( UserId , PhoneId) VALUES (' . $this->UserId . ',' . $phone->PhoneId . ')';
		$db->createCommand($sql)->execute();
	}

	/**
	 * @param ContactServiceAccount $serviceAccount
	 * @return void
	 */
	public function AddServiceAccount($serviceAccount)
	{
		$db = Registry::GetDb();
		$sql = 'INSERT INTO Link_User_ContactServiceAccount ( UserId , ServiceId) VALUES (' . $this->UserId . ',' . $serviceAccount->ServiceId . ')';
		$db->createCommand($sql)->execute();
	}

	/**
	 * @param int|ContactSite $site
	 * @return void
	 */
	public function DeleteSite($site)
	{
		if (is_object($site))
		{
			$site = $site->SiteId;
		}
		$db = Registry::GetDb();
		$sql = 'DELETE FROM Link_User_ContactSite WHERE UserId = ' . $this->UserId .' AND SiteId = ' . $site;
		$db->createCommand($sql)->execute();
	}

	/**
	 * @param int|ContactPhone $phone
	 * @return void
	 */
	public function DeletePhone($phone)
	{
		if (is_object($phone))
		{
			$phone = $phone->PhoneId;
		}
		$db = Registry::GetDb();
		$sql = 'DELETE FROM Link_User_ContactPhone WHERE UserId = ' . $this->UserId .' AND PhoneId = ' . $phone;
		$db->createCommand($sql)->execute();
	}

	/**
	 * @param int|ContactServiceAccount $serviceAccount
	 * @return void
	 */
	public function DeleteServiceAccount($serviceAccount)
	{
		if (is_object($serviceAccount))
		{
			$serviceAccount = $serviceAccount->ServiceId;
		}
		$db = Registry::GetDb();
		$sql = 'DELETE FROM Link_User_ContactServiceAccount WHERE UserId = ' . $this->UserId .' AND ServiceId = ' . $serviceAccount;
		$db->createCommand($sql)->execute();
	}
	
	/**
	* Создает настройки пользователя, либо возвращает имеющиеся - если они уже существуют
	* @return UserSettings
	*/
	public function CreateSettings()
	{
		$settings = $this->Settings;
		if ($settings == null)
		{
			$settings = new UserSettings();
			$settings->UserId = $this->GetUserId();
			$settings->Visible = 1;
			$settings->save();
      $this->Settings = $settings;
		}
		return $settings;
	}

  /**
   * Обновляет последнее посещение пользователя
   * @return void
   */
  public function UpdateLastVisit()
  {
    $db = Registry::GetDb();
    $db->createCommand()->update(self::$TableName, array('LastVisit' => time()), 'UserId = :UserId', array(':UserId' => $this->UserId));
  }
	
	/**
	* Проверяет, зарегистрирован ли пользователь на мероприятии с таким Id
	* 
	* @param int $eventId
	* @return boolean
	*/
	public function IsRegisterOnEvent($eventId)
	{
		$eventUser = EventUser::model();
		$criteria = new CDbCriteria();
		$criteria->condition = 't.UserId = :UserId AND t.EventId = :EventId';
		$criteria->params = array(':UserId' => $this->UserId, ':EventId' => $eventId);
		$count = $eventUser->count($criteria);
		return $count > 0;
	}
	
	/**
	* Возвращает всех интересующих персон данного пользователя
	* 
	* @param $withParam
	* @return array[User]
	*/
	public function GetInterestPersonsWith($withParam)
	{
		return $this->InterestPersons(array('with' => $withParam));
		//return array();
	}

  public function Hide()
  {
    $this->Settings->Visible = 0;
    $this->Settings->save();
  }
		
	/**
	* Геттеры и сеттеры для полей
	*/
	//UserId
	public function GetUserId()
	{
		return $this->UserId;;
	}  
	//Rocid
	public function GetRocId()
	{
		return $this->RocId;
	}
	
	public function SetRocId($value)
	{
		$this->RocId = $value;
	}
	//LastCompanyId
	public function GetLastCompanyId()
	{
		return $this->LastCompanyId;
	}
	
	public function SetLastCompanyId($value)
	{
		$this->LastCompanyId = $value;
	}
	//LastName
	public function GetLastName()
	{
		return $this->LastName;
	}
	
	public function SetLastName($value)
	{
		$this->LastName = $value;
	}
	//FirstName
	public function GetFirstName()
	{
		return $this->FirstName;
	}
	
	public function SetFirstName($value)
	{
		$this->FirstName = $value;
	}
	//FatherName
	public function GetFatherName()
	{
		return $this->FatherName;
	}
	
	public function SetFatherName($value)
	{
		$this->FatherName = $value;
	}
	//Sex
	public function GetSex()
	{
		return $this->Sex;
	}
	
	public function SetSex($value)
	{
		$this->Sex = $value;
	}
	//Birthday
	public function GetBirthday()
	{
		return $this->Birthday;
	}
	
	/**
	* Возвращает ассоциативный массив с полями day, month, year
	* 
	* @return array
	*/
	public function GetParsedBirthday()
	{
		$birthday = $this->Birthday;
		$result = array();

		$result['year'] = intval(substr($birthday, 0, 4));
		$result['month'] = intval(substr($birthday, 5, 2));
		$result['day'] = intval(substr($birthday, 8, 2));

		return $result;

		/*if (substr($birthday, 0, 4) != '0000')
		{
			$result['year'] = intval(substr($birthday, 0, 4));
		}
		if (substr($birthday, 5, 2) != '00')
		{
			$result['month'] = intval(substr($birthday, 5, 2));
		}
		if (substr($birthday, 8, 2) != '00')
		{
			$result['day'] = intval(substr($birthday, 8, 2));
		}
		return $result;*/
	}

	/**
	 * Устанавливает корректную дату рождения из массива day, month, year
	 * @param array $date
	 * @return void
	 */
	public function SetParsedBirthday($date)
	{
		if (empty($date['year']) || intval($date['year']) == 0)
		{
			$birthday = '0000';
		}
		else
		{
			$birthday = $date['year'];
		}
		$birthday .= '-';
		if (empty($date['month']) || intval($date['month']) == 0)
		{
			$birthday .= '00';
		}
		else
		{
			$birthday .= $date['month'];
		}
		$birthday .= '-';
		if (empty($date['day']) || intval($date['day']) == 0)
		{
			$birthday .= '00';
		}
		else
		{
			$birthday .= $date['day'];
		}
		$this->Birthday = $birthday;
	}
	
	public function SetBirthday($value)
	{
		$this->Birthday = $value;
	}
	//Password
	public function GetPassword()
	{
		return $this->Password;
	}
	
	public function SetPassword($value)
	{
		$this->Password = $value;
	}
	//CreationTime
	public function GetCreationTime()
	{
		return $this->CreationTime;
	}
	
	public function SetCreationTime($value)
	{
		$this->CreationTime = $value;
	}
	//UpdateTime
	public function GetUpdateTime()
	{
		return $this->UpdateTime;
	}
	
	public function SetUpdateTime($value)
	{
		$this->UpdateTime = $value;
	}
	//LastVisit
	public function GetLastVisit()
	{
		return $this->LastVisit;
	}
	
	public function SetLastVisit($value)
	{
		$this->LastVisit = $value;
	}
	//Referral
	public function GetReferral()
	{
		return $this->Referral;
	}
	
	public function SetReferral($value)
	{
		$this->Referral = $value;
	}
	
	/**
	* @return ContactAddress
	*/
	public function GetAddress()
	{
		
		$addresses = $this->Addresses;    
		if (isset($addresses[0]))
		{
			return $addresses[0];
		}
		else
		{
			return null;
		}
	}
	
	/**
	* @return ContactSite
	*/
	public function GetSite()
	{
		$sites = $this->Sites;
		if (isset($sites[0]))
		{
			return $sites[0];
		}
		else
		{
			return null;
		}
	}
	
	/**
	* @return ContactEmail
	*/
	public function GetEmail()
	{
		$emails = $this->Emails;    
		if (isset($emails[0]))
		{
			return $emails[0];
		}
		else
		{
			return null;
		}
	}
	
	/**
	* @return ContactServiceAccount[]
	*/
	public function GetServiceAccounts()
	{
		return $this->ServiceAccounts;
	}

	/**
	 * @return ContactPhone[]
	 */
	public function GetPhones()
	{
		return $this->Phones;
	}

	/**
	* @return UserEmployment[]
	*/
	public function GetEmployments()
	{
		return $this->Employments;
	}
	
	/**
	* @return UserActivity[]
	*/
	public function GetActivities()
	{
		return $this->Activities;
	}
	
	/**
	* @return EventUser[]
	*/
	public function GetEventUsers()
	{
		return $this->EventUsers;
	}
	
	/**
	* @return array[Event]
	*/
	public function GetEvents()
	{
		return $this->Events;
	}
	
	/**
	* Возвращает массив с элементами EventSubscription, Event 
	* загружается автоматически с использованием жадной загрузки
	* 
	* @return array[EventSubscription]
	*/
	public function GetEventSubscriptions()
	{
		return $this->EventSubscriptions;
	}
	
	/**
	* @return UserSettings
	*/  
	public function GetSetting()
	{
		return $this->Setting();
	}
	
	/**
	* @return array[UserInterestPerson] 
	*/
	public function GetInterestPersons()
	{
		return $this->InterestPersons;
	}
	
	/**
	* @return EventProgramHere
	*/
	public function GetEventProgramHere()
	{
		return $this->EventProgramHere;
	}
	
	/**
	* @return UserMobilePassword
	*/
	public function GetMobilePassword()
	{
		return $this->MobilePassword;
	}

  /**
   * Возвращает основное место работы (либо последнее место работы, если по каким то причинам основное не указано)
   * @return UserEmployment|null
   */
  public function EmploymentPrimary()
  {
    return !empty($this->Employments) ? $this->Employments[0] : null;
  }
}