<?php

namespace app\controllers;

use Yii;
use app\models\OrganizationInsurance;
use yii\data\ActiveDataProvider;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;

/**
 * OrganizationInsuracneController implements the CRUD actions for OrganizationInsurance model.
 */
class OrganizationInsuranceController extends Controller {

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
     * Lists all OrganizationInsurance models.
     * @return mixed
     */
    public function actionIndex() {

        return $this->loadGridView();
    }

    public function actionPending() {
        return $this->loadGridView("Pending");
    }

    public function actionRejected() {
        return $this->loadGridView("Rejected");
    }

    public function actionApproved() {
        return $this->loadGridView("Approved");
    }

    public function actionConfirm($id) {
        $this->changeStatus($id, "Approved");
    }

    public function actionReject($id) {
        $this->changeStatus($id, "Rejected");
    }

    /**
     * Displays a single OrganizationInsurance model.
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
     * Creates a new OrganizationInsurance model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate() {
        $model = new OrganizationInsurance();

        if ($model->load(Yii::$app->request->post())) {
            $model->organization_id = Yii::$app->user->identity->organization_id;
//            var_dump(Yii::$app->user->identity);
            $model->status = "Pending";
//            var_dump($model);
            if ($model->save()) {
                return $this->redirect(['view', 'id' => $model->id]);
            } else {
//                var_dump($model->errors);
//                exit();
            }
        }

        return $this->render('create', [
                    'model' => $model,
        ]);
    }

    /**
     * Updates an existing OrganizationInsurance model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionUpdate($id) {
        $model = $this->findModel($id);

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->id]);
        }

        return $this->render('update', [
                    'model' => $model,
        ]);
    }

    /**
     * Deletes an existing OrganizationInsurance model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionDelete($id) {
        $this->findModel($id)->delete();

        return $this->redirect(['index']);
    }

    /**
     * Finds the OrganizationInsurance model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return OrganizationInsurance the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id) {
        if (($model = OrganizationInsurance::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }

    protected function changeStatus($id, $status) {
        $model = $this->findModel($id);
        $model->status = $status;
        $model->save();
        return $this->redirect(['index']);
    }

    protected function loadGridView($status="") {
        $searchModel = new OrganizationInsurance();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        if (Yii::$app->user->identity->insurance_id != null) {
            $dataProvider->query->andWhere(['insurance_company_id' => Yii::$app->user->identity->insurance_id]);
        } else if (Yii::$app->user->identity->organization_id) {
            $dataProvider->query->andWhere(['organization_id' => Yii::$app->user->identity->organization_id]);
        }
        
        if($status!=""){
            $dataProvider->query->andWhere(['status' => $status]);
        }

        return $this->render('index', [
                    'dataProvider' => $dataProvider,
                    'searchModel' => $searchModel,
        ]);
    }

}
