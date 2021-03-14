<?php

use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Locations';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="organization-index">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a('Create Location', ['add-location'], ['class' => 'btn btn-cob']) ?>
    </p>


    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'columns' => [

            'id',
            'name',
            'address',
            'phone',
            'location',
            'email',
            [
                'format' => 'raw',
                'value' => function ($data) {
                    $column = "";
                    $column .= Html::a(Html::tag('span', "", ['class' => "glyphicon glyphicon-eye-open"]), ['location', 'id' => $data->id], ['title' => "View", "aria-label" => "View"]);
                    $column .= " ";
                    $column .= Html::a(Html::tag('span', "", ['class' => "glyphicon glyphicon-pencil"]), ['update-location', 'id' => $data->id], ['title' => "Edit", "aria-label" => "Edit"]);
                    return $column;
                }
            ],
        ],
    ]); ?>


</div>
