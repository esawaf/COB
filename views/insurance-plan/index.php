<?php

use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Insurance Plans';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="insurance-plan-index">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a('Create Insurance Plan', ['create'], ['class' => 'btn btn-success']) ?>
    </p>


    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            'id',
            'insurance_id',
            'plan_name',
            'coverage_percantage',
            'medication_coverage_percentage',

            ['class' => 'yii\grid\ActionColumn'],
        ],
    ]); ?>


</div>
