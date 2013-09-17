<?php
class CreateController extends \application\components\controllers\PublicMainController
{
  public function actionIndex()
  {
    $request = \Yii::app()->getRequest();
    $form = new \event\models\forms\Create(); 
    $form->attributes = $request->getParam(get_class($form));
    
    if (\Yii::app()->getUser()->getIsGuest())
    {
      $form->addError('ContactName', '<a href="#" id="PromoLogin">Авторизуйтесь или зарегистрируйтесь</a> в системе RUNET-ID для добавления мероприятия');
    }
    
    if ($request->getIsPostRequest() && $form->validate(null,false))
    {
      $event = new \event\models\Event();
      $event->Title = $form->Title;
      $event->Info = $form->Info;
      if (!empty($form->FullInfo))
      {
        $event->FullInfo = $form->FullInfo;
      }
      $event->External = true;
      
      $translit = new \ext\translator\Translite();
      $event->IdName = preg_replace("|[^a-z]|i", "", $translit->translit($event->Title));
      $event->IdName = mb_substr($event->IdName, 0, 128);
      
      $startDate = getdate($form->StartTimestamp);
      $event->StartYear = $startDate['year'];
      $event->StartMonth = $startDate['mon'];
      $event->StartDay = $startDate['mday'];
      
      $endDate = getdate($form->EndTimestamp);
      $event->EndYear = $endDate['year'];
      $event->EndMonth = $endDate['mon'];
      $event->EndDay = $endDate['mday'];

      $event->LogoSource = CUploadedFile::getInstance($form, 'LogoSource');

      if ($event->save())
      {
        $LogoSource_path = $event->getPath($event->LogoSource, true);
        
        
        if (!file_exists(dirname($LogoSource_path)))
          mkdir(dirname($LogoSource_path));

        $event->LogoSource->saveAs($LogoSource_path);

        if (!empty($form->Url))
        {
          $event->setContactSite($form->Url);
        }

        $address = new \contact\models\Address();
        $address->Place = $form->Place;
        $address->save();
        $linkAddress = new \event\models\LinkAddress();
        $linkAddress->AddressId = $address->Id;
        $linkAddress->EventId = $event->Id;
        $linkAddress->save();

        $attribute = new \event\models\Attribute();
        $attribute->Name = 'ContactPerson';
        $attributeValue = [
          'Name'    => $form->ContactName,
          'Email'   => $form->ContactEmail,
          'Phone'   => $form->ContactPhone,
          'RunetId' => \Yii::app()->getUser()->getCurrentUser()->RunetId
        ];
        $attribute->Value = serialize($attributeValue);
        $attribute->EventId = $event->Id;
        $attribute->save();

        $attribute = new \event\models\Attribute();
        $attribute->Name  = 'Options';
        $attributeValue = array();
        foreach($form->Options as $option)
        {
          $attributeValue[] = $form->getOptionValue($option);
        }
        $attribute->Value = serialize($attributeValue);
        $attribute->EventId = $event->Id;
        $attribute->save();

        $mail = new \ext\mailer\PHPMailer(false);
        $mail->AddAddress(\Yii::app()->params['EmailEventCalendar']);
        $mail->AddAddress('andrey.korotov@yandex.ru');
        $mail->AddAddress('nikitin@internetmediaholding.com');
        $mail->SetFrom('event@'.RUNETID_HOST, 'RUNET-ID', false);
        $mail->CharSet = 'utf-8';
        $mail->Subject = '=?UTF-8?B?'. base64_encode(\Yii::t('app', 'Получено новое мероприятие')) .'?=';
        $mail->IsHTML(false);
        $mail->Body = $this->renderPartial('email', array('form' => $form), true);
        $mail->Send();

        \Yii::app()->user->setFlash('success', \Yii::t('app', '<h4 class="m-bottom_5">Поздравляем!</h4>Мероприятие отправлено. В ближайшее время c Вами свяжутся.'));
        $this->refresh();
      }
    }
    
    $this->bodyId = 'event-create';
    $this->render('index', array('form' => $form));
  }
}
