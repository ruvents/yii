<?php

return array(
  'modules' => array(
    'gii' => array(
      'class' => 'system.gii.GiiModule',
      'password' => 'vBFwhV06yZ',

      'ipFilters'=>array('127.0.0.1','::1'),
      'generatorPaths'=>array(
        'application.gii',   // псевдоним пути
      ),
    ),


  ),

  'components' => array(
    'user' => array(
      'class' => '\application\components\auth\WebUser',
    )
  )
);