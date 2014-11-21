<?php
return array(
  array(
    'allow',
    'users' => array('*'),
    'controllers' => array('default', 'recovery', 'fastauth'),
    'actions' => array('index'),
    'module' => 'main'
  ),
  array(
    'allow',
    'users' => array('*'),
    'controllers' => array('error'),
    'module' => 'main'
  ),

  [
    'allow',
    'users' => ['*'],
    'controllers' => ['devcon', 'appday14'],
    'module' => 'main'
  ],

    [
        'allow',
        'users' => [528, 113001, 172852],
        'controllers' => ['info'],
        'actions' => ['appday14'],
        'module' => 'main'
    ],


  /** Admin Rules */
  array(
    'allow',
    'roles' => array('admin', 'raec', 'booker', 'roommanager'),
    'module' => 'main',
    'controllers' => array('admin/default')
  ),
  array(
    'allow',
    'users' => array(15648, 39948),
    'module' => 'main',
    'controllers' => array('admin/default'),
    'actions' => array('competence2'),
  )
);