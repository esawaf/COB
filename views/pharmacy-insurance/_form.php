<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\models\PharmacyInsurance */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="pharmacy-insurance-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'pharmacy_id')->textInput() ?>

    <?= $form->field($model, 'insurance_company_id')->textInput() ?>

    <?= $form->field($model, 'status')->dropDownList([ 'Pending' => 'Pending', 'Approved' => 'Approved', 'Rejected' => 'Rejected', ], ['prompt' => '']) ?>

    <div class="form-group">
        <?= Html::submitButton('Save', ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
