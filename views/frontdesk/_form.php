<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\models\Doctor */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="doctor-form">

    <?php $form = ActiveForm::begin(); ?>
    
    <?php echo $form->errorSummary($model); ?>
    
    <h2>Login Info</h2>
    
    <?= $form->field($model, 'username')->textInput(['maxlength' => true]) ?>
    
    <?= $form->field($model, 'password')->passwordInput(['maxlength' => true]) ?>
    
    <?= Html::label("Password Confirmation", "password_conf")?>
    
    <?= Html::input('password','password_conf','', $options=['class'=>'form-control',"id"=>"password_conf","required"=>"required"] ) ?>
    
    <?= $form->field($model, 'email')->textInput(['maxlength' => true]) ?>
    
    <h2>Frontdesk Info</h2>

    <?= $form->field($model, 'name')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'phone')->textInput(['maxlength' => true]) ?>
    
    
    <?php
    $list = array();
    foreach ($locations as $location){
        $list[$location->id] = $location->name;
    }
    echo Html::checkboxList("locations", "",$list);
    //echo $form->field($model, 'status')->checkboxList($list,array("separator"=>"     "));
    
//    var_dump($locations);
    ?>
    
    

    <div class="form-group">
        <?= Html::submitButton('Save', ['class' => 'btn btn-cob']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>