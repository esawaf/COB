<?php

use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Company Profiles';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="login-index">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a('Create Login', ['create'], ['class' => 'btn btn-cob']) ?>
    </p>


    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            'id',
            'username',
//            'password',
            'email:email',
//            'type',
            //'active',
            //'confirmed',
            //'last_login',
            //'register_date',
            //'name',
            //'phone',
            //'id_number',
            //'speciality',
            //'provider_id',
            //'organization_id',
            //'selected_location',

            ['class' => 'yii\grid\ActionColumn'],
        ],
    ]); ?>


</div>
