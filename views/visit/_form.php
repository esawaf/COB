<?php

use app\models\Login;
use app\models\Patient;
use app\models\VisitStatus;
use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\helpers\ArrayHelper;
use yii\jui\AutoComplete;
use yii\web\JsExpression;
use kartik\datetime\DateTimePicker;
use yii\helpers\Url;

/* @var $this yii\web\View */
/* @var $model app\models\Visit */
/* @var $form yii\widgets\ActiveForm */

$selectedLocation = Yii::$app->user->identity->selected_location;
$patientId = "";
if($model->visit_date==null){
    $model->visit_date="";
}else{
    $model->visit_date = Yii::$app->formatter->asDate($model->visit_date, 'php:m/d/Y G:i');
}
?>

<div class="visit-form">

    <?php $form = ActiveForm::begin(["id"=>"visit-form"]); ?>
    <div class="row">
        <div class="col-md-6">
            <?php
            $visitDate = "";
            if (!$model->isNewRecord) {
                $visitDate = $model->visit_date;
            }
            echo '<label>Start Date/Time</label>';
            echo DateTimePicker::widget([
                'name' => 'Visit[visit_date]',
                'options' => ['placeholder' => 'Select visit date/time ...'],
                'convertFormat' => true,
                'value' => $visitDate,
                'pluginOptions' => [
                    'autoclose' => true,
                    'format' => 'M/d/yyyy HH:i',
                    'startDate' => '01-Mar-2014 12:00 AM',
                    'todayHighlight' => true
                ]
            ]);
            ?>




            <label class="control-label" for="patient-auto-complete">Patient Name</label>

            <?php
            $patients = Patient::find()->asArray()->all();

            $patients = Patient::find()
                    ->select(['name as value', 'name as  label', 'id as id'])
                    ->asArray()
                    ->all();
            $patientName = "";
            if (!$model->isNewRecord) {
                $patientName = $model->patient->name;
                $patientId = $model->patient->id;
            } else if ($patientModel != null) {
                $patientName = $patientModel->name;
                $patientId = $patientModel->id;
            }
            $autoCompleteOptions=['class' => 'form-control'];
            if(!$model->isNewRecord){
                $autoCompleteOptions["disabled"]="disabled";
            }
            echo AutoComplete::widget([
                'name' => 'patients',
                'id' => 'patient-auto-complete',
                'options' => $autoCompleteOptions,
                'value' => $patientName,
                'clientOptions' => [
                    'source' => $patients,
                    'autoFill' => true,
                    'minLength' => '1',
                    'select' => new JsExpression("function( event, ui ) {
                        $('#visit-patient_id').val(ui.item.id);
                        $.get( '/api/patient/get?id='+ui.item.id, function( data ) {
                                $('#patient-national-id').val(data.national_id);
                                $('#patient-passport-number').val(data.passport_number);
                                $('#patient-phone-number').val(data.phone);
                                $('#patient-birth-date').val(data.birth_date);
                                $('#patient-gender').val(data.gender);
                                $('#patient-email').val(data.email);
                                $('#insurance-company-name').val(data.insurance_company_name);
                                $('#insurance-number').val(data.insurance_number);
                                $('#insurance-plan').val(data.insurance_plan_name);
                                $('#insurance-expiry').val(data.insurance_expiry_date);
                                $('#insurance-status').val(data.insurance_status);
                            });
                    }"),
                ],
            ]);
            ?>
            <?= Html::activeHiddenInput($model, 'patient_id', ['value' => $patientId]) ?>

            <?php
            // echo $form->field($model, 'patient_id')
            //     ->dropDownList(
            //         $patients,           
            //         ['id'=>'id']
            //     );
            ?>


            <?php
            $doctors = ArrayHelper::map(Login::find()->leftJoin("login_location", 'login_location.login_id=login.id')->where(["login.type" => "Doctor", 'login_location.location_id' => $selectedLocation])->asArray()->all(), 'id', 'name');
            echo $form->field($model, 'doctor_id')
                    ->dropDownList(
                            $doctors,
                            ['id' => 'id']
            );
            ?>



            <?php
            $model->isNewRecord==1 ? $model->send_to_insurance=1:$model->send_to_insurance;
            $list = array(
                "1" => "Yes",
                "0" => "No",
            );
            echo $form->field($model, 'send_to_insurance')->radioList($list, array("separator" => "     "));
            
            $model->isNewRecord==1 ? $model->review=1:$model->review;
            $list = array(
                "1" => "Yes",
                "0" => "No",
            );
            echo $form->field($model, 'review')->radioList($list, array("separator" => "     "));

            $nationalId = $passportNumber = $phoneNumber = $birthDate = $gender = $email = $insuranceCompanyName = $insuranceNumber = "";
            $insurancePlan=$insuranceExpiryDate=$insuranceStatus="";
            if (!$model->isNewRecord) {
                $nationalId = $model->patient->national_id;
                $passportNumber = $model->patient->passport_number;
                $phoneNumber = $model->patient->phone;
                $birthDate = Yii::$app->formatter->asDate($model->patient->birth_date, 'php:m/d/Y');
                $gender = $model->patient->gender;
                $email = $model->patient->email;
                if ($model->patient->insurance != null) {
                    $insuranceCompanyName = $model->patient->insurance->company_name;
                    $insuranceNumber = $model->patient->insurance_number;
                    $insurancePlan=$model->patient->insurancePlan->plan_name;
                    $insuranceExpiryDate = Yii::$app->formatter->asDate($model->patient->insurance_expiry_date, 'php:m/d/Y');
                    $insuranceStatus=$model->patient->insurance_status;
                }
            } else if ($patientModel != null) {
                $nationalId = $patientModel->national_id;
                $passportNumber = $patientModel->passport_number;
                $phoneNumber = $patientModel->phone;
                $birthDate = Yii::$app->formatter->asDate($patientModel->birth_date, 'php:m/d/Y');
//                $birthDate = date('m/d/Y', strtotime($patientModel->birth_date));
                $gender = $patientModel->gender;
                $email = $patientModel->email;
                if ($patientModel->insurance != null) {
                    $insuranceCompanyName = $patientModel->insurance->company_name;
                    $insuranceNumber = $patientModel->insurance_number;
                    $insurancePlan=$patientModel->insurancePlan->plan_name;
                    $insuranceExpiryDate = Yii::$app->formatter->asDate($patientModel->insurance_expiry_date, 'php:m/d/Y');
                    $insuranceStatus=$patientModel->insurance_status;
                }
            }
            ?>
        </div>
        <div class="col-md-6">
            Patient Info
            <div class="form-group">
                <label class="control-label">National ID
                    <input type="text" id="patient-national-id" value="<?= $nationalId ?>" class="form-control" disabled="disabled"">
                </label>
                <label class="control-label">Passport Number
                    <input type="text" id="patient-passport-number" value="<?= $passportNumber ?>" class="form-control" disabled="disabled"">
                </label>
                <label class="control-label">Phone Number
                    <input type="text" id="patient-phone-number" value="<?= $phoneNumber ?>" class="form-control" disabled="disabled"">
                </label>
                <label class="control-label">Birth Date
                    <input type="text" id="patient-birth-date" value="<?= $birthDate ?>" class="form-control" disabled="disabled"">
                </label>
                <label class="control-label">Gender
                    <input type="text" id="patient-gender" value="<?= $gender ?>" class="form-control" disabled="disabled"">
                </label>
                <label class="control-label">email
                    <input type="text" id="patient-email" value="<?= $email ?>" class="form-control" disabled="disabled"">
                </label>
                <label class="control-label">Insurance Company Name
                    <input type="text" id="insurance-company-name" value="<?= $insuranceCompanyName ?>" class="form-control" disabled="disabled"">
                </label>
                <label class="control-label">Insurance Number
                    <input type="text" id="insurance-number" value="<?= $insuranceNumber ?>" class="form-control" disabled="disabled"">
                </label>
                <label class="control-label">Insurance Plan
                    <input type="text" id="insurance-plan" value="<?= $insurancePlan ?>" class="form-control" disabled="disabled"">
                </label>
                <label class="control-label">Insurance Expiry Date
                    <input type="text" id="insurance-expiry" value="<?= $insuranceExpiryDate ?>" class="form-control" disabled="disabled"">
                </label>
                <label class="control-label">Insurance Status
                    <input type="text" id="insurance-status" value="<?= $insuranceStatus ?>" class="form-control" disabled="disabled"">
                </label>
            </div>
        </div>
    </div>


<?php
//	$list = array( 
//		VisitStatus::PENDING => VisitStatus::PENDING,
//		VisitStatus::CANCELED => VisitStatus::CANCELED,
//		VisitStatus::POSTPONED => VisitStatus::POSTPONED,
//		VisitStatus::NOSHOW => VisitStatus::NOSHOW,
//		VisitStatus::COMPLETED => VisitStatus::COMPLETED,
//                VisitStatus::CHECKED_IN => VisitStatus::CHECKED_IN,
//		);
//	echo $form->field($model, 'status')->radioList($list,array("separator"=>"     "));
?>
    <?php
    echo $form->field($model, 'status')->hiddenInput(['value' => VisitStatus::PENDING])->label(false);
    echo $form->field($model, 'location_id')->hiddenInput(['value' => $selectedLocation])->label(false);
    ?>







    <div class="form-group">
<?= Html::submitButton('Save', ['class' => 'btn btn-cob']) ?>
    </div>

<?php ActiveForm::end(); ?>

</div>
