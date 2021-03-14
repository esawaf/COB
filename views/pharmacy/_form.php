<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\models\Pharmacy */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="pharmacy-form">

    <?php $form = ActiveForm::begin(); ?>

    <?php echo $form->errorSummary($login); ?>

    
    <h2>Login Info</h2>
    
    <?= $form->field($login, 'username')->textInput(['maxlength' => true]) ?>
    
    <?php
    if($model->name==null || $model->name==""){
    ?>
    
    <?= $form->field($login, 'password')->passwordInput(['maxlength' => true]) ?>
    
    <?= Html::label("Password Confirmation", "password_conf")?>
    
    <?= Html::input('password','password_conf','', $options=['class'=>'form-control',"id"=>"password_conf","required"=>"required"] ) ?>
    <?php
    }
    ?>
    
    <?= $form->field($login, 'email')->textInput(['maxlength' => true]) ?>
    
    <?= $form->field($login, 'name')->textInput(['maxlength' => true]) ?>
    
    
    <h2>Pharmacy Info</h2>

    <?= $form->field($model, 'name')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'address')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'contact_person')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'phone')->textInput(['maxlength' => true]) ?>

    
    
    <h2>Pharmacy Location</h2>

    <?= $form->field($pharmacyLocation, 'name')->textInput(['maxlength' => true]) ?>

    <?= $form->field($pharmacyLocation, 'address')->textInput(['maxlength' => true]) ?>

    <?= $form->field($pharmacyLocation, 'phone')->textInput(['maxlength' => true]) ?>

    <?= $form->field($pharmacyLocation, 'location')->textInput(['maxlength' => true]) ?>

    <?= $form->field($pharmacyLocation, 'email')->textInput() ?>

    <div class="form-group">
        <?= Html::submitButton('Save', ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
