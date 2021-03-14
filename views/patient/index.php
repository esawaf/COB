<?php

use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Patients';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="patient-index">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?php
        if (Yii::$app->user->identity->type != "Insurance Profile") {
            ?>
            <?= Html::a('Create Patient', ['create'], ['class' => 'btn btn-cob']) ?>
            <?php
        } else {
            ?>
            <?= Html::a('Pending Insurance', ['pending-insurance'], ['class' => 'btn btn-cob']) ?>
            <?php
        }
        ?>


    </p>
    <?php
    $colums = [
//        ['class' => 'yii\grid\SerialColumn'],
        'id',
        'name',
        'national_id',
        'passport_number',
//        'phone',
    ];
    if (Yii::$app->user->identity->type != "Insurance Profile") {
        $colums[] = ['attribute' => 'insuranceCompanyName', 'value' => 'insurance.company_name'];

//        $colums[] = [
//                'label' => 'Insurance Company',
//                'attribute'=>'insuranceCompanyName',
//                'format' => 'ntext',
//                'value' => function($model) {
////                    var_dump($model);
////                    exit();
//                    return $model->insurance_id;
//                },
//            ];

    }
    //    $colums[] = 'insurancePlan.plan_name';

    $colums[] = 'insurance_number';
    if (Yii::$app->user->identity->type == "Insurance Profile") {
        $colums[] = 'insurance_status';
    }

    $colums[] = [
        'format' => 'raw',
        'value' => function ($data) {
            $column = "";
            $column .= Html::a(Html::tag('span', "", ['class' => "glyphicon glyphicon-eye-open"]), ['view', 'id' => $data->id], ['title' => "View", "aria-label" => "View"]);
            $column .= " ";
            $column .= Html::a(Html::tag('span', "", ['class' => "glyphicon glyphicon-pencil"]), ['update', 'id' => $data->id], ['title' => "Edit", "aria-label" => "Edit"]);
            return $column;
        }];

    ?>

    <?=
    GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => $colums,
    ]);
    ?>


</div>
