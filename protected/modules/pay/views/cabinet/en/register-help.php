<?php
/**
 * @var \user\models\User $user
 * @var \pay\models\Product[] $products
 * @var \pay\models\Account $account
 * @var \event\models\Event $event
 * @var int $unpaidOwnerCount
 * @var int $unpaidJuridicalOrderCount
 */
?>
<div class="alert alert-block alert-muted">
  <p>
    <?if (!empty($user->FirstName)):?>
      Dear <?=$user->getShortName();?>,
    <?else:?>
      Dear customer,
    <?endif;?>
    this step allows you make or edit your order.</p>

  <?if (count($products) > 1):?>
    <p>You can pay both for one or for several participants: all <?=$event->Title;?> services are divided into groups, within which you can specify participants.</p>
  <?else:?>
    <p>You can pay both for one or for several participants.</p>
  <?endif;?>

  <?if (!empty($account->SandBoxUserRegisterUrl)):?>
    <p>
      <strong>Если ваши коллеги еще не зарегистрированы на конференцию, вы можете сделать это за них, пройдя по <a target="_blank" href="<?=$account->SandBoxUserRegisterUrl;?>">ссылке</a>.</strong>
    </p>
  <?endif;?>

  <?if (!$account->SandBoxUser):?>
    <p>To add a participant, add his or her Name and Surname or a RUNET-ID, and the system will automatically check if the person is already added as a participant of the event and will offer to add an existing profile, if it is found. Otherwise you will need to fill in the fields with required contact details.</p>

    <?if ($unpaidOwnerCount > 0 || $unpaidJuridicalOrderCount > 0):?>
      <p><strong>Important:</strong> you have already formed but still <a href="<?=$this->createUrl('/pay/cabinet/index', array('eventIdName' => $event->IdName));?>">unpaid orders</a>.</p>
    <?endif;?>
  <?endif;?>
</div>