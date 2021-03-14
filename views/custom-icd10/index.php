<?php

use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Custom Icd10s';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="custom-icd10-index">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
    </p>


    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            'id',
            'custom_id',
            'custom_description',

            ['class' => 'yii\grid\ActionColumn'],
        ],
    ]); ?>


</div>
