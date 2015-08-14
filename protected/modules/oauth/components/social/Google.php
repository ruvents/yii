<?php
namespace oauth\components\social;

class Google implements ISocial
{
  const ClientId = '467484783673-m9sim31n4l1f746irq3e7ivfjikt9fi1.apps.googleusercontent.com';
  const ClientSecret = 'WoRL8MaNzqAiaa6jXCqwDoyE';

  protected $redirectUrl;
  public function __construct($redirectUrl = null)
  {
    $this->redirectUrl = $redirectUrl;
  }
  
  public function getOAuthUrl()
  {    
    $params = [
      'client_id' => self::ClientId,
      'response_type' => 'code',
      'scope' => 'email profile',
    ];
    $returnUrlParams = [
        'social' => $this->getSocialId(),
        'url' => ''
    ];
    \Yii::app()->getController()->isFrame() ? $returnUrlParams['frame'] = 'true' : '';
    $params['redirect_uri'] = $this->redirectUrl == null ? \Yii::app()->getController()->createAbsoluteUrl('/oauth/social/connect', $returnUrlParams) : $this->redirectUrl;
    return 'https://accounts.google.com/o/oauth2/auth?'.  http_build_query($params);
  }
  
  public function getSocialId()
  {
    return self::Google;
  }
  
  public function getData()
  {
    $accessToken = $this->getAccessToken();  
    $params = [
      'access_token' => $accessToken->access_token
    ];
    $response = $this->makeRequest('https://www.googleapis.com/oauth2/v3/userinfo?'.http_build_query($params));
    if (isset($response->error)) {
      throw new \CHttpException(400, 'Сервис авторизации Google Accounts не отвечает');
    }    
    $data = new Data();
    $data->Hash = $response->sub;
    $data->LastName = $response->family_name;
    $data->FirstName = $response->given_name;
    $data->Email = $response->email;
    return $data;
  }
  
  public function getSocialTitle()
  {
    return 'Google Accounts';
  }
  
  public function isHasAccess()
  {
    $code = \Yii::app()->getRequest()->getParam('code', null);
    $accessToken = $this->getAccessToken();
    if (empty($accessToken) && !empty($code)) {
      $accessToken = $this->requestAccessToken($code);
      if (isset($accessToken->error)) {
        throw new \CHttpException(400, 'Сервис авторизации Google Account не отвечает');
      }
      \Yii::app()->getSession()->add('google_access_token', $accessToken);
    }
    return !empty($code) || !empty($accessToken);
  }
  
  protected function getAccessToken()
  {
    return \Yii::app()->getSession()->get('google_access_token', null);
  }
  
  public function clearAccess()
  {
    \YIi::app()->getSession()->remove('google_access_token');
  }
  
  protected function requestAccessToken($code)
  {
    $params = array(
      'client_id' => self::ClientId,
      'client_secret' => self::ClientSecret,
      'code' => $code,
      'grant_type' => 'authorization_code'
    );
    $returnUrlParams = [
        'social' => $this->getSocialId(),
        'url' => ''
    ];
    \Yii::app()->getController()->isFrame()  ? $returnUrlParams['frame'] = 'true' : '';
    $params['redirect_uri'] = $this->redirectUrl == null ? \Yii::app()->getController()->createAbsoluteUrl('/oauth/social/connect', $returnUrlParams) : $this->redirectUrl;
    return $this->makeRequest('https://accounts.google.com/o/oauth2/token?', $params);
  }
  
  public static $CURL_OPTS = array(
    CURLOPT_CONNECTTIMEOUT => 10,
    CURLOPT_RETURNTRANSFER => true,
    CURLOPT_TIMEOUT        => 60,
    CURLOPT_USERAGENT      => 'runetid-php'
  );
  
  protected function makeRequest($url, $params = array())
  {
    $ch = curl_init();

    $opts = self::$CURL_OPTS;
  
    if (!empty($params)) {
      $opts[CURLOPT_POSTFIELDS] = http_build_query($params, null, '&');
    }
    $opts[CURLOPT_URL] = $url;
    
    curl_setopt_array($ch, $opts);
    $result = curl_exec($ch);
    
    if (curl_errno($ch) !== 0) {
      throw new \CHttpException(400, 'Сервис авторизации Google Account  не отвечает');
    }
    return json_decode($result);
  }
  
  public function renderScript()
  {
    echo '<script>
      if(window.opener != null && !window.opener.closed)
      {
        window.opener.oauthModuleObj.gProcess();
        window.close();
      }
      </script>';
  }
}
