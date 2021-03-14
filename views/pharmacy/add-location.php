<?php
use yii\helpers\Html;
use yii\widgets\ActiveForm;
?>

<div class="organization-form">

    <?php $form = ActiveForm::begin(); ?>
    <?= $form->field($pharmacyLocation, 'name')->textInput(['maxlength' => true]) ?>

    <?= $form->field($pharmacyLocation, 'address')->textInput(['maxlength' => true]) ?>

    <?= $form->field($pharmacyLocation, 'phone')->textInput(['maxlength' => true]) ?>

    <?= $form->field($pharmacyLocation, 'location')->textInput(['maxlength' => true]) ?>

    <?= $form->field($pharmacyLocation, 'email')->textInput() ?>
    
    
    
    

    <div class="form-group">
        <?= Html::submitButton('Save', ['class' => 'btn btn-cob']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
