<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\models\Icd10 */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="icd10-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'icd10_code')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'description')->textInput() ?>

    <div class="form-group">
        <?= Html::submitButton('Save', ['class' => 'btn btn-cob']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
