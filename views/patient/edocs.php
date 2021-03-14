<?php

use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Edocs';

?>
<div class="patient-index">

    <h1><?= Html::encode($this->title) ?></h1>
    <?php // var_dump($dataProvider->getModels())?>
    <?php
    $count = $dataProvider->getTotalCount();
    if($count>0){
        $firstRow = $dataProvider->getModels()[0];
        $this->params['breadcrumbs'][] = ['label' => $firstRow->patient->name, 'url' => ['view',"id"=>$firstRow->patient_id]];
        
    }
    $this->params['breadcrumbs'][] = $this->title;
    ?>

    <?=
    GridView::widget([
        'dataProvider' => $dataProvider,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],
            'id',
//            'name',
            [
                'label' => 'Name',
                'format' => 'raw',
                'value' => function ($data) {
                    return "<a href='/".$data['file_path']."'>".$data['name']."</a>";
                },
            ],
            'date:date',
//            ['class' => 'yii\grid\ActionColumn'],
        ],
    ]);
    ?>


</div>
