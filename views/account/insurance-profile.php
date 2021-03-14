<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\models\Login */
/* @var $saved boolean */
/* @var $form yii\widgets\ActiveForm */

$this->title = 'Account Details';
$this->params['breadcrumbs'][] = ['label' => 'Account', 'url' => ['index']];
$this->params['breadcrumbs'][] = 'Update';
?>

<div class="login-form">

    <?php $form = ActiveForm::begin(); ?>
    <?php echo $form->errorSummary($model); ?>

    <h1>Account Details</h1>
    <?php
    if ($saved) {
        ?>
        <div class="alert alert-success">
            The account information has been successfully updated.
        </div>
        <?php
    }
    ?>

    <?= $form->field($model, 'username')->textInput(['maxlength' => true,"disabled"=>"disabled"]) ?>


    <?= $form->field($model, 'email')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'name')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'phone')->textInput(['maxlength' => true]) ?>
    <div class="form-group">
        <?= Html::submitButton('Save', ['class' => 'btn btn-cob']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
