<?php
use yii\helpers\Html;
use yii\widgets\DetailView;
use yii\widgets\ActiveForm;
/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
$this->title = "Customize ICD10";
?>
<h1>Customize ICD10</h1>
<?= DetailView::widget([
        'model' => $icd10,
        'attributes' => [
            'id',
            'icd10_code',
            'description',
        ],
    ]) ?>



<div class="icd10-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($customIcd10, 'custom_id')->textInput(['maxlength' => true]) ?>

    <?= $form->field($customIcd10, 'custom_description')->textInput() ?>

    <div class="form-group">
        <?= Html::submitButton('Save', ['class' => 'btn btn-cob']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
