<?php
/**
 * Created by PhpStorm.
 * User: Андрей
 * Date: 30.11.2015
 * Time: 18:36
 */

namespace pay\components\handlers\buyproduct\products;

use mail\models\Layout;

class Product4028 extends Product3907
{
    /**
     * @inheritdoc
     */
    public function getTo()
    {
        return 'reg@iplaceconf.ru';
    }
}