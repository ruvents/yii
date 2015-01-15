<?php
class LinkController extends \widget\components\Controller
{
  protected function getWidgetParamNames()
  {
    return ['product', 'disable-link'];
  }


  public function actions()
  {
    return [
      'index' => '\widget\controllers\link\IndexAction',
      'cabinet' => '\widget\controllers\link\CabinetAction'
    ];
  }

} 