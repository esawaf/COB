<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model app\models\CustomIcd10 */

$this->title = $model->id;
$this->params['breadcrumbs'][] = ['label' => 'Custom Icd10s', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
\yii\web\YiiAsset::register($this);
?>
<div class="custom-icd10-view">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a('Update', ['update', 'id' => $model->id], ['class' => 'btn btn-primary']) ?>
        <?= Html::a('Delete', ['delete', 'id' => $model->id], [
            'class' => 'btn btn-danger',
            'data' => [
                'confirm' => 'Are you sure you want to delete this item?',
                'method' => 'post',
            ],
        ]) ?>
    </p>

    <h2>Original ICD10</h2>
    <?= DetailView::widget([
        'model' => $originalIcd10,
        'attributes' => [
            'id',
            'icd10_code',
            'description',
        ],
    ]) ?>
    
    <h2>Customized ICD10</h2>
    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            'id',
            'custom_id',
            'custom_description',
        ],
    ]) ?>
    

</div>
