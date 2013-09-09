<?php
namespace api\controllers\user;

class SearchAction extends \api\components\Action
{
  public function run()
  {
    $request = \Yii::app()->getRequest();
    $query = $request->getParam('Query', null);
    $maxResults = $request->getParam('MaxResults', $this->getMaxResults());
    $maxResults = min($maxResults, $this->getMaxResults());
    $pageToken = $request->getParam('PageToken', null);

    if (strlen($query) === 0)
    {
      throw new \api\components\Exception(203);
    }

    $criteria = new \CDbCriteria();
    $criteria->order = '"t"."LastName", "t"."FirstName", "t"."RunetId"';
    if ($pageToken === null)
    {
      $criteria->limit = $maxResults;
      $criteria->offset = 0;
    }
    else
    {
      $criteria->limit = $maxResults;
      $criteria->offset = $this->getController()->parsePageToken($pageToken);
    }

    $with = array(
      'Settings',
      'Employments.Company' => array('on' => '"Employments"."Primary"'),
      'LinkEmail.Email'
    );
    if ($this->Account->EventId != null)
    {
      $with['Participants'] = array('on' => '"Participants"."EventId" = :EventId', 'params' => array(':EventId' => $this->Account->EventId));
    }
    else
    {
      $with[] = 'Participants';
    }
    $with[] = 'Participants.Role';
    $with[] = 'Participants.Event';

    $model = \user\models\User::model();
    if (filter_var($query, FILTER_VALIDATE_EMAIL))
    {
      $model->byEmail($query)->byVisible();
    }
    else
    {
      $model->bySearch($query);
    }
    $model->with($with);

    /** @var $users \user\models\User[] */
    $users = $model->findAll($criteria);

    $result = array();
    $result['Users'] = array();
    foreach ($users as $user)
    {
      $this->getAccount()->getDataBuilder()->createUser($user);
      //$this->getAccount()->getDataBuilder()->buildUserContacts($user);
      $this->getAccount()->getDataBuilder()->buildUserEmployment($user);
      $result['Users'][] = $this->getAccount()->getDataBuilder()->buildUserEvent($user);
    }

    if (sizeof($users) === $maxResults)
    {
      $result['NextPageToken'] = $this->getController()->getPageToken($criteria->offset + $maxResults);
    }

    $this->getController()->setResult($result);
  }


}
