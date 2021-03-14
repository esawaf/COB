<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $organization app\models\Organization */
/* @var $login app\models\Login */

/* @var $form yii\widgets\ActiveForm */
?>

<div class="organization-form">

    <?php $form = ActiveForm::begin(); ?>
    
    <?php echo $form->errorSummary($login); ?>

    
    <h2>Login Info</h2>
    
    <?= $form->field($login, 'username')->textInput(['maxlength' => true]) ?>
    
    <?php
    if($organization->name==null || $organization->name==""){
    ?>
    
    <?= $form->field($login, 'password')->passwordInput(['maxlength' => true]) ?>
    
    <?= Html::label("Password Confirmation", "password_conf")?>
    
    <?= Html::input('password','password_conf','', $options=['class'=>'form-control',"id"=>"password_conf","required"=>"required"] ) ?>
    <?php
    }
    ?>
    
    <?= $form->field($login, 'email')->textInput(['maxlength' => true]) ?>

    
    <h2>Organization Info</h2>

    <?= $form->field($organization, 'name')->textInput(['maxlength' => true]) ?>

    <?= $form->field($organization, 'address')->textInput(['maxlength' => true]) ?>

    <?= $form->field($organization, 'city')->textInput(['maxlength' => true]) ?>

    <?= $form->field($organization, 'state')->textInput(['maxlength' => true]) ?>

    <?= $form->field($organization, 'zip')->textInput(['maxlength' => true]) ?>


    <?= $form->field($organization, 'contact_person')->textInput(['maxlength' => true]) ?>

    <?= $form->field($organization, 'phone_code')->textInput(['maxlength' => true]) ?>

    <?= $form->field($organization, 'phone')->textInput(['maxlength' => true]) ?>

    <?= $form->field($organization, 'federal_tax_id')->textInput(['maxlength' => true]) ?>

    <?= $form->field($organization, 'npi')->textInput(['maxlength' => true]) ?>


    <?= $form->field($organization, 'doctors_count')->textInput() ?>



    <h2>Organization Location</h2>

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
