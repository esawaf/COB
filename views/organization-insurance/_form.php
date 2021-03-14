<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\helpers\ArrayHelper;
use app\models\InsuranceCompany;

/* @var $this yii\web\View */
/* @var $model app\models\OrganizationInsurance */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="organization-insurance-form">

    <?php $form = ActiveForm::begin(); ?>


    <?php
    $companyNames = ArrayHelper::map(InsuranceCompany::find()->all(), 'id', 'company_name');
    echo $form->field($model, 'insurance_company_id')
            ->dropDownList(
                    $companyNames,
                    ['id' => 'id']
    );
    ?>




    <div class="form-group">
        <?= Html::submitButton('Save', ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
