<?php

use \partner\components\Controller;

class MainController extends Controller
{
    public function registeredGoogleApis()
    {
        return [
            'visualization' => [
                'version' => '1.1',
                'options' => [
                    'packages' => ['corechart']
                ]
            ]
        ];
    }

    public function actions()
    {
        return [
            'index' => 'partner\controllers\main\IndexAction',
            'pay' => 'partner\controllers\main\PayAction',
        ];
    }

}