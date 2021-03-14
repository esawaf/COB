<?php

use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Icd10s';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="icd10-index">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a('Create Icd10', ['create'], ['class' => 'btn btn-cob']) ?>
    </p>


    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            'id',
            'icd10_code',
            'description',

            ['class' => 'yii\grid\ActionColumn'],
            [
                'format' => 'raw',
                'value' => function($data) {
                    return Html::a('Customize ICD10', [ 'custom-icd10/create','id'=>$data->id ], ['class' => 'btn btn-cob']);
                }
            ]
        ],
    ]); ?>


</div>
