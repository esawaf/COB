<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\models\InsuranceCompany */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="insurance-company-form">

    <?php $form = ActiveForm::begin(); ?>
    
    <h2>Login Info</h2>
    
    <?= $form->field($login, 'username')->textInput(['maxlength' => true]) ?>
    
    <?php
    if($model->company_name==null || $model->company_name==""){
    ?>
    
    <?= $form->field($login, 'password')->passwordInput(['maxlength' => true]) ?>
    
    <?= Html::label("Password Confirmation", "password_conf")?>
    
    <?= Html::input('password','password_conf','', $options=['class'=>'form-control',"id"=>"password_conf","required"=>"required"] ) ?>
    <?php
    }
    ?>
    
    <?= $form->field($login, 'email')->textInput(['maxlength' => true]) ?>
    
    <?= $form->field($login, 'name')->textInput(['maxlength' => true]) ?>
    
    
    <h2>Company Info</h2>
    
    <?= $form->field($model, 'company_name')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'telephone_number')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'extension')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'fax_no')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'address')->textInput(['maxlength' => true]) ?>
    <?= $form->field($model, 'city')->textInput(['maxlength' => true]) ?>
    <?= $form->field($model, 'state')->textInput(['maxlength' => true]) ?>
    <?= $form->field($model, 'zip')->textInput(['maxlength' => true]) ?>
    
    <?= $form->field($model, 'contact_person')->textInput(['maxlength' => true]) ?>
    
    <?= $form->field($model, 'contact_person_phone')->textInput(['maxlength' => true]) ?>
    
    <?= $form->field($model, 'contact_person_email')->textInput(['maxlength' => true]) ?>
    
    <?= $form->field($model, 'accounts_number')->textInput(['maxlength' => true]) ?>

    <div class="form-group">
        <?= Html::submitButton('Save', ['class' => 'btn btn-cob']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
