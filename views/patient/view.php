<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model app\models\Patient */

$this->title = $model->name;
$this->params['breadcrumbs'][] = ['label' => 'Patients', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
\yii\web\YiiAsset::register($this);
?>
<div class="patient-view">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a('Update', ['update', 'id' => $model->id], ['class' => 'btn btn-cob']) ?>
        <?= Html::a('e-Docs', ['edocs', 'id' => $model->id], ['class' => 'btn btn-cob']) ?>
        <?= Html::a('Add e-Doc', ['add-edoc', 'id' => $model->id], ['class' => 'btn btn-cob']) ?>
        <?= Html::a('Visits', ['visit/patient', 'id' => $model->id], ['class' => 'btn btn-cob']) ?>
        <?php
        if (Yii::$app->user->identity->type == "Front Desk" || Yii::$app->user->identity->type == "Doctor") {
            ?>
            <?= Html::a('Create Visit', ['visit/create', 'patient' => $model->id], ['class' => 'btn btn-cob']) ?>
            <?php
        }
        ?>
        <?php
        if (Yii::$app->user->identity->type == "Insurance Profile") {
            ?>
    <div>
            <?php
            echo Html::a('Approve Insurance Plan', ['approve-insurance', 'id' => $model->id], ['class' => 'btn btn-cob']);
            ?>
            <?php
            echo Html::a('Mark Insurance Plan as Pending', ['mark-pending-insurance', 'id' => $model->id], ['class' => 'btn btn-cob']);
            ?>
            <?php
            echo Html::a('Reject Insurance Plan', ['reject-insurance', 'id' => $model->id], ['class' => 'btn btn-cob']);
            ?>
            <?php
            echo Html::a('Cancel Insurance Plan', ['cancel-insurance', 'id' => $model->id], ['class' => 'btn btn-cob']);
            ?>
        </div>
            <?php
        }
        ?>
    </p>

    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            'id',
            'name',
            'national_id',
            'passport_number',
            'phone',
            'birth_date:date',
            'gender',
            'email:email',
            'address',
            'city',
            'state',
            'zip',
            'patient_relationship_to_insured',
            ['attribute'=>'is_employment','value'=>$model->is_employment==1?"Yes":"No"],
            ['attribute'=>'is_auto_accident','value'=>$model->is_auto_accident==1?"Yes":"No"],
            'auto_accident_place',
            ['attribute'=>'is_other_accident','value'=>$model->is_other_accident==1?"Yes":"No"],
            'referring_provider_name',
            'referring_provider_npi',
            'prior_authorization_number',
            'insurance.company_name',
            'insurance_number',
            'insurance_expiry_date:date',
            'insurancePlan.plan_name',
            'insurance_status',
            'date_of_injury:date',
            'additional_info',

        ],
    ]) ?>

</div>
