<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\models\Prescription */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="prescription-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'uuid')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'date')->textInput() ?>

    <?= $form->field($model, 'patient_id')->textInput() ?>

    <?= $form->field($model, 'pharmaciest_id')->textInput() ?>

    <?= $form->field($model, 'status')->dropDownList([ 'Canceled' => 'Canceled', 'Compleled' => 'Compleled', 'Paid' => 'Paid', ], ['prompt' => '']) ?>

    <?= $form->field($model, 'insurance_payment_status')->dropDownList([ 'Pending' => 'Pending', 'Approved' => 'Approved', 'Partialy Approved' => 'Partialy Approved', 'Declined' => 'Declined', ], ['prompt' => '']) ?>

    <?= $form->field($model, 'send_to_insurance')->textInput() ?>

    <div class="form-group">
        <?= Html::submitButton('Save', ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
