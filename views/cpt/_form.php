<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\models\Cpt */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="cpt-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'custom_code')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'custom_description')->textInput() ?>

    <?= $form->field($model, 'charge')->textInput() ?>

    <div class="form-group">
        <?= Html::submitButton('Save', ['class' => 'btn btn-cob']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
