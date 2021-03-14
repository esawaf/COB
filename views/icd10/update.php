<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\models\Icd10 */

$this->title = 'Update Icd10: ' . $model->id;
$this->params['breadcrumbs'][] = ['label' => 'Icd10s', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->id, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="icd10-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
