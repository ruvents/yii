<?php
namespace user\models;

class Photo
{
  private $runetId;

  public function __construct($runetId)
  {
    $this->runetId = $runetId;
  }

  /**
   * @param bool $serverPath
   * @return string
   */
  protected function getPath($serverPath = false)
  {
    $folder = $this->runetId / 10000;
    $folder = (int)$folder;
    $result = \Yii::app()->params['UserPhotoDir'] . $folder . '/';
    if ($serverPath)
    {
      $result = \Yii::getPathOfAlias('webroot') . $result;
    }
    return $result;
  }

  /**
   * Возвращает путь к мини изображению пользователя, для шапки сайта, отображения в компаниях и тп
   * @param bool $serverPath
   * @return string
   */
  public function get50px($serverPath = false)
  {
    $name = $this->runetId . '_50.jpg';
    if ($serverPath || file_exists($this->getPath(true) . $name))
    {
      return $this->getPath($serverPath) . $name;
    }
    else
    {
      return $this->getPath($serverPath) . 'nophoto_50.png';
    }
  }

  /**
   * Возвращает путь к мини изображению пользователя, для шапки сайта, отображения в компаниях и тп
   * @param bool $serverPath
   * @return string
   */
  public function get90px($serverPath = false)
  {
    $name = $this->runetId . '_90.jpg';
    if ($serverPath || file_exists($this->getPath(true) . $name))
    {
      return $this->getPath($serverPath) . $name;
    }
    else
    {
      return $this->getPath($serverPath) . 'nophoto_90.png';
    }
  }

  /**
   * Возвращает путь к изображению пользователя для профиля и тп
   * @param bool $serverPath
   * @return string
   */
  public function get200px($serverPath = false)
  {
    $name = $this->runetId . '_200.jpg';
    if ($serverPath || file_exists($this->getPath(true) . $name))
    {
      return $this->getPath($serverPath) . $name;
    }
    else
    {
      return $this->getPath($serverPath) . 'nophoto_200.png';
    }
  }

  /**
   * Возвращает путь к исходному изображению пользователя
   * @param bool $serverPath
   * @return string
   */
  public function getOriginal($serverPath = false)
  {
    $name = $this->runetId . '.jpg';
    if ($serverPath || file_exists($this->getPath(true) . $name))
    {
      return $this->getPath($serverPath) . $name;
    }
    else
    {
      return $this->getPath($serverPath) . 'nophoto_200.png';
    }
  }

  /**
   * Возвращает путь к исходному изображению пользователя
   * @param bool $serverPath
   * @return string
   */
  protected function getClear($serverPath = false)
  {
    $name = $this->runetId . '_clear.jpg';
    if ($serverPath || file_exists($this->getPath(true) . $name))
    {
      return $this->getPath($serverPath) . $name;
    }
    else
    {
      return $this->getPath($serverPath) . 'nophoto_200.png';
    }
  }

  /**
   * @param $image
   * @return void
   */
  public function SavePhoto($image)
  {
    $tmpName = DIRECTORY_SEPARATOR . 'tmp' . DIRECTORY_SEPARATOR .
      md5('user' . microtime()) . '.jpg';
    file_put_contents($tmpName, $image);

    $path = $this->getPath(true);
    if (! is_dir($path))
    {
      mkdir($path);
    }

    $img = \application\components\graphics\Image::GetImage($tmpName);

    $clearSaveTo = $this->getClear(true);
    imagejpeg($img, $clearSaveTo, 100);
    $newImage = $this->getOriginal(true);
    imagejpeg($img, $newImage, 100);
    imagedestroy($img);
    $newImage = $this->get200px(true);
    \application\components\graphics\Image::ResizeAndSave($clearSaveTo, $newImage, 200, 0, array('x1'=>0, 'y1'=>0));
    $newImage = $this->get90px(true);
    \application\components\graphics\Image::ResizeAndSave($clearSaveTo, $newImage, 90, 90, array('x1'=>0, 'y1'=>0));
    $newImage = $this->get50px(true);
    \application\components\graphics\Image::ResizeAndSave($clearSaveTo, $newImage, 50, 50, array('x1'=>0, 'y1'=>0));
  }
}
