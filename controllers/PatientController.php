<?php

namespace app\controllers;

use Yii;
use app\models\Patient;
use yii\data\ActiveDataProvider;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\web\UploadedFile;


/**
 * PatientController implements the CRUD actions for Patient model.
 */
class PatientController extends Controller {

    /**
     * {@inheritdoc}
     */
    public function behaviors() {
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
     * Lists all Patient models.
     * @return mixed
     */
    public function actionIndex() {
//        $dataProvider = new ActiveDataProvider([
//            'query' => Patient::find(),
//        ]);

        $searchModel = new Patient();

        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);
        if (Yii::$app->user->identity->type == "Insurance Profile") {
            $dataProvider->query->andFilterWhere(['insurance_id' => Yii::$app->user->identity->insurance_id]);
        }
        $dataProvider->query->joinWith(['insurance'], true);
        if (Yii::$app->user->identity->organization_id != null) {
            $insuranceCompanties = \app\models\InsuranceCompany::find()
                            ->select('insurance_company.id')
                            ->joinWith('organizationInsurances')
                            ->andWhere(['organization_insurance.organization_id' => Yii::$app->user->identity->organization_id])
                            ->andWhere(['organization_insurance.status' => 'Approved'])->all();

            $insuracneCompanyIds = array();
            foreach ($insuranceCompanties as $insuranceCompany) {
                $insuracneCompanyIds[] = $insuranceCompany->id;
            }
            $dataProvider->query->andWhere( ['or',['insurance_number' => ""],[ 'insurance_id' => $insuracneCompanyIds]]);
        }
        return $this->render('index', [
                    'dataProvider' => $dataProvider,
                    'searchModel' => $searchModel,
        ]);
    }

    public function actionPendingInsurance() {
        $searchModel = new Patient();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);
        if (Yii::$app->user->identity->type == "Insurance Profile") {
            $dataProvider->query->andFilterWhere(['insurance_id' => Yii::$app->user->identity->insurance_id]);
        }
        $dataProvider->query->andFilterWhere(['insurance_status' => "Pending"]);
        return $this->render('index', [
                    'dataProvider' => $dataProvider,
                    'searchModel' => $searchModel,
        ]);
    }

    public function actionApproveInsurance($id) {
        $this->updateInsruranceStatus($id, "Approved");
    }

    public function actionMarkPendingInsurance($id) {
        $this->updateInsruranceStatus($id, "Pending");
    }

    public function actionRejectInsurance($id) {
        $this->updateInsruranceStatus($id, "Rejected");
    }

    public function actionCancelInsurance($id) {
        $this->updateInsruranceStatus($id, "Canceled");
    }

    /**
     * Displays a single Patient model.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionView($id) {
        return $this->render('view', [
                    'model' => $this->findModel($id),
        ]);
    }

    /**
     * Creates a new Patient model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate() {
        $model = new Patient();

        if ($model->load(Yii::$app->request->post())) {
            $birthDate = Yii::$app->request->post()['birthdate'];
            $model->birth_date = $birthDate['year']."-".$birthDate['month']."-".$birthDate['day'];

            if($model->insurance_expiry_date!=null && $model->insurance_expiry_date!=""){
                $insuranceExpiryDate = \DateTime::createFromFormat('m/d/Y',$model->insurance_expiry_date);
                $model->insurance_expiry_date = $insuranceExpiryDate->format('Y-m-d');
            }
            if($model->date_of_injury!=null && $model->date_of_injury!=""){
                $dateOfInjury = \DateTime::createFromFormat('m/d/Y',$model->date_of_injury);
                $model->date_of_injury = $dateOfInjury->format('Y-m-d');
            }
            if ($model->insurance_id != null) {
                $model->insurance_status = "Pending";
            }
            if ($model->save()) {
                return $this->redirect(['view', 'id' => $model->id]);
            }
        }

        return $this->render('create', [
                    'model' => $model,
        ]);
    }

    /**
     * Updates an existing Patient model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionUpdate($id) {
        $model = $this->findModel($id);

        if ($model->load(Yii::$app->request->post())) {
            $birthDate = Yii::$app->request->post()['birthdate'];
            $model->birth_date = $birthDate['year']."-".$birthDate['month']."-".$birthDate['day'];

            if($model->insurance_expiry_date!=null && $model->insurance_expiry_date!=""){
                $insuranceExpiryDate = \DateTime::createFromFormat('m/d/Y',$model->insurance_expiry_date);
                $model->insurance_expiry_date = $insuranceExpiryDate->format('Y-m-d');
            }

            if($model->date_of_injury!=null && $model->date_of_injury!=""){
                $dateOfInjury = \DateTime::createFromFormat('m/d/Y',$model->date_of_injury);
                $model->date_of_injury = $dateOfInjury->format('Y-m-d');
            }
            if($model->save()){
                return $this->redirect(['view', 'id' => $model->id]);
            }
        }

        return $this->render('update', [
                    'model' => $model,
        ]);
    }

    /**
     * Deletes an existing Patient model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionDelete($id) {
        $this->findModel($id)->delete();

        return $this->redirect(['index']);
    }

    public function actionAddEdoc($id) {
//        var_dump(Yii::$app->request->files());

        $model = new \app\models\PatientEdoc();
        $uploadForm = new \app\models\UploadForm();
        if ($model->load(Yii::$app->request->post())) {
            $uploadForm->edocFiles = UploadedFile::getInstances($uploadForm, 'edocFiles');
            if ($uploadForm->upload()) {
                $filePaths = $uploadForm->edocFilePaths;
                foreach ($filePaths as $filePath) {
                    $patientEdoc = new \app\models\PatientEdoc();
                    $patientEdoc->name = $model->name;
                    $patientEdoc->date = Yii::$app->formatter->asDate($model->date, 'php:Y-m-d');
                    $patientEdoc->file_path = $filePath;
                    $patientEdoc->patient_id = $id;
                    $patientEdoc->save();
                }
                return $this->redirect(['view', 'id' => $id]);
            }
        }

        return $this->render('add-edoc', [
                    'model' => $model,
                    'uploadForm' => $uploadForm,
        ]);
    }

    public function actionEdocs($id) {
        $dataProvider = new ActiveDataProvider([
            'query' => \app\models\PatientEdoc::find(),
        ]);

        $dataProvider->query->andFilterWhere(['patient_id' => $id]);

        return $this->render('edocs', [
                    'dataProvider' => $dataProvider,
        ]);
    }

    public function actionUpdatePlanList() {
        $insuranceId = Yii::$app->request->post()['inid'];
        $model = new Patient();
        $insurancePlan = new \app\models\InsurancePlan();
        $plans = $insurancePlan->findAll(['insurance_id' => $insuranceId]);
        $items = \yii\helpers\ArrayHelper::map($plans, 'id', 'plan_name');
        $out = '<div class="form-group field-insurance-plan has-success">';
        $out .= '<label class="control-label" for="patient-insurance_plan_id">Insurance Plan</label>';
        $out .= \yii\helpers\Html::activeDropDownList($model, 'insurance_plan_id', $items, ['class' => 'form-control']);
        $out .= '</div>';
        return $out;
    }

    /**
     * Finds the Patient model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Patient the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id) {
        if (($model = Patient::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }

    protected function updateInsruranceStatus($id, $status) {
        $model = $this->findModel($id);
        $model->insurance_status = $status;
        $model->save();
        return $this->redirect(['view', 'id' => $model->id]);
    }

}
