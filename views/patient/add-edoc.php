<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use nex\datepicker\DatePicker;

/* @var $this yii\web\View */
/* @var $model app\models\PatientEdoc */
/* @var $form ActiveForm */
?>
<div class="patient-add-edoc">

    <?php $form = ActiveForm::begin(['options' => ['enctype' => 'multipart/form-data']]) ?>

        <?= $form->field($model, 'name') ?>
        

        <label>Date</label>
        <?= DatePicker::widget([
            'model' => $model,
            'attribute' => 'date',
            'size' => 'lg',
            'readonly' => true,
            'placeholder' => 'Choose date',
            'clientOptions' => [
                'format' => 'L',
            ],
            'clientEvents' => [
                'dp.show' => new \yii\web\JsExpression("function () { console.log('It works!'); }"),
            ],
        ]);?>
    
        <?= $form->field($uploadForm, 'edocFiles[]')->fileInput(['multiple' => true, 'accept' => 'image/*,application/pdf']) ?>

    
        <div class="form-group">
            <?= Html::submitButton('Submit', ['class' => 'btn btn-primary']) ?>
        </div>
    <?php ActiveForm::end(); ?>

</div><!-- patient-add-edoc -->
