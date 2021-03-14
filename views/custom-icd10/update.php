<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\models\CustomIcd10 */

$this->title = 'Update Custom Icd10: ' . $customIcd10->id;
$this->params['breadcrumbs'][] = ['label' => 'Custom Icd10s', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $customIcd10->id, 'url' => ['view', 'id' => $customIcd10->id]];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="custom-icd10-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'icd10' => $icd10,
        'customIcd10' => $customIcd10,
    ]) ?>

</div>
