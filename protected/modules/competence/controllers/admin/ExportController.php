<?php

use competence\models\Result;
use \competence\models\tests\mailru2013;
\Yii::import('ext.PHPExcel.PHPExcel', true);

class ExportController extends \application\components\controllers\AdminMainController
{
    /** @var  \competence\models\Test */
    private $test;

    public function actionIndex($id)
    {
        $this->test = \competence\models\Test::model()->findByPk($id);
        if ($this->test === null)
            throw new CHttpException(404);

        $request = Yii::app()->getRequest();

        if ($request->getIsPostRequest()) {
            ini_set("memory_limit", "512M");

            $type = $request->getParam('type', 'all');

            $phpExcel = new PHPExcel();
            $phpExcel->setActiveSheetIndex(0);

            $this->fillProperties($phpExcel);
            $this->fillTitles($phpExcel);
            $this->fillResults($phpExcel, $type);


            $objWriter = new PHPExcel_Writer_Excel2007($phpExcel);
            $path = Yii::getPathOfAlias('competence.data') . sprintf('/%s-%s-%s.xlsx', $this->test->Code, $type, date('YmdHis'));
            $objWriter->save($path);

            Yii::app()->user->setFlash('success', $path);
            $this->refresh();
        }

        $countFinished = \competence\models\Result::model()->byTestId($this->test->Id)->count('"Finished"');
        $countNotFinished = \competence\models\Result::model()->byTestId($this->test->Id)->count('NOT "Finished"');

        $this->render('index', [
            'test' => $this->test,
            'countFinished' => $countFinished,
            'countNotFinished' => $countNotFinished
        ]);
    }

    private $questions = null;

    private function getQuestions()
    {
        if ($this->questions === null) {
            $this->questions = \competence\models\Question::model()->byTestId($this->test->Id)
                ->findAll(['order' => '"Sort"']);
            foreach ($this->questions as $question)
                $question->setTest($this->test);
        }
        return $this->questions;
    }

    /**
     * @param string $type
     * @return Result
     */
    private function getResults($type)
    {
        $criteria = new \CDbCriteria();
        $criteria->with = ['User.Employments.Company'];

        $model = Result::model()->byTestId($this->test->Id);
        switch ($type) {
            case 'finished':
                $model->byFinished(true);
                break;
            case 'unfinished':
                $model->byFinished(false);
                break;
        }
        return $model->findAll();
    }

    private function fillProperties(PHPExcel $phpExcel)
    {
        $phpExcel->getProperties()->setTitle($this->test->Title);
    }

    private function fillTitles(PHPExcel $phpExcel)
    {
        $phpExcel->getActiveSheet()->setCellValueByColumnAndRow(0,3, 'Имя');
        $phpExcel->getActiveSheet()->setCellValueByColumnAndRow(1,3, 'Фамилия');
        $phpExcel->getActiveSheet()->setCellValueByColumnAndRow(2,3, 'Отчество');
        $phpExcel->getActiveSheet()->setCellValueByColumnAndRow(3,3, 'Компания');
        $phpExcel->getActiveSheet()->setCellValueByColumnAndRow(4,3, 'Должность');
        $phpExcel->getActiveSheet()->setCellValueByColumnAndRow(5,3, 'Email');
        $phpExcel->getActiveSheet()->setCellValueByColumnAndRow(6,3, 'Телефон');

        $col = 7;
        foreach ($this->getQuestions() as $question) {
            $phpExcel->getActiveSheet()->setCellValueByColumnAndRow($col, 1, $question->Code);
            $phpExcel->getActiveSheet()->setCellValueByColumnAndRow($col, 2, $question->getForm()->getTitle());

            $titles = $question->getForm()->getExportValueTitles();
            $delta = count($titles) - 1;
            $phpExcel->getActiveSheet()->mergeCellsByColumnAndRow($col, 1, $col+$delta, 1);
            $phpExcel->getActiveSheet()->mergeCellsByColumnAndRow($col, 2, $col+$delta, 2);
            foreach ($titles as $title) {
                $phpExcel->getActiveSheet()->setCellValueByColumnAndRow($col, 3, $title);
                $col++;
            }
        }
    }

    private function fillResults(PHPExcel $phpExcel, $type)
    {
        $results = $this->getResults($type);
        $row = 4;
        /** @var Result $result */
        foreach ($results as $result) {

            $user = $result->User;
            $phpExcel->getActiveSheet()->setCellValueByColumnAndRow(0, $row, $user->FirstName);
            $phpExcel->getActiveSheet()->setCellValueByColumnAndRow(1, $row, $user->LastName);
            $phpExcel->getActiveSheet()->setCellValueByColumnAndRow(2, $row, $user->FatherName);
            $phpExcel->getActiveSheet()->setCellValueByColumnAndRow(3, $row, ($user->getEmploymentPrimary() !== null ? $user->getEmploymentPrimary()->Company->Name : ''));
            $phpExcel->getActiveSheet()->setCellValueByColumnAndRow(4, $row, ($user->getEmploymentPrimary() !== null ? $user->getEmploymentPrimary()->Position : ''));
            $phpExcel->getActiveSheet()->setCellValueByColumnAndRow(5, $row, $user->Email);
            $phpExcel->getActiveSheet()->setCellValueByColumnAndRow(6, $row, $user->PrimaryPhone);

            $col = 7;
            foreach ($this->getQuestions() as $question) {
                $data = $question->getForm()->getExportData($result);
                foreach ($data as $value) {
                    $phpExcel->getActiveSheet()->setCellValueByColumnAndRow($col, $row, $value);
                    $col++;
                }
            }
            $row++;
        }
    }
}