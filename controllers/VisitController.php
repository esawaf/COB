<?php

namespace app\controllers;

use app\models\BillingPostCpt;
use app\models\CustomCpt;
use app\models\Login;
use app\models\Organization;
use app\models\Template;
use app\models\VisitReportData;
use app\models\VisitStatus;
use mikehaertl\pdftk\Pdf;
use Mpdf\Mpdf;
use Yii;
use app\models\Visit;
use app\models\VisitAssessment;
use app\models\VisitBilling;
use app\models\VisitObjective;
use app\models\VisitSubjective;
use yii\data\ActiveDataProvider;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use app\models\CustomIcd10;
use app\models\VisitIcd10;
use app\models\BillCpt;
use app\models\VisitReport;
use app\models\OrganizationLocation;

/**
 * VisitController implements the CRUD actions for Visit model.
 */
class VisitController extends Controller
{

    /**
     * {@inheritdoc}
     */
    public function behaviors()
    {
        return [
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'delete' => ['POST'],
                ],
            ],
        ];
    }

    /**
     * Lists all Visit models.
     * @return mixed
     */
    public function actionIndex()
    {
        if (Yii::$app->user->identity->type != "Insurance Profile" && Yii::$app->user->identity->selected_location == null) {
            $user = \app\models\Login::findOne(array("id" => Yii::$app->user->identity->id));
            $user->selected_location = OrganizationLocation::findAll(array('organization_id' => Yii::$app->user->identity->organization_id))[0]->id;
            $user->save();
            $this->redirect(["index"]);
        }
        $query = Visit::find();
        $insurancePendingPaymentCount = 0;

        if (Yii::$app->user->identity->type == "Insurance Profile") {

            $query = Visit::find()->leftJoin("patient", "patient.id=visit.patient_id")->where(['patient.insurance_id' => Yii::$app->user->identity->insurance_id]);
            $query->andWhere(['send_to_insurance' => 1]);
            $query->andWhere(['review' => 0]);
            $query->andWhere(['status' => 'Compleled']);
            $insurancePendingPaymentCount = Visit::find()->leftJoin("patient", "patient.id=visit.patient_id")
                ->where(['patient.insurance_id' => Yii::$app->user->identity->insurance_id])
                ->andWhere(['insurance_payment_status' => 'Pending'])
                ->count();
        }
        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);
        $selectedLocation = Yii::$app->user->identity->selected_location;
        $count = 0;
        $pendingReviewCount = 0;

        if (Yii::$app->user->identity->type != "Insurance Profile") {
            $query->andFilterWhere(['location_id' => $selectedLocation]);
            $count = Visit::find()->where(['location_id' => $selectedLocation])->andWhere(["status" => \app\models\VisitStatus::CHECKED_IN])->count();
            $pendingReviewCount = Visit::find()->where(['location_id' => $selectedLocation])->andWhere(["review" => "1"])->count();

        }
//        $doctors = Login::find()->where(['type'=>'Doctor'])->all();
        $doctors = Login::find()->leftJoin("login_location", 'login_location.login_id=login.id')->where(["login.type" => "Doctor", 'login_location.location_id' => $selectedLocation])->all();


        $doctorVisitCount = array();
        if (isset(Yii::$app->request->get()['date'])) {
            $date = Yii::$app->request->get()['date'];
            $startDate = $date . " 00:00:00";
            $endDate = $date . " 23:59:59";
            $query->andWhere(['between', 'visit_date', $startDate, $endDate]);
//            echo "<pre>";
//            var_dump($doctors);
//            echo "</pre>";
            foreach ($doctors as $doctor){
//                echo "<pre>";
//                var_dump($doctor->id);
//                echo "</pre>";
                $doctorVisitCount[$doctor->name]=Visit::find()->where(['doctor_id'=>$doctor->id])->andWhere(['between', 'visit_date', $startDate, $endDate])->count();
            }
        } else {
            $date = date('Y-m-d');
            $startDate = $date . " 00:00:00";
            $endDate = $date . " 23:59:59";
            $query->andWhere(['between', 'visit_date', $startDate, $endDate]);
            foreach ($doctors as $doctor){
                $doctorVisitCount[$doctor->name]=Visit::find()->where(['doctor_id'=>$doctor->id])->andWhere(['between', 'visit_date', $startDate, $endDate])->count();
            }
        }
        $dataProvider->sort->attributes['patient.name'] = [
            'asc' => ['patient.name' => SORT_ASC],
            'desc' => ['patient.name' => SORT_DESC],
        ];

        $dataProvider->sort->attributes['doctor.name'] = [
            'asc' => ['doctor.name' => SORT_ASC],
            'desc' => ['doctor.name' => SORT_DESC],
        ];


        return $this->render('index', [
            'dataProvider' => $dataProvider,
            'incompleteCount' => $count,
            'reviewCount' => $pendingReviewCount,
            'insurancePendingPaymentCount' => $insurancePendingPaymentCount,
            'doctorVisitCount'=>$doctorVisitCount,
        ]);
    }

    public function actionIncomplete()
    {
        $insurancePendingPaymentCount = 0;
        $dataProvider = new ActiveDataProvider([
            'query' => Visit::find(),
        ]);
        $selectedLocation = Yii::$app->user->identity->selected_location;
        $dataProvider->query->andFilterWhere(['location_id' => $selectedLocation]);
        $dataProvider->query->andFilterWhere(['status' => \app\models\VisitStatus::CHECKED_IN]);
        $count = Visit::find()->where(['location_id' => $selectedLocation])->andWhere(["status" => \app\models\VisitStatus::CHECKED_IN])->count();
        $pendingReviewCount = Visit::find()->where(['location_id' => $selectedLocation])->andWhere(["review" => "1"])->count();
        return $this->render('index', [
            'dataProvider' => $dataProvider,
            'incompleteCount' => $count,
            'reviewCount' => $pendingReviewCount,
            'insurancePendingPaymentCount' => $insurancePendingPaymentCount,
        ]);
    }

    public function actionUnpaid()
    {
        $insurancePendingPaymentCount = 0;
        $dataProvider = new ActiveDataProvider([
            'query' => Visit::find(),
        ]);
        $selectedLocation = Yii::$app->user->identity->selected_location;
        $dataProvider->query->andFilterWhere(['location_id' => $selectedLocation]);
        $dataProvider->query->andFilterWhere(['status' => \app\models\VisitStatus::COMPLETED]);
        $count = Visit::find()->where(['location_id' => $selectedLocation])->andWhere(["status" => \app\models\VisitStatus::CHECKED_IN])->count();
        $pendingReviewCount = Visit::find()->where(['location_id' => $selectedLocation])->andWhere(["review" => "1"])->count();
        return $this->render('index', [
            'dataProvider' => $dataProvider,
            'incompleteCount' => $count,
            'reviewCount' => $pendingReviewCount,
            'insurancePendingPaymentCount' => $insurancePendingPaymentCount,
        ]);
    }

    public function actionPendingReview()
    {
        $insurancePendingPaymentCount = 0;
        $dataProvider = new ActiveDataProvider([
            'query' => Visit::find(),
        ]);
        $selectedLocation = Yii::$app->user->identity->selected_location;
        $dataProvider->query->andFilterWhere(['location_id' => $selectedLocation]);
        $dataProvider->query->andFilterWhere(['status' => \app\models\VisitStatus::COMPLETED]);
        $dataProvider->query->andFilterWhere(['review' => 1]);
        $count = Visit::find()->where(['location_id' => $selectedLocation])->andWhere(["status" => \app\models\VisitStatus::CHECKED_IN])->count();
        $pendingReviewCount = Visit::find()->where(['location_id' => $selectedLocation])->andWhere(["review" => "1"])->count();
        return $this->render('index', [
            'dataProvider' => $dataProvider,
            'incompleteCount' => $count,
            'reviewCount' => $pendingReviewCount,
            'insurancePendingPaymentCount' => $insurancePendingPaymentCount,
        ]);
    }

    public function actionInsurancePaymentPending()
    {
        $insurancePendingPaymentCount = 0;
        $query = Visit::find()->leftJoin("patient", "patient.id=visit.patient_id")
            ->where(['patient.insurance_id' => Yii::$app->user->identity->insurance_id]);
        $query->andWhere(['send_to_insurance' => 1]);
        $query->andWhere(['review' => 0]);
        $query->andWhere(['status' => 'Compleled']);
        $query->andWhere(['insurance_payment_status' => 'Pending']);


        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        $count = 0;
        $pendingReviewCount = 0;


        $insurancePendingPaymentCount = Visit::find()->leftJoin("patient", "patient.id=visit.patient_id")
            ->where(['patient.insurance_id' => Yii::$app->user->identity->insurance_id])
            ->andWhere(['send_to_insurance' => 1])
            ->andWhere(['review' => 0])
            ->andWhere(['status' => 'Compleled'])

            ->andWhere(['insurance_payment_status' => 'Pending'])
            ->count();

        return $this->render('index', [
            'dataProvider' => $dataProvider,
            'incompleteCount' => $count,
            'reviewCount' => $pendingReviewCount,
            'insurancePendingPaymentCount' => $insurancePendingPaymentCount,
        ]);
    }

    /**
     * Displays a single Visit model.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionView($id)
    {
        $model = $this->findModel($id);
        $visitReportsCount = count($model->visitReports);
        if ($visitReportsCount > 0 && count($model->visitReports[$visitReportsCount-1]->visitReportDatas)>0 &&  $model->status == VisitStatus::COMPLETED && Yii::$app->request->get('carry') != 1) {
            return $this->redirect(['view-details', 'id' => $model->id]);
        }

        $templates = Template::findAll(['organization_id' => Yii::$app->user->identity->organization_id, 'active' => 1]);

        $visitBilling = new VisitBilling();

        $cpts = CustomCpt::findAll(["doctor_id" => Yii::$app->user->identity->id]);
        $icd10s = CustomIcd10::findAll(["login_id" => Yii::$app->user->identity->id]);

        $request = Yii::$app->request;

        if ($request->isPost) {
            $this->insertVisitNoteDetails($id);
            return $this->redirect(['view-details', 'id' => $model->id]);
        }
        $visitReport = null;
        if (isset(Yii::$app->request->get()['carry']) && Yii::$app->request->get()['carry'] == 1) {

            $lastVisit = Visit::find()->where(['patient_id' => $model->patient_id])
                ->andWhere(['or', ['status' => 'Compleled'], ['status' => 'Paid']])
                ->andWhere("visit_date < '" . $model->visit_date . "'")
                ->orderBy(['visit_date' => SORT_DESC])->limit(1)->one();
            if ($lastVisit != null) {
                $visitReports = $lastVisit->visitReports;
                if (count($visitReports) > 0) {
                    $visitReport = $visitReports[count($visitReports) - 1];
                }
            }
//            $visitReport = VisitReport::find()->where(['visit_id' => $id])->orderBy(['id' => SORT_DESC])->limit(1)->one();
        }
        return $this->render('view', [
            'model' => $model,
            'billing' => $visitBilling,
            'cpts' => $cpts,
            'icd10s' => $icd10s,
            'visitReport' => $visitReport,
            'templates' => $templates,
        ]);
    }

    public function actionAddendum($id)
    {
        $model = $this->findModel($id);
        $visitSubective = new VisitSubjective();
        $visitObjective = new VisitObjective();
        $visitAssessment = new VisitAssessment();
        $visitBilling = new VisitBilling();

        $cpts = CustomCpt::findAll(["doctor_id" => Yii::$app->user->identity->id]);
        $icd10s = CustomIcd10::findAll(["login_id" => Yii::$app->user->identity->id]);
        $templates = Template::findAll(['organization_id' => Yii::$app->user->identity->organization_id]);

        $request = Yii::$app->request;
        if ($request->isPost) {
            $this->insertVisitNoteDetails($id);
            return $this->redirect(['view-details', 'id' => $model->id]);
        }
        $visitReport = VisitReport::find()->where(['visit_id' => $id])->orderBy(['id' => SORT_DESC])->limit(1)->one();
        return $this->render('view', [
            'model' => $model,
            'subjective' => $visitSubective,
            'objective' => $visitObjective,
            'assessment' => $visitAssessment,
            'billing' => $visitBilling,
            'cpts' => $cpts,
            'icd10s' => $icd10s,
            'visitReport' => $visitReport,
            'templates' => $templates,
        ]);
    }

    public function actionViewDetails($id)
    {
        $reportId = null;
        $visitReport = null;


        $model = $this->findModel($id);
        $visitSubective = new VisitSubjective();
        $visitObjective = new VisitObjective();
        $visitAssessment = new VisitAssessment();
        $visitBilling = new VisitBilling();


        $cpts = CustomCpt::findAll(["doctor_id" => Yii::$app->user->identity->id]);
        $icd10s = CustomIcd10::findAll(["login_id" => Yii::$app->user->identity->id]);
        $templates = Template::findAll(['organization_id' => Yii::$app->user->identity->organization_id]);

        if (isset(Yii::$app->request->get()['report'])) {
            $reportId = Yii::$app->request->get()['report'];
        }
        if ($reportId != null) {
            $visitReport = VisitReport::findOne($reportId);
        } else {
            $visitReport = VisitReport::find()->where(['visit_id' => $id])->orderBy(['id' => SORT_DESC])->limit(1)->one();
        }

        $request = Yii::$app->request;
        if ($request->isPost) {
//            var_dump(Yii::$app->request->post());
//            exit();
            $cptIds = Yii::$app->request->post()['cpt-ids'];
            $modifier1 = Yii::$app->request->post()['cpt-modifier1'];
            $modifier2 = Yii::$app->request->post()['cpt-modifier2'];
            $modifier3 = Yii::$app->request->post()['cpt-modifier3'];
            $modifier4 = Yii::$app->request->post()['cpt-modifier4'];
            $cptUnits = Yii::$app->request->post()['cpt-units'];
            $cptCharges = Yii::$app->request->post()['cpt-charges'];
            $totalCharge = Yii::$app->request->post()['total-rate'];
            $removedCpts = Yii::$app->request->post()['removed-cpts'];
            $billCptIds = Yii::$app->request->post()['bill-cpt-ids'];
            $relatedIcd10s = Yii::$app->request->post()['related-icd10s'];
            $icd10Ids = Yii::$app->request->post()['idc10-ids'];
            $visitIcd10Ids = Yii::$app->request->post()['visit-idc10-ids'];
            $removedIcd10s = Yii::$app->request->post()['removed-icd10s'];

            $removedIcd10sArr = explode(",", $removedIcd10s);
            foreach ($removedIcd10sArr as $removedIcd10Id) {
                if ($removedIcd10Id != "") {
                    $visitIcd10 = VisitIcd10::findOne($removedIcd10Id);
                    $visitIcd10->delete();
                }
            }
            for ($i = 0; $i < count($icd10Ids); $i++) {
                $visitIcd10Id = $visitIcd10Ids[$i];
                $icd10Id = $icd10Ids[$i];
                if ($visitIcd10Id == "") {
                    $visitIcd10 = new VisitIcd10();
                    $visitIcd10->visit_report_id = $visitReport->id;
                    $visitIcd10->custom_icd10_id = $icd10Id;
                    $visitIcd10->save();
                }
            }
            $removedCptsArr = explode(",", $removedCpts);
            foreach ($removedCptsArr as $removedCpt) {
                if ($removedCpt != "") {
                    $billCpt = BillCpt::findOne($removedCpt);
                    $billCpt->delete();
                }
            }
            $visitBill = null;
            for ($i = 0; $i < count($cptIds); $i++) {
                $billCpt = null;
                if ($billCptIds[$i] == "") {
                    $billCpt = new BillCpt();
                    $billCpt->cpt_id = $cptIds[$i];
                    $billCpt->visit_bill_id = $visitReport->visitBillings[0]->id;
                } else {
                    $billCpt = BillCpt::findOne($billCptIds[$i]);

                }
                $billCpt->identifier1 = $modifier1[$i];
                $billCpt->identifier2 = $modifier2[$i];
                $billCpt->identifier3 = $modifier3[$i];
                $billCpt->identifier4 = $modifier4[$i];
                $billCpt->no_of_units = $cptUnits[$i];
                $billCpt->related_icd10 = $relatedIcd10s[$i];
                $billCpt->charge = $cptCharges[$i];
                if ($visitBill == null) {
                    $visitBill = $billCpt->visitBill;
                }
                $billCpt->save();
            }
            if ($visitBill != null) {
                $visitBill->cost = $totalCharge;
                $visitBill->save();
            }
        }
        $dataProvider = new ActiveDataProvider([
            'query' => VisitReport::find(),
        ]);

        $dataProvider->query->andFilterWhere(['visit_id' => $id]);

        return $this->render('view', [
            'model' => $model,
            'subjective' => $visitSubective,
            'objective' => $visitObjective,
            'assessment' => $visitAssessment,
            'billing' => $visitBilling,
            'cpts' => $cpts,
            'icd10s' => $icd10s,
            'visitReport' => $visitReport,
            'templates' => $templates,
            'dataProvider' => $dataProvider,
        ]);
    }

    public function actionReport($id)
    {
        $visitReport = VisitReport::find()->where(['visit_id' => $id])->orderBy(['id' => SORT_DESC])->limit(1)->one();
        $visitBillId = $visitReport->visitBillings[0]->id;
        $patient = $visitReport->visit->patient;

        $visit = $visitReport->visit;
        $visitReportData = json_decode($visitReport->visitReportDatas[0]->visit_data, true);
//        var_dump($visitReportData);
//        var_dump($visit);
//        exit();
        $patientBirthDate = $patient->birth_date;
        $patientBirthTime = strtotime($patientBirthDate);


        $patientBirthYear = date('Y', $patientBirthTime);
        $patientBirthMonth = date('m', $patientBirthTime);
        $patientBirthDay = date('d', $patientBirthTime);
        $patientFullName = $patient->name;
        $spacePos = strpos($patientFullName, " ");
//        $patientFirstName = trim(substr($patientFullName,0,$spacePos));
//        $patientLastName=trim(substr($patientFullName,$spacePos));
//        $patientLastNameFirstName= "$patientLastName, $patientFirstName";
//        var_dump($patientBirthDate);
        $pdf = new Pdf('/pdf/cms.pdf');
        $formData = array();
        $insurance = $patient->insurance;

        if ($insurance != null) {
            $formData['INSURANCE_NAME'] = $insurance->company_name;
            $formData['INSURANCE_ADDRESS1'] = $insurance->address;
            $formData['INSURANCE_ADDRESS2'] = $insurance->city . ", " . $insurance->state . " " . $insurance->zip;
        }
        $formData['BOX1_GROUP'] = "Yes";
        $formData['BOX1A'] = $patient->insurance_number;
        $formData['PAT_NAME'] = $patientFullName;
        $formData['BOX3_MM'] = $patientBirthMonth;
        $formData['BOX3_DD'] = $patientBirthDay;
        $formData['BOX3_YY'] = $patientBirthYear;
        if ($patient->gender == "Male") {
            $formData['BOX3_M'] = "Yes";
        } else {
            $formData['BOX3_F'] = "Yes";
        }
        $formData['BOX4'] = $patientFullName;
        $formData['BOX5'] = $patient->address;
        $formData['PAT_CITY'] = $patient->city;
        $formData['PAT_STATE'] = $patient->state;
        $formData['PAT_ZIP'] = $patient->zip;
        $formData['PAT_AREA_CODE'] = $patient->phone_code;
        $formData['PAT_PHONE'] = $patient->phone;

        if ($patient->patient_relationship_to_insured == 'Self') {
            $formData['BOX6_SELF'] = "Yes";
        } else if ($patient->patient_relationship_to_insured == 'Spouse') {
            $formData['BOX6_SPOUSE'] = "Yes";
        } else if ($patient->patient_relationship_to_insured == 'Child') {
            $formData['BOX6_CHILD'] = "Yes";
        } else if ($patient->patient_relationship_to_insured == 'Other') {
            $formData['BOX6_OTHER'] = "Yes";
        }

        $formData['BOX7'] = $patient->address;
        $formData['INSURED_CITY'] = $patient->city;
        $formData['INSURED_STATE'] = $patient->state;
        $formData['INSURED_ZIP'] = $patient->zip;
        $formData['INSURED_AREA_CODE'] = $patient->phone_code;
        $formData['INSURED_PHONE'] = $patient->phone;
        /*
         * NUCC_BOX8
         */
        $formData['BOX9'] = $patientFullName;
        /*
         * BOX9A
         * NUCC_BOX9B
         * NUCC_BOX9C
         * BOX9D
         */

        /*
         * BOX10A_Y
         * BOX10A_N
         * BOX10B_Y
         * BOX10B_N
         * BOX10_STATE
         * BOX10C_Y
         * BOX10C_N
         * NUCC_BOX10D
         */
        if ($patient->is_employment == "1") {
            $formData['BOX10A_Y'] = "Yes";
        } else {
            $formData['BOX10A_N'] = "Yes";
        }

        if ($patient->is_auto_accident == "1") {
            $formData['BOX10B_Y'] = "Yes";
            $formData['BOX10_STATE'] = $patient->auto_accident_place;
        } else {
            $formData['BOX10B_N'] = "Yes";
        }

        if ($patient->is_other_accident == "1") {
            $formData['BOX10C_Y'] = "Yes";
        } else {
            $formData['BOX10C_N'] = "Yes";
        }

        /**
         * BOX11
         */

        $formData['BOX11A_MM'] = $patientBirthMonth;
        $formData['BOX11A_DD'] = $patientBirthDay;
        $formData['BOX11A_YY'] = $patientBirthYear;
        if ($patient->gender == "Male") {
            $formData['BOX11A_M'] = "Yes";
        } else {
            $formData['BOX11A_F'] = "Yes";
        }
        $formData['NUCC_BOX11B_1'] = "Y4";
        $formData['NUCC_BOX11B_2'] = $patient->insurance_number;
        //BOX11C
        $formData['BOX11D_Y'] = "Yes";
        $formData['BOX12_SIG'] = "Signature On File";
        //BOX12_DATE
        $formData['BOX13'] = "Signature On File";
        if ($visitReportData['current-illness-date'] != "") {
            $currentIllnessDate = $visitReportData['current-illness-date'];
            $currentIllnessTime = strtotime($currentIllnessDate);

            $currentIllnessYear = date('Y', $currentIllnessTime);
            $currentIllnessMonth = date('m', $currentIllnessTime);
            $currentIllnessDay = date('d', $currentIllnessTime);
            $formData['BOX14_MM'] = $currentIllnessMonth;
            $formData['BOX14_DD'] = $currentIllnessDay;
            $formData['BOX14_YY'] = $currentIllnessYear;
            $formData['BOX14_QUAL'] = "431";
        }

        if ($patient->date_of_injury != null && $patient->date_of_injury != "") {
            $otherDate = $patient->date_of_injury;
            $otherDateTime = strtotime($otherDate);

            $otherDateYear = date('Y', $otherDateTime);
            $otherDateMonth = date('m', $otherDateTime);
            $otherDateDay = date('d', $otherDateTime);
            $formData['BOX15_MM'] = $otherDateMonth;
            $formData['BOX15_DD'] = $otherDateDay;
            $formData['BOX15_YY'] = $otherDateYear;
            $formData['BOX15_QUAL'] = "439";
        }

//        if($visitReportData['dates-patient-unable-to-work-from']!=""){
//            $unableToWorkFromDate = $visitReportData['dates-patient-unable-to-work-from'];
//            $unableToWorkFromTime = strtotime($unableToWorkFromDate);
//
//            $unableToWorkFromYear=date('Y',$unableToWorkFromTime);
//            $unableToWorkFromMonth = date('m',$unableToWorkFromTime);
//            $unableToWorkFromDay = date('d',$unableToWorkFromTime);
//            $formData['BOX16_FROM_MM']=$unableToWorkFromMonth;
//            $formData['BOX16_FROM_DD']=$unableToWorkFromDay;
//            $formData['BOX16_FROM_YY']=$unableToWorkFromYear;
//        }
//
//        if($visitReportData['dates-patient-unable-to-work-to']!=""){
//            $unableToWorkToDate = $visitReportData['dates-patient-unable-to-work-to'];
//            $unableToWorkToTime = strtotime($unableToWorkToDate);
//
//            $unableToWorkToYear=date('Y',$unableToWorkToTime);
//            $unableToWorkToMonth = date('m',$unableToWorkToTime);
//            $unableToWorkToDay = date('d',$unableToWorkToTime);
//            $formData['BOX16_TO_MM']=$unableToWorkToMonth;
//            $formData['BOX16_TO_DD']=$unableToWorkToDay;
//            $formData['BOX16_TO_YY']=$unableToWorkToYear;
//        }

        if ($patient->referring_provider_name != null && $patient->referring_provider_name != "") {
            $formData['BOX17_QUAL'] = 'DN';
            $formData['BOX17'] = $patient->referring_provider_name;
            $formData['BOX17A_BOX2'] = $patient->referring_provider_npi;
        }
        $formData['BOX21_ICD'] = "0";
//        var_dump($visitReport->visitIcd10s[0]->customIcd10->icd10->icd10_code);
        $icd10BoxMap = array();
        $icd10BoxMap[0] = 'A';
        $icd10BoxMap[1] = 'B';
        $icd10BoxMap[2] = 'C';
        $icd10BoxMap[3] = 'D';
        $icd10BoxMap[4] = 'E';
        $icd10BoxMap[5] = 'F';
        $icd10BoxMap[6] = 'G';
        $icd10BoxMap[7] = 'H';
        $icd10BoxMap[8] = 'I';
        $icd10BoxMap[9] = 'J';
        $icd10BoxMap[10] = 'K';
        $icd10BoxMap[11] = 'L';
        $i = 0;
        foreach ($visitReport->visitIcd10s as $visitIcd10) {
            if ($i > 11) {
                break;
            }
            $icd10Code = $visitIcd10->customIcd10->icd10->icd10_code;
            $formData['BOX21_' . $icd10BoxMap[$i]] = $icd10Code;
            $i++;
        }
        if ($patient->prior_authorization_number != null && $patient->prior_authorization_number != "") {
            $formData['BOX23'] = $patient->prior_authorization_number;
        }
        $visitBilling = $visitReport->visitBillings[0];
        $visitCpts = $visitBilling->billCpts;
        $visitDate = $visit->visit_date;
        $visitTime = strtotime($visitDate);

        $visitYear = date('y', $visitTime);
        $visitMonth = date('m', $visitTime);
        $visitDay = date('d', $visitTime);


        $i = 1;


        foreach ($visitCpts as $visitCpt) {
            if ($i > 6) {
                break;
            }
            $noOfUnits = $visitCpt->no_of_units;
            $cptCharge = $visitCpt->charge;
            $cptCode = $visitCpt->cpt->cpt->code;
            $totalCptCharge = $noOfUnits * $cptCharge;
            $relatedIcd10Codes = $visitCpt->related_icd10;
            $relatedIcd10CodesArr = explode(",", $relatedIcd10Codes);
            $relatedIcd10CodesStr = "";
            foreach ($relatedIcd10CodesArr as $relatedIcd10Code) {
                if($relatedIcd10Code!=null && $relatedIcd10Code!="" && is_numeric($relatedIcd10Code)){
                    $relatedIcd10CodesStr .= $icd10BoxMap[$relatedIcd10Code-1];
                }
            }

            $formData["BOX24_" . $i . "_FROM_MM"] = $visitMonth;
            $formData["BOX24_" . $i . "
            "] = $visitDay;
            $formData["BOX24_" . $i . "_FROM_YY"] = $visitYear;
            $formData["BOX24_" . $i . "_TO_MM"] = $visitMonth;
            $formData["BOX24_" . $i . "_TO_DD"] = $visitDay;
            $formData["BOX24_" . $i . "_TO_YY"] = $visitYear;
            $formData["BOX24_" . $i . "B"] = "11";
            $formData['BOX24_' . $i . 'D_CPT'] = $cptCode;
            $formData['BOX24_' . $i . 'D_MOD1'] = $visitCpt->identifier1;
            $formData['BOX24_' . $i . 'D_MOD2'] = $visitCpt->identifier2;
            $formData['BOX24_' . $i . 'D_MOD3'] = $visitCpt->identifier3;
            $formData['BOX24_' . $i . 'D_MOD4'] = $visitCpt->identifier4;


            $formData['BOX24_' . $i . 'E'] = $relatedIcd10CodesStr;


            $totalCptChargeDollars = floor($totalCptCharge);
            $totalCptChargeInCents = $totalCptCharge - $totalCptChargeDollars;
            $totalCptChargeInCents = round($totalCptChargeInCents, 2);
            $totalCptChargeInCents = (int)substr($totalCptChargeInCents, 2);;
            if ($totalCptChargeInCents < 10) {
                $totalCptChargeInCents = $totalCptChargeInCents * 10;
            }
            if ($totalCptChargeInCents == 0) {
                $totalCptChargeInCents = "00";
            }

            $formData['BOX24_' . $i . 'F_DOLLAR'] = $totalCptChargeDollars;
            $formData['BOX24_' . $i . 'F_CENT'] = $totalCptChargeInCents;
            $formData['BOX24_' . $i . 'G'] = $noOfUnits;
            $formData['BOX24_' . $i . 'J_NPI'] = $visitReport->doctor->provider_id;


            $i++;
        }


        $organization = Organization::findOne($visitReport->doctor->organization_id);
        $formData['BOX25_ID'] = $organization->federal_tax_id;
        $formData['BOX25_EIN'] = "Yes";
        $formData['BOX26'] = $patient->insurance_number;
        $formData['BOX27_Y'] = "Yes";
        $totalCharge = $visitBilling->cost;

        $totalChargeDollars = floor($totalCharge);
        $totalChargeInCents = $totalCharge - $totalChargeDollars;
        $totalChargeInCents = round($totalChargeInCents, 2);
        $totalChargeInCents = (int)substr($totalChargeInCents, 2);;
        if ($totalChargeInCents < 10) {
            $totalChargeInCents = $totalChargeInCents * 10;
        }
        if ($totalChargeInCents == 0) {
            $totalChargeInCents = "00";
        }

        $formData['BOX28_DOLLAR'] = $totalChargeDollars;
        $formData['BOX28_CENT'] = $totalCptChargeInCents;
        $formData['BOX29_DOLLAR'] = "0";
        $formData['BOX29_CENTS'] = "00";


        $doctorFullName = $visitReport->doctor->name;
        $doctorSpacePos = strpos($doctorFullName, " ");
        $doctorFirstName = trim(substr($doctorFullName, 0, $doctorSpacePos));
        $doctorLastName = trim(substr($doctorFullName, $doctorSpacePos));
        $doctorLastNameFirstName = "$doctorLastName, $doctorFirstName";
        $formData['BOX31_SIGNATURE'] = $doctorLastNameFirstName;
        $todayDate = date('n/j/y');
        $formData['BOX31_DATE'] = $todayDate;

        $visitLocation = $visit->location;
        $formData['BOX32_LINE1'] = $visitLocation->name;
        $formData['BOX32_LINE2'] = $visitLocation->address;
        $formData['BOX32_LINE3'] = $visitLocation->city . ", " . $visitLocation->state . " " . $visitLocation->zip;
        $formData['BOX32_NPI'] = $organization->npi;

        $formData['BOX33_AREA_CODE'] = $organization->phone_code;
        $formData['BOX33_PHONE'] = $organization->phone;
        $formData['BOX33_NAME'] = $organization->name;
        $formData['BOX33_LINE1'] = $organization->address;
        $formData['BOX33_LINE2'] = $organization->city . ", " . $organization->state . " " . $organization->zip;
        $formData['BOX33_NPI'] = $organization->npi;
        $formData['BOX33_PROV'] = '225100000X';


        $pdf->fillForm($formData)->flatten();

        $pdf->send("CMS-" . $visit->uuid . ".pdf");
        exit();


    }


    public function actionNoteReport($id)
    {

        $visitReport = VisitReport::find()->where(['visit_id' => $id])->orderBy(['id' => SORT_DESC])->limit(1)->one();
        $visitReportData = $visitReport->visitReportDatas[0];
        $visitDataJsonStr = $visitReportData->visit_data;
        $visitData = json_decode($visitDataJsonStr, true);
        $outputData = array();
        $templateJsonStr = $visitReportData->visitTemplate->template;
//        $templateObj = Template::findOne(10);
//        $templateJsonStr = $templateObj->template;
        $template = json_decode($templateJsonStr, true);
        $types = array();
        $organization = $visitReport->visit->location->organization;
        $leftHeader = $organization->name . " <br />" . $organization->address;
        $dt = \DateTime::createFromFormat("Y-m-d", $visitReport->visit->patient->birth_date);
        $birthDate = $dt->format('m/d/Y');
        $rightHeader = "Patient:" . $visitReport->visit->patient->name . "<br />Date of Birth:" . $birthDate;

        foreach ($template as $form) {
//            var_dump($form['tabHead']);
            $head = $form['tabHead'];
            $formDataJsonStr = $form['formData'];
            $formData = json_decode($formDataJsonStr, true);
            $tabElements = array();
            foreach ($formData as $formElement) {
                $type = $formElement['type'];
                $label = $formElement['label'];
                if ($type == 'textarea' || $type == 'number' || $type == 'date' || $type == 'text') {
                    $value = $visitData[$formElement['name']];
                    $tabElements[$label] = $value;
                } else if ($type == 'checkbox-group' || $type == 'select' || $type == 'radio-group') {
                    $tempVal = $visitData[$formElement['name']];
                    $value = "";
                    foreach ($formElement['values'] as $formValue) {
                        if (is_array($tempVal)) {
                            foreach ($tempVal as $tVal) {
                                if ($formValue['value'] == $tVal) {
                                    if ($value != "") {
                                        $value .= ", ";
                                    }
                                    $value .= $formValue['label'];
                                }
                            }
                        } else if ($formValue['value'] == $tempVal) {
                            if ($value != "") {
                                $value .= ", ";
                            }
                            $value .= $formValue['label'];
                        }
                    }
                    $tabElements[$label] = $value;
                }
                $types[$formElement['type']] = $formElement['type'];
            }
            $outputData[$head] = $tabElements;
        }
//        var_dump($outputData);
//        exit();
        $visitBillId = $visitReport->visitBillings[0]->id;
        $cptDataProvider = new ActiveDataProvider([
            'query' => BillCpt::find(),
        ]);
        $cptDataProvider->query->andFilterWhere(['visit_bill_id' => $visitBillId]);
        $cptDataProvider->sort = false;

        $icd10DataProvider = new ActiveDataProvider([
            'query' => VisitIcd10::find(),
        ]);

        $icd10DataProvider->query->andFilterWhere(['visit_report_id' => $visitReport->id]);

        $content = $this->renderPartial('_reportView',
            ["model" => $visitReport,
                "icd10s" => $icd10DataProvider,
                "cpts" => $cptDataProvider,
                'outputData' => $outputData,
            ]
        );


        // setup kartik\mpdf\Pdf component
        $pdf = new \kartik\mpdf\Pdf([
            // set to use core fonts only
            'mode' => \kartik\mpdf\Pdf::MODE_CORE,
            // A4 paper format
            'format' => \kartik\mpdf\Pdf::FORMAT_A4,
            // portrait orientation
            'orientation' => \kartik\mpdf\Pdf::ORIENT_PORTRAIT,
            // stream to browser inline
            'destination' => \kartik\mpdf\Pdf::DEST_BROWSER,
            // your html content input
            'content' => $content,
            // format content from your own css file if needed or use the
            // enhanced bootstrap css built by Krajee for mPDF formatting
            'cssFile' => '@vendor/kartik-v/yii2-mpdf/src/assets/kv-mpdf-bootstrap.min.css',
            // any css to be embedded if required
            'cssInline' => '.kv-heading-1{font-size:18px} h2{color:#163a5f;} hr{color:#45eba5;} th{background-color: red;color:#45eba5;}',
            // set mPDF properties on the fly
            'options' => ['title' => 'COB Note Report'],
            // call mPDF methods on the fly
            'methods' => [
                'SetHeader' => "$leftHeader|Medical Note Report|$rightHeader",
                'SetFooter' => "{PAGENO}|  <img src='images/logo-small.png' /> | COB",
            ]
        ]);

        return $pdf->render();
    }

    /**
     * Creates a new Visit model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate($patient = 0)
    {
        $patientModel = null;
        if ($patient != 0) {
            $patientModel = \app\models\Patient::findOne(['id' => $patient]);
        }
        $model = new Visit();

        if ($model->load(Yii::$app->request->post())) {
//    var_dump(Yii::$app->request->post());
//    exit();
            if ($model->visit_date != null && $model->visit_date != "") {
                $dt = \DateTime::createFromFormat('m/d/Y G:i', $model->visit_date)->format('Y-m-d H:i:s');
                $model->visit_date = $dt;

            }
            $model->uuid = \thamtech\uuid\helpers\UuidHelper::uuid();

            if ($model->save()) {
                return $this->redirect(['view', 'id' => $model->id]);
            }
        }

        return $this->render('create', [
            'model' => $model,
            'patientModel' => $patientModel,
        ]);
    }

    /**
     * Updates an existing Visit model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);

        if ($model->load(Yii::$app->request->post())) {

            if ($model->visit_date != null && $model->visit_date != "") {
                $dt = \DateTime::createFromFormat('m/d/Y G:i', $model->visit_date)->format('Y-m-d H:i:s');
                $model->visit_date = $dt;
            }
            if ($model->save()) {
                return $this->redirect(['view', 'id' => $model->id]);
            }
        }

        return $this->render('update', [
            'model' => $model,
        ]);
    }

    /**
     * Deletes an existing Visit model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
//    public function actionDelete($id) {
//        $this->findModel($id)->delete();
//
//        return $this->redirect(['index']);
//    }

    public function actionPaid($id)
    {
        $model = $this->findModel($id);
        $model->status = \app\models\VisitStatus::PAID;
        $model->save();
        return $this->redirect(['index']);
    }

    public function actionCancel($id)
    {
        $model = $this->findModel($id);
        $model->status = \app\models\VisitStatus::CANCELED;
        $model->save();
        return $this->redirect(['index']);
    }

    public function actionCheckin($id)
    {
        $model = $this->findModel($id);
        $model->status = \app\models\VisitStatus::CHECKED_IN;
        $model->save();
        return $this->redirect(['index']);
    }

    public function actionApproveInsurance($id)
    {
        $model = $this->findModel($id);
        $model->insurance_payment_status = "Approved";
        $model->save();
        return $this->redirect(['index']);
    }

    public function actionRejectInsurance($id)
    {
        $model = $this->findModel($id);
        $model->insurance_payment_status = "Declined";
        $model->save();
        return $this->redirect(['index']);
    }

    public function actionPartialApproveInsurance($id)
    {
        $model = $this->findModel($id);
        $model->insurance_payment_status = "Partialy Approved";
        $model->save();
        return $this->redirect(['index']);
    }

    public function actionPatient($id)
    {
        $patient = \app\models\Patient::findOne(["id" => $id]);
        $dataProvider = new ActiveDataProvider([
            'query' => Visit::find()
        ]);
        $dataProvider->query->andFilterWhere(['patient_id' => $id]);
        $count = 0;

        return $this->render('index', [
            'dataProvider' => $dataProvider,
            'patient' => $patient,
        ]);
    }

    public function actionChangeLocation($id)
    {
        $user = \app\models\Login::findOne(array("id" => Yii::$app->user->identity->id));
        $user->selected_location = $id;
        $user->save();
        return $this->redirect(['index']);
    }

    /**
     * Finds the Visit model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Visit the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Visit::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }

    protected function insertVisitNoteDetails($id)
    {
        $model = $this->findModel($id);
        $cptIds = Yii::$app->request->post()['cpt-ids'];
        $cptUnits = Yii::$app->request->post()['cpt-units'];
        $cptCharges = Yii::$app->request->post()['cpt-charges'];
        $cptRelatedIcd10s = Yii::$app->request->post()['related-icd10s'];
        $billTotalRate = Yii::$app->request->post()['total-rate'];
//        $insuranceCharge = Yii::$app->request->post()['insuracne-charge'];
//        $patientCharge = Yii::$app->request->post()['patient-charge'];
        $icd10Ids = Yii::$app->request->post()['idc10-ids'];
        $templateId = Yii::$app->request->post()['template'];
        $modifiers1 = Yii::$app->request->post()['cpt-modifier1'];
        $modifiers2 = Yii::$app->request->post()['cpt-modifier2'];
        $modifiers3 = Yii::$app->request->post()['cpt-modifier3'];
        $modifiers4 = Yii::$app->request->post()['cpt-modifier4'];

        $postParams = Yii::$app->request->post();
        unset($postParams['cpt-ids']);
        unset($postParams['cpt-units']);
        unset($postParams['cpt-charges']);
        unset($postParams['related-icd10s']);
        unset($postParams['total-rate']);
        unset($postParams['insuracne-charge']);
        unset($postParams['patient-charge']);
        unset($postParams['idc10-ids']);
        unset($postParams['idc10-codes']);
        unset($postParams['idc10-descriptions']);
        unset($postParams['cpt-codes']);
        unset($postParams['cpt-descriptions']);
        unset($postParams['Visit']);
        unset($postParams['template']);
        unset($postParams['_csrf']);
        unset($postParams['bill-cpt-ids']);
        unset($postParams['cpt-modifier1']);
        unset($postParams['cpt-modifier2']);
        unset($postParams['cpt-modifier3']);
        unset($postParams['cpt-modifier4']);
        unset($postParams['visit-idc10-ids']);
        unset($postParams['removed-icd10s']);


        $visitReport = new VisitReport();
        $visitReport->doctor_id = Yii::$app->user->identity->id;
        $visitReport->visit_id = $id;
        $visitReport->save();
        $visitReportId = $visitReport->id;

        $encodedParams = json_encode($postParams);
        $visitData = new VisitReportData();
        $visitData->visit_report_id = $visitReportId;
        $visitData->visit_template_id = $templateId;
        $visitData->visit_data = $encodedParams;
        $visitData->save();
        if ($icd10Ids != null) {
            foreach ($icd10Ids as $icd10id) {
                $visitIcd10 = new VisitIcd10();
                $visitIcd10->visit_report_id = $visitReportId;
                $visitIcd10->custom_icd10_id = $icd10id;
                $visitIcd10->save();
            }
        }
        $visitBilling = new VisitBilling();
        $visitBilling->visit_report_id = $visitReportId;
        $visitBilling->cost = $billTotalRate;
        $visitBilling->insurance_charge = 0;
        $visitBilling->patient_charge = 0;

        if ($visitBilling->save()) {
            BillingPostCpt::deleteAll(["visit_id"=>$id]);
            for ($i = 0; $i < count($cptIds); $i++) {
                $billCpt = new BillCpt();
                $billCpt->visit_bill_id = $visitBilling->id;
                $billCpt->cpt_id = $cptIds[$i];
                $billCpt->no_of_units = $cptUnits[$i];
                $billCpt->charge = $cptCharges[$i];
                $billCpt->related_icd10 = $cptRelatedIcd10s[$i];
                $billCpt->identifier1 = $modifiers1[$i];
                $billCpt->identifier2 = $modifiers2[$i];
                $billCpt->identifier3 = $modifiers3[$i];
                $billCpt->identifier4 = $modifiers4[$i];
                $billCpt->save();
                $billingPostCpt = new BillingPostCpt();
                $billingPostCpt->adjustment=0;
                $billingPostCpt->balance=0;
                $billingPostCpt->visit_id = $id;
                $billingPostCpt->visit_cpt_id = $billCpt->id;
                $billingPostCpt->charged=0;
                $billingPostCpt->payment=0;
                $billingPostCpt->session_action = "Pending";
                $billingPostCpt->save();
            }
        }

        $model->status = VisitStatus::COMPLETED;
        if ($model->patient->insurance_id != null && $model->patient->insurance_id != "") {
            $model->insurance_payment_status = "Pending";
        }
        $model->save();
    }

}

