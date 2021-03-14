<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace app\modules\api\controllers;

use app\models\Patient;
use app\models\PatientApiModel;

/**
 * Description of PatientController
 *
 * @author esawa
 */
class PatientController extends \yii\web\Controller {

    public function actionGet($id) {
        \Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
        $data = Patient::findOne($id);
        $data->birth_date = date('d-m-Y', strtotime($data->birth_date));
        
        $patientApiModel = new PatientApiModel();
        $patientApiModel->id = $data->id;
        $patientApiModel->name = $data->name;
        $patientApiModel->birth_date = date('m/d/Y', strtotime($data->birth_date));
        $patientApiModel->national_id = $data->national_id;
        $patientApiModel->passport_number = $data->passport_number;
        $patientApiModel->phone = $data->phone;
        $patientApiModel->gender = $data->gender;
        $patientApiModel->email = $data->email;
        
        if($data->insurance!=null){
            $patientApiModel->insurance_company_name = $data->insurance->company_name;
            $patientApiModel->insurance_number = $data->insurance_number;
            $insuranceExpiryDate = date('m/d/Y', strtotime($data->insurance_expiry_date));

            $patientApiModel->insurance_expiry_date = $insuranceExpiryDate;
            $patientApiModel->insurance_plan_name = $data->insurancePlan->plan_name;
            $patientApiModel->insurance_status = $data->insurance_status;
        }
        return $patientApiModel;
    }

}


