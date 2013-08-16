<?php
namespace partner\controllers\main;

class IndexAction extends \partner\components\Action
{

  public function run()
  {
    $this->getController()->setPageTitle('Партнерский интерфейс');
    $this->getController()->initActiveBottomMenu('index');


    /** @var $roles \event\models\Role[] */
    $roles = \event\models\Role::model()
        ->byEventId(\Yii::app()->partner->getAccount()->EventId)->findAll();

    $statistics = null;
    if (sizeof(\Yii::app()->partner->getEvent()->Parts) === 0)
    {
      $statistics = $this->getSingleStatistics();
    }
    else
    {
      $statistics = $this->getManyPartsStatistics();
    }

    $this->getController()->render('index', [
        'roles' => $roles,
        'statistics' => $statistics,
        'event' => \Yii::app()->partner->getEvent(),
        'timeSteps' => $this->getTimeSteps()
      ]
    );
  }

  protected function getSingleStatistics()
  {
    $event = \Yii::app()->partner->getEvent();
    $statistics = array();

    $criteria = new \CDbCriteria();
    $criteria->select = '"t"."RoleId", Count(*) as "CountForCriteria"';
    $criteria->condition = '"t"."EventId" = :EventId';
    $criteria->params['EventId'] = $event->Id;
    $criteria->group = '"t"."RoleId"';
    $criteria->addCondition('"t"."UpdateTime" >= :UpdateTime');

    foreach ($this->getTimeSteps() as $key => $time)
    {
      $criteria->params['UpdateTime'] = $time;
      $participants = \event\models\Participant::model()->findAll($criteria);
      $statistics[$key] = [];
      $statistics[$key]['Total'] = 0;
      foreach ($participants as $participant)
      {
        $statistics[$key][$participant->RoleId] = $participant->CountForCriteria;
        $statistics[$key]['Total'] += $participant->CountForCriteria;
      }
    }

    return $statistics;
  }

  protected function getManyPartsStatistics()
  {
    $event = $this->getEvent();
    $statistics = [];

    /** @var \event\models\Role[] $roles */
    $roles = \event\models\Role::model()->byEventId($event->Id)->findAll(['order'=>'"t"."Priority" DESC']);


    foreach ($this->getTimeSteps() as $key => $time)
    {
      $roleIdList = [];
      $statistics[$key] = [];
      $statistics[$key]['Total'] = 0;

      foreach ($roles as $role)
      {

        $command = \Yii::app()->getDb()->createCommand();
        $command->select('count(DISTINCT ep."UserId")');
        $command->from('EventParticipant ep');
        $command->where('ep."EventId" = :EventId AND ep."RoleId" = :RoleId', [
          'EventId' => $this->getEvent()->Id,
          'RoleId' => $role->Id
        ]);

        if (!empty($time))
        {
          $command->andWhere('ep."UpdateTime" >= :UpdateTime', ['UpdateTime' => $time]);
        }

        if (sizeof($roleIdList) > 0)
        {
          $commandExclude = \Yii::app()->getDb()->createCommand();
          $commandExclude->select('ep2.UserId')->from('EventParticipant ep2');
          $commandExclude->where('"ep2"."EventId" = '.$this->getEvent()->Id);
          $commandExclude->andWhere('"ep2"."RoleId" IN (' . implode(',', $roleIdList) .')');
          if (!empty($time))
          {
            $commandExclude->andWhere('ep2."UpdateTime" >= :UpdateTime', ['UpdateTime' => $time]);
          }

          $command->andWhere('ep."UserId" NOT IN (' . $commandExclude->getText() . ')');
        }

        $result = $command->queryRow();

        $statistics[$key][$role->Id] = [];
        $statistics[$key][$role->Id] = $result['count'];
        $statistics[$key]['Total'] += $result['count'];

        $roleIdList[] = $role->Id;
      }
    }

    $statistics['Parts'] = [];

    $criteria = new \CDbCriteria();
    $criteria->condition = '"t"."EventId" = :EventId AND "t"."RoleId" = :RoleId';
    $criteria->params['EventId'] = $event->Id;
    $criteria->params['RoleId'] = 1;
    $criteria->group = '"t"."PartId"';
    $criteria->select = '"t"."PartId", Count(*) as "CountForCriteria"';
    $criteria->addCondition('"t"."UpdateTime" >= :UpdateTime');

    foreach ($this->getTimeSteps() as $key => $time)
    {
      $statistics['Parts'][$key] = [];
      $statistics['Parts'][$key]['Total'] = 0;
      $criteria->params['UpdateTime'] = $time;

      $participants = \event\models\Participant::model()->findAll($criteria);
      foreach ($participants as $participant)
      {
        $statistics['Parts'][$key][$participant->PartId] = $participant->CountForCriteria;
        $statistics['Parts'][$key]['Total'] += $participant->CountForCriteria;
      }
    }

    return $statistics;
  }

  private $timeSteps = null;

  private function getTimeSteps()
  {
    if ($this->timeSteps === null)
    {
      $this->timeSteps = [];
      $this->timeSteps['За Сегодня'] = date('Y-m-d') . ' 00:00:00';
      $this->timeSteps['На этой неделе'] = date('Y-m-d', strtotime('last Monday'));
      $this->timeSteps['Всего'] = date('Y-m-d', 0);
    }

    return $this->timeSteps;
  }
}
