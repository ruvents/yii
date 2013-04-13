<?php

return array(

  /***  DENY BLOCK  ***/
  array(
    'deny',
    'roles' => array('mobile'),
    'controllers' => array('event'),
    'actions' => array('register')
  ),
  array(
    'deny',
    'roles' => array('mobile'),
    'controllers' => array('user'),
    'actions' => array('create')
  ),
  array(
    'deny',
    'roles' => array('mobile'),
    'controllers' => array('pay')
  ),
  /*** END DENY BLOCK ***/


  array(
    'allow',
    'users' => array('?'),
    'controllers' => array('raec')
  ),
  array(
    'allow',
    'roles' => array('base'),
    'controllers' => array('user'),
    'actions' => array('auth', 'search', 'create', 'get', 'login')
  ),
  array(
    'allow',
    'roles' => array('base'),
    'controllers' => array('section')
  ),
  array(
    'allow',
    'roles' => array('base'),
    'controllers' => array('event'),
    'actions' => array('roles', 'register', 'list', 'info')
  ),
  array(
    'allow',
    'roles' => array('base'),
    'controllers' => array('pay')
  ),


  /*** Спецпроект для сбербанка  ***/
  array(
    'allow',
    'roles' => array('sberbank'),
    'controllers' => array('user'),
    'actions' => array('get')
  ),

  /***  ЗАПРЕЩЕНО ВСЕ ЧТО НЕ РАЗРЕШЕНО   ***/
  array(
    'deny',
    'users' => array('*')
  ),
);