<?php
namespace competence\models\test\mdtspb15;

class Q7 extends \competence\models\form\Base
{
    public $other;

    private $questions = null;

    public function getQuestions()
    {
        if ($this->questions == null) {
            $this->questions = [
                'q7_1'  => 'Организация мероприятия в целом',
                'q7_2'  => 'Контент мероприятия в целом',
                'q7_3'  => 'Процесс регистрации участников на месте проведения (получение бейджей)',
                'q7_4'  => 'Организация, качество и количество питания',
                'q7_5'  => 'Работа помощников (event-team)',
                'q7_6'  => 'Удобство сайта конференции',
                'q7_7'  => 'Качество и доступность подключения к WiFi сети конференции',
                'q7_8'  => 'Понравилось ли вам мероприятие в целом',
                'q7_9'  => 'Трейлер Microsoft Developer Tour',
                'q7_10' => 'Визуальная стилистика мероприятия'
            ];
        }
        return $this->questions;
    }

    private $values = ['0' => '-', '9' => '9', '8' => '8', '7' => '7', '6' => '6', '5' => '5', '4' => '4', '3' => '3', '2' => '2', '1' => '1', '10' => 'Не знаю'];

    public function getValues()
    {
        return $this->values;
    }

    public function rules()
    {
        return [
            ['value', 'valueValidator'],
        ];
    }

    public function valueValidator($attribute, $params)
    {
        foreach ($this->getQuestions() as $key => $question) {
            $value = isset($this->value[$key]) ? intval($this->value[$key]) : 0;
            if ($value <= 0 || $value > 10) {
                $this->addError('', 'Необходимо оценить мероприятие по всем критериям из списка');
                return false;
            }
        }
        return true;
    }

    protected function getFormData()
    {
        return [
            'value' => $this->value,
            'other' => $this->other
        ];
    }
}
