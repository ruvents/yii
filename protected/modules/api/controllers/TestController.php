<?php

class TestController extends CController
{
  public function actionIndex()
  {
    //$api = 'zrnzd5rs8i';
    //$secret = 'YzyrQiHRGDZhsh7ENiRi6YdE5';
    $api = 'y7s32hs3ry';
    $secret = 'atYHhHtKzaK9eQNz5YnRz9sSY';

    $params = array(
      'ApiKey' => $api,
      'Hash' => md5($api . $secret)
    );

    //$params['Email'] = 'alaris.nik@gmail.com';
    //$params['LastName'] = 'Никитин';
    //$params['FirstName'] = 'Виталий';
    //$params['ExternalId'] = 121;
    //$params['RoleId'] = 5;

    //$params['OnlyPublic'] = true;
    //$params['WithDeleted'] = 1;

    //$params['SectionId'] = 12322;
    //$this->apiRequest('/api/section/deleteFavorite', $params);

    print_r($params);

    $this->apiRequest('/api/pay/products', $params);
  }

  private function apiRequest($url, $params)
  {
    $curl = curl_init();
    curl_setopt($curl, CURLOPT_TIMEOUT, 60);
    curl_setopt($curl, CURLOPT_CONNECTTIMEOUT, 10);
    curl_setopt($curl, CURLOPT_FOLLOWLOCATION, 1);
    curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);

    $url = $this->createAbsoluteUrl($url, $params);

    echo $url;

    curl_setopt($curl, CURLOPT_URL, $url);


    $result = curl_exec($curl);

    echo '<pre>';
    print_r($result);

    echo  curl_error ($curl);
    $result = json_decode($result);

    print_r($result);
    echo '</pre>';
  }
}
