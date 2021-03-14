<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $insurance app\models\InsuranceCompany */
/* @var $model app\models\Login */
/* @var $saved boolean */
/* @var $form yii\widgets\ActiveForm */

$this->title = 'Account Details';
$this->params['breadcrumbs'][] = ['label' => 'Account', 'url' => ['index']];
$this->params['breadcrumbs'][] = 'Update';
?>

<div class="insurance-company-form">

    <?php $form = ActiveForm::begin(); ?>
    <?php echo $form->errorSummary($model); ?>
    <?php echo $form->errorSummary($insurance); ?>

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

    <h2>Company Info</h2>

    <?= $form->field($insurance, 'company_name')->textInput(['maxlength' => true]) ?>

    <?= $form->field($insurance, 'telephone_number')->textInput(['maxlength' => true]) ?>

    <?= $form->field($insurance, 'extension')->textInput(['maxlength' => true]) ?>

    <?= $form->field($insurance, 'fax_no')->textInput(['maxlength' => true]) ?>

    <?= $form->field($insurance, 'address')->textInput(['maxlength' => true]) ?>
    <?= $form->field($insurance, 'city')->textInput(['maxlength' => true]) ?>
    <?= $form->field($insurance, 'state')->textInput(['maxlength' => true]) ?>
    <?= $form->field($insurance, 'zip')->textInput(['maxlength' => true]) ?>

    <?= $form->field($insurance, 'contact_person')->textInput(['maxlength' => true]) ?>

    <?= $form->field($insurance, 'contact_person_phone')->textInput(['maxlength' => true]) ?>

    <?= $form->field($insurance, 'contact_person_email')->textInput(['maxlength' => true]) ?>


    <div class="form-group">
        <?= Html::submitButton('Save', ['class' => 'btn btn-cob']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
