<?php

use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Insurance Companies';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="insurance-company-index">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a('Create Insurance Company', ['create'], ['class' => 'btn btn-cob']) ?>
    </p>


    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            'id',
            'company_name',
            'telephone_number',
            'extension',
            'fax_no',
            //'address',

            ['class' => 'yii\grid\ActionColumn'],
        ],
    ]); ?>


</div>
