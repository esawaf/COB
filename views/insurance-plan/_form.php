<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\models\InsurancePlan */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="insurance-plan-form">

    <?php $form = ActiveForm::begin(); ?>


    <?= $form->field($model, 'plan_name')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'coverage_percantage')->textInput() ?>

    <?= $form->field($model, 'medication_coverage_percentage')->textInput() ?>

    <div class="form-group">
        <?= Html::submitButton('Save', ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
