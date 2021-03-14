<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $organization app\models\Organization */
/* @var $model app\models\Login */
/* @var $saved boolean */

/* @var $form yii\widgets\ActiveForm */

$this->title = 'Account Details';
$this->params['breadcrumbs'][] = ['label' => 'Account', 'url' => ['index']];
$this->params['breadcrumbs'][] = 'Update';
?>

<div class="organization-form">

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

    <div class="form-group">
        <?= Html::submitButton('Save', ['class' => 'btn btn-cob']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
