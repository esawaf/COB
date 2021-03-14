<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use nex\datepicker\DatePicker;

/* @var $this yii\web\View */
/* @var $model app\models\Patient */
/* @var $form yii\widgets\ActiveForm */
$this->registerJsFile('/js/dobpicker.js');
//var_dump($model);
if(!$model->isNewRecord){
    if($model->insurance_expiry_date==null){
        $model->insurance_expiry_date="";
    }else{
        $model->insurance_expiry_date = Yii::$app->formatter->asDate($model->insurance_expiry_date, 'php:m/d/Y');
    }
    if($model->date_of_injury==null){
        $model->date_of_injury="";
    }else{
        $model->date_of_injury = Yii::$app->formatter->asDate($model->date_of_injury, 'php:m/d/Y');
    }

}
?>

<div class="patient-form">

    <?php $form = ActiveForm::begin(['id'=>'patient-form']); ?>

    <?= $form->field($model, 'name')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'national_id')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'passport_number')->textInput(['maxlength' => true]) ?>

    <div class="row">
        <div class="col-md-2">
            <?= $form->field($model, 'phone_code')->textInput(['maxlength' => true]) ?>
        </div>
        <div class="col-md-10">
            <?= $form->field($model, 'phone')->textInput(['maxlength' => true]) ?>
        </div>
    </div>

    <label>Birth Date</label>
    <div class="row" >
        <div class="col-md-3">
            <select id="birthdate-month" name="birthdate[month]" class="form-control"></select>
        </div>
        <div class="col-md-3">
            <select id="birthdate-day" name="birthdate[day]" class="form-control"></select>
        </div>
        <div class="col-md-3">
            <select id="birthdate-year" name="birthdate[year]" class="form-control"></select>
        </div>

    </div>
    <div class="row" id="birth-date-error-row" style="margin-top: 10px;">

    </div>





    <?php
    $items = ['Male'=>'Male','Female'=>'Female'];
    ?>
    <?=$form->field($model, 'gender')
        ->dropDownList(
            $items
        )?>

    <?= $form->field($model, 'email')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'address')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'city')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'state')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'zip')->textInput(['maxlength' => true]) ?>


    <?= $form->field($model, 'patient_relationship_to_insured')->dropDownList([ 'Self' => 'Self', 'Spouse' => 'Spouse', 'Child' => 'Child', 'Other' => 'Other', ], ['prompt' => '']) ?>
    <?= $form->field($model, 'is_employment')->radioList([1 => 'Yes', 0 => 'No']); ?>
    <?= $form->field($model, 'is_auto_accident')->radioList([1 => 'Yes', 0 => 'No']); ?>
    <?= $form->field($model, 'auto_accident_place')->textInput(['maxlength' => true]) ?>
    <?= $form->field($model, 'is_other_accident')->radioList([1 => 'Yes', 0 => 'No']); ?>
    <?= $form->field($model, 'referring_provider_name')->textInput(['maxlength' => true]) ?>
    <?= $form->field($model, 'referring_provider_npi')->textInput(['maxlength' => true]) ?>
    <?= $form->field($model, 'prior_authorization_number')->textInput(['maxlength' => true]) ?>

    <?php
    $insurenceCompanies = yii\helpers\ArrayHelper::map(app\models\InsuranceCompany::find()
                    ->joinWith('organizationInsurances')
                    ->andWhere(['organization_insurance.organization_id'=>Yii::$app->user->identity->organization_id])
                    ->andWhere(['organization_insurance.status'=>'Approved'])
                    ->all(), 'id', 'company_name');
    echo $form->field($model, 'insurance_id')
            ->dropDownList(
                    $insurenceCompanies,
                    ['id' => 'insuracne-company', "prompt" => "Select Insurance Company"]
    );
    ?>


    <?= $form->field($model, 'insurance_number')->textInput(['maxlength' => true]) ?>
    <label>Insuracne Expiry Date</label>
    <?= DatePicker::widget([
        'model' => $model,
        'attribute' => 'insurance_expiry_date',
        'size' => 'lg',
        'readonly' => true,
        'placeholder' => 'Choose date',
        'clientOptions' => [
            'format' => 'L',
        ],
        'clientEvents' => [
            'dp.show' => new \yii\web\JsExpression("function () { console.log('It works!'); }"),
        ],
    ]);?>



    <div id="insurance-plan-wrapper">

        <?php
        $subcategory = array();
        echo $form->field($model, 'insurance_plan_id')->dropDownList($subcategory,
                ['id' => 'insurance-plan', "prompt" => "Select Insurance Plan"]);
        ?>
    </div>

    <label>Date of Injury</label>
    <?= DatePicker::widget([
        'model' => $model,
        'attribute' => 'date_of_injury',
        'size' => 'lg',
        'readonly' => true,
        'placeholder' => 'Choose date',
        'clientOptions' => [
            'format' => 'L',
        ],
        'clientEvents' => [
            'dp.show' => new \yii\web\JsExpression("function () { console.log('It works!'); }"),
        ],
    ]);?>

    <?= $form->field($model, 'additional_info')->textarea(['rows' => 6]) ?>


    <div class="form-group">
        <?= Html::submitButton('Save', ['class' => 'btn btn-cob']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>


<script type="text/javascript">
    $("#insuracne-company").change(function (){
        addInsurancePlan();
    });


    function addInsurancePlan(){
        var insuranceId = $('#insuracne-company').val();
        var urlP = '<?=\yii\helpers\Url::to(['update-plan-list'])?>';
        $.ajax({
            type: "POST",
            url: urlP, //url to be called
            data: { inid: insuranceId }, //data to be send
            success: function( response ) {
                $('#insurance-plan-wrapper').html(response);
                <?php
                if(!$model->isNewRecord){
                    ?>
                    $('#patient-insurance_plan_id').val('<?=$model->insurance_plan_id?>');
                    <?php
                }
                ?>
            }
        });
    }

    $(document).ready(function(){
        $.dobPicker({
            // Selectopr IDs
            daySelector: '#birthdate-day',
            monthSelector: '#birthdate-month',
            yearSelector: '#birthdate-year',

            // Default option values
            dayDefault: 'Day',
            monthDefault: 'Month',
            yearDefault: 'Year',

            // Minimum age
            minimumAge: 0,

            // Maximum age
            maximumAge: 200
        });
        <?php
        if(!$model->isNewRecord){
            $birthYear = Yii::$app->formatter->asDate($model->birth_date, 'php:Y');
            $birthMonth = Yii::$app->formatter->asDate($model->birth_date, 'php:m');
            $birthDay = Yii::$app->formatter->asDate($model->birth_date, 'php:d');
            ?>
            $('#insuracne-company').val('<?=$model->insurance_id?>');
            setTimeout(function (){
                addInsurancePlan();
            }, 500);
            $('#birthdate-month').val('<?=$birthMonth?>');
            $('#birthdate-day').val('<?=$birthDay?>');
            $('#birthdate-year').val('<?=$birthYear?>');
            <?php
        }
        ?>
        $('#patient-form').submit(function (){
            if($('#birthdate-month').val()=="" ||
                    $('#birthdate-day').val() =="" ||
                    $('#birthdate-year').val()==""){
                $('#birthdate-month').addClass('has-error');
                $('#birthdate-day').addClass('has-error');
                $('#birthdate-year').addClass('has-error');
                $('#birth-date-error-row').append('<div class="alert alert-danger" style="display: block">The birth date is not valid</div>');
                return false;
            }
            return true;
        });
    });
</script>