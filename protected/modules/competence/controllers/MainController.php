<?php

use application\components\controllers\PublicMainController;
use competence\models\Result;
use competence\models\Test;
use competence\models\Question;
use user\models\User;

/**
 * Class MainController
 */
class MainController extends PublicMainController
{
    // Identifier of the done action
    CONST END_ACTION_NAME = 'done';

    /**
     * @inheritdoc
     */
    public $layout = '/layouts/public';

    /**
     * @var Test
     */
    public $test;

    /**
     * @var Question
     */
    public $question;

    /**
     * @var bool Whether the event header must be rendered
     */
    public $renderEventHeader = false;

    /**
     * @inheritdoc
     */
    public function actions()
    {
        return [
            'process' => 'competence\controllers\main\ProcessAction',
        ];
    }

    /**
     * @return Test
     */
    public function getTest()
    {
        if (is_null($this->test)) {
            $this->test = Test::model()
                ->byParticipantsOnly(false)
                ->findByPk($this->actionParams['id']);
        }

        return $this->test;
    }

    /**
     * Shows the page with the test
     * @param int $id Identifier of the test
     */
    public function actionIndex($id)
    {
        $this->setPageTitle(strip_tags($this->getTest()->Title));

        if (Yii::app()->request->getIsPostRequest()) {
            if ($this->getTest()->Test) {
                $this->test->getFirstQuestion()->getForm()->clearResult();
                $this->test->getFirstQuestion()->getForm()->clearRotation();
            }
            $this->redirect($this->createUrl('/competence/main/process', ['id' => $id]));
        }

        $this->render('index', [
            'test' => $this->getTest()
        ]);
    }

    /**
     * Shows the page with all questions from the test
     * @param int $id Identifier of the test
     */
    public function actionAll($id)
    {
        $request = \Yii::app()->getRequest();

        $this->test->setUser($this->getUser());
        $questions = $this->getQuestions();

        $hasErrors = false;
        if ($request->isPostRequest) {
            foreach ($questions as $question) {
                $form = $question->getForm();
                $form->setAttributes($request->getParam(get_class($form)), false);
                if (!$form->process(true)) {
                    $hasErrors = true;
                }
            }

            if (!$hasErrors) {
                $this->test->saveResult();
                $this->redirect([self::END_ACTION_NAME, 'id' => $this->test->Id]);
            }
        }

        if ($this->test->EventId == 2318 /* СВЯЗЬ. ИНФОРМАЦИОННЫЕ И КОММУНИКАЦИОННЫЕ ТЕХНОЛОГИИ – 2016 */) {
            $this->renderEventHeader = true;
        }

        $this->render('all-questions', [
            'user' => $this->getUser(),
            'test' => $this->test,
            'questions' => $questions,
            'hasErrors' => $hasErrors
        ]);
    }

    /**
     * Shows the page when the test is done
     * @param int $id
     */
    public function actionDone($id)
    {
        $this->render($this->getTest()->getEndView(), [
            'test' => $this->getTest(),
            'done' => $this->checkExistsResult()
        ]);
    }

    /**
     * Shows the page after the test
     * @param int $id
     */
    public function actionAfter($id)
    {
        $this->render('after', ['test' => $this->getTest()]);
    }

    /**
     * Returns current user
     * @return User|null
     */
    public function getUser()
    {
        if (Yii::app()->user->getCurrentUser() !== null) {
            return Yii::app()->user->getCurrentUser();
        } elseif (Yii::app()->tempUser->getCurrentUser() !== null) {
            return Yii::app()->tempUser->getCurrentUser();
        }

        return null;
    }

    /**
     * @inheritdoc
     */
    protected function beforeAction($action)
    {
        if (is_null($this->getTest()) || !$this->getTest()->Enable) {
            throw new CHttpException(404);
        }

        if ($this->getTest()->getUserKey() == null && Yii::app()->user->getCurrentUser() == null) {
            $this->render('competence.views.system.unregister');
            return false;
        }

        if ($this->checkExistsResult() && $action->getId() != 'done') {
            $this->redirect(['done', 'id' => $this->getTest()->Id]);
        }

        if (!empty($this->getTest()->EndTime) && $this->test->EndTime < date('Y-m-d H:i:s') && $action->getId() != 'after') {
            $this->redirect(['after', 'id' => $this->getTest()->Id]);
        }

        $this->getTest()->setUser(Yii::app()->user->getCurrentUser());

        return parent::beforeAction($action);
    }

    /**
     * Проверяет проходил ли пользователь опрос
     * @return bool
     */
    private function checkExistsResult()
    {
        if (!$this->getTest()->Test && !$this->getTest()->Multiple) {
            $model = Result::model()->byTestId($this->getTest()->Id)->byFinished();
            if ($this->getTest()->getUserKey() !== null) {
                $model->byUserKey($this->getTest()->getUserKey());
            } else {
                $model->byUserId($this->getUser()->Id);
            }
            return $model->exists();
        }

        return false;
    }

    /**
     * Returns questions list
     * @return Question[]
     * @throws Exception
     */
    private function getQuestions()
    {
        $questions = [];
        $question = $this->test->getFirstQuestion();

        while (true) {
            $questions[] = $question;
            /** @var Question $question */
            $question = $question->getForm()->getNext();
            if ($question == null) {
                break;
            }

            $question->setTest($this->test);
        }

        return $questions;
    }
}
