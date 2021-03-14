<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\models\InsurancePlan */

$this->title = 'Create Insurance Plan';
$this->params['breadcrumbs'][] = ['label' => 'Insurance Plans', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="insurance-plan-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
