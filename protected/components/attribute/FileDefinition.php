<?php
/**
 * Created by PhpStorm.
 * User: Андрей
 * Date: 17.05.2015
 * Time: 1:07
 */

namespace application\components\attribute;


use application\components\utility\Texts;

class FileDefinition extends Definition
{
    public $types;

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [$this->name, 'file', 'types' => $this->types, 'allowEmpty' => (!$this->required || !$this->public)]
        ];
    }

    /**
     * @inheritdoc
     */
    protected function internalActiveEdit(\CModel $container, $htmlOptions = [])
    {
        $htmlOptions['class'] = $this->cssClass . (isset($htmlOptions['class']) ? $htmlOptions['class'] : '');
        $htmlOptions['style'] = $this->cssStyle . (isset($htmlOptions['style']) ? $htmlOptions['style'] : '');
        return \CHtml::activeFileField($container, $this->name, $htmlOptions);
    }

    /**
     * @param \CUploadedFile $value
     * @inheritdoc
     */
    public function internalPush(\CModel $container, $value)
    {
        if ($value !== null) {
            $path = $this->getSavePath($container, $value);
            $value->saveAs($path);
            return $path;
        }
        return parent::internalPush($container, $value);
    }

    /**
     * @inheritdoc
     */
    public function getPrintValue($container)
    {
        if (!empty($container->{$this->name})) {
            return \CHtml::link(\Yii::t('app', 'Скачать'), ['/partner/user/viewdatafile', 'id' => $container->model()->Id, 'attribute' => $this->name]);
        }
        return '';
    }


    /**
     * @inheritdoc
     */
    public function internalSetAttribute(\CModel $container)
    {
        return \CUploadedFile::getInstance($container, $this->name);
    }

    /**
     * @param \CModel $container
     * @param \CUploadedFile $value
     * @return string
     */
    private function getSavePath(\CModel $container, $value)
    {
        $class = get_class($container);
        $class = substr($class, strrpos($class,'\\')+1);
        $name  = Texts::GenerateString(10, true) . '.' . $value->getExtensionName();
        $path  = \Yii::getPathOfAlias('application') . DIRECTORY_SEPARATOR . '..' . DIRECTORY_SEPARATOR;
        $path .= 'data' . DIRECTORY_SEPARATOR . strtolower($class) . DIRECTORY_SEPARATOR . substr($name, 0, 3);
        if (!is_dir($path)) {
            mkdir($path, 0777, true);
        }
        $path .= DIRECTORY_SEPARATOR . $name;
        return $path;
    }
} 