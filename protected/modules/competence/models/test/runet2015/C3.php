<?php
namespace competence\models\test\runet2015;

class C3 extends \competence\models\form\Single
{
    public function rules()
    {
        $rules = parent::rules();
        $rules[] = ['other', 'numerical', 'message' => 'Вводимое значение должно быть числом, дробная часть отделяется точкой.', 'min' => 0, 'tooSmall' => 'Вводимое значение не может быть меньше нуля'];
        return $rules;
    }
} 