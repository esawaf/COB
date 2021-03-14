<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\models\CustomIcd10 */

$this->title = 'Create Custom Icd10';
$this->params['breadcrumbs'][] = ['label' => 'Custom Icd10s', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="custom-icd10-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'icd10' => $icd10,
        'customIcd10' => $customIcd10,
    ]) ?>

</div>
