<?php

use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Templates';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="template-index">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a('Create Template', ['create'], ['class' => 'btn btn-cob']) ?>
    </p>


    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            'id',
            'template_name',
            [
                'format' => 'raw',
                'value' => function ($data) {
                    $column = "";
                    $column .= Html::a(Html::tag('span', "", ['class' => "glyphicon glyphicon-pencil"]), ['update', 'id' => $data->id], ['title' => "Edit", "aria-label" => "Edit"]);
                    $column .=" ";
                    $column .= Html::a(Html::tag('span', "", ['class' => "glyphicon glyphicon-trash"]),
                        ['delete', 'id' => $data->id], ['title' => "Delete", "aria-label" => "Delete" ,
                            'data-confirm'=>'Are you sure you want to delete this item?',
                            'data-method'=>'post','data-pjax'=>'0',
                        ]);
                    $column .= " ";
                    $column .= Html::a(Html::tag('span', "", ['class' => "glyphicon glyphicon-copy"]), ['copy', 'id' => $data->id], ['title' => "Copy Template", "aria-label" => "Copy Template"]);
                    return $column;
                }
            ],
        ],
    ]); ?>


</div>
