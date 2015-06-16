<?php
/**
 * Created by PhpStorm.
 * User: Андрей
 * Date: 29.05.2015
 * Time: 13:51
 */

namespace application\widgets\grid;


class ButtonColumn extends \CButtonColumn
{
    /**
     * @var array the HTML options for the header cell tag.
     */
    public $headerHtmlOptions = [
        'style' => 'width:1px;'
    ];

    /**
     * @var array the HTML options for the footer cell tag.
     */
    public $footerHtmlOptions = [];

    /**
     * @var string the label for the view button. Defaults to "View".
     * Note that the label will not be HTML-encoded when rendering.
     */
    public $viewButtonLabel = '<i class="fa fa-eye"></i>';

    /**
     * @var string the image URL for the view button. If not set, an integrated image will be used.
     * You may set this property to be false to render a text link instead.
     */
    public $viewButtonImageUrl = false;

    /**
     * @var array the HTML options for the update button tag.
     */
    public $updateButtonOptions = [
        'class' => 'btn btn-info',
        'title' => 'Редактировать'
    ];

    /**
     * @var string the label for the update button. Defaults to "Update".
     * Note that the label will not be HTML-encoded when rendering.
     */
    public $updateButtonLabel = '<i class="fa fa-pencil"></i>';

    /**
     * @var string the image URL for the update button. If not set, an integrated image will be used.
     * You may set this property to be false to render a text link instead.
     */
    public $updateButtonImageUrl = false;

    public $updateButtonUrl = 'Yii::app()->controller->createUrl("edit",["id"=>$data->primaryKey])';

    /**
     * @var array the HTML options for the view button tag.
     */
    public $viewButtonOptions = [
        'class' => 'btn btn-success',
        'title' => 'Посмотреть'
    ];

    /**
     * @inheritdoc
     */
    protected function renderDataCellContent($row, $data)
    {
        ob_start();
        parent::renderDataCellContent($row, $data);
        $content = ob_get_contents();
        ob_end_clean();


        echo '<div class="btn-group-vertical" role="group">' . $content . '</div>';
    }


} 