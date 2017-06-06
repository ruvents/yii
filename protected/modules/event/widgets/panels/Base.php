<?php
/**
 * Created by PhpStorm.
 * User: Андрей
 * Date: 20.12.13
 * Time: 13:43
 */

namespace event\widgets\panels;

use event\models\Attribute;

class Base extends \event\components\WidgetAdminPanel
{
    protected $form;
    protected $showForm = true;

    public function __construct($widget)
    {
        parent::__construct($widget);
        $attributes = $this->getWidget()->getAttributeNames();
        $this->form = new \event\models\forms\widgets\Base();
        if (!empty($attributes)) {
            foreach ($attributes as $name) {
                $attribute = Attribute::model()->byEventId($this->getEvent()->Id)->byName($name)->find();
                foreach ($this->form->getLocaleList() as $locale) {
                    if ($attribute == null) {
                        $this->form->Attributes[$name][$locale] = '';
                    } else {
                        $attribute->setLocale($locale);
                        $attribute->setReturnTransliteIfEmpty(false);
                        $this->form->Attributes[$name][$locale] = $attribute->Value;
                        $attribute->resetLocale();
                    }
                }
            }
        } else {
            $this->showForm = false;
            $this->addError(\Yii::t('app', 'У виджета нет настроек.'));
        }
    }

    public function process()
    {
        $request = \Yii::app()->getRequest();
        $this->form->attributes = $request->getParam(get_class($this->form));
        if ($this->showForm && $this->form->validate()) {
            foreach ($this->getWidget()->getAttributeNames() as $name) {
                $attribute = $this->getAttributeActiveRecord($name);
                $delete = false;
                foreach ($this->form->getLocaleList() as $locale) {
                    $value = isset($this->form->Attributes[$name][$locale]) && strlen($this->form->Attributes[$name][$locale]) ? $this->form->Attributes[$name][$locale] : null;
                    if ($locale == \Yii::app()->getLanguage() && $value == null) {
                        if (!$attribute->getIsNewRecord()) {
                            $attribute->delete();
                        }
                        $delete = true;
                        break;
                    }
                    $attribute->setLocale($locale);
                    $attribute->Value = $value !== null ? $value : '';
                    $attribute->resetLocale();
                }

                if (!$delete) {
                    $attribute->save();
                }
            }
            $this->setSuccess(\Yii::t('app', 'Настройки виджета успешно сохранены'));
            return true;
        }
        $this->addError($this->form->getErrors());
        return false;
    }

    public function render()
    {
        return $this->renderView(['form' => $this->form, 'showForm' => $this->showForm]);
    }

    /**
     * @param string $name
     * @return Attribute
     */
    protected function getAttributeActiveRecord($name)
    {
        $attribute = Attribute::model()->byName($name)->byEventId($this->getEvent()->Id)->find();
        if ($attribute == null) {
            $attribute = new Attribute();
            $attribute->EventId = $this->getEvent()->Id;
            $attribute->Name = $name;
        }
        return $attribute;
    }
}