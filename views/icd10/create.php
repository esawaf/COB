<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\models\Icd10 */

$this->title = 'Create Icd10';
$this->params['breadcrumbs'][] = ['label' => 'Icd10s', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="icd10-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
