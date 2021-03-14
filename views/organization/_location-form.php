<?php
use yii\helpers\Html;
use yii\widgets\ActiveForm;
?>
<div class="organization-form">
    <?php $form = ActiveForm::begin(); ?>

    <?php echo $form->errorSummary($organizationLocation); ?>

    <?= $form->field($organizationLocation, 'name')->textInput(['maxlength' => true]) ?>

    <?= $form->field($organizationLocation, 'address')->textInput(['maxlength' => true]) ?>
    <?= $form->field($organizationLocation, 'city')->textInput(['maxlength' => true]) ?>
    <?= $form->field($organizationLocation, 'state')->textInput(['maxlength' => true]) ?>
    <?= $form->field($organizationLocation, 'zip')->textInput(['maxlength' => true]) ?>

    <?= $form->field($organizationLocation, 'phone')->textInput(['maxlength' => true]) ?>

    <?= $form->field($organizationLocation, 'location')->textInput(['maxlength' => true]) ?>

    <?= $form->field($organizationLocation, 'email')->textInput() ?>

    <div class="form-group">
        <?= Html::submitButton('Save', ['class' => 'btn btn-cob']) ?>
    </div>

    <?php ActiveForm::end(); ?>
</div>
