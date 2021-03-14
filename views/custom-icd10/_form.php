<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\widgets\DetailView;


/* @var $this yii\web\View */
/* @var $model app\models\CustomIcd10 */
/* @var $form yii\widgets\ActiveForm */
?>


<?= DetailView::widget([
        'model' => $icd10,
        'attributes' => [
            'id',
            'icd10_code',
            'description',
        ],
    ]) ?>




<div class="custom-icd10-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($customIcd10, 'custom_id')->textInput(['maxlength' => true]) ?>

    <?= $form->field($customIcd10, 'custom_description')->textInput() ?>

    <div class="form-group">
        <?= Html::submitButton('Save', ['class' => 'btn btn-cob']) ?>
    </div>

    <?php ActiveForm::end(); ?>


</div>
