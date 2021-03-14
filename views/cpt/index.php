<?php

use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Cpts';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="cpt-index">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a('Create Cpt', ['create'], ['class' => 'btn btn-cob']) ?>
    </p>


    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            'id',
            'custom_code',
            'custom_description',
            'charge',
            ['class' => 'yii\grid\ActionColumn'],
        ],
    ]); ?>


</div>
