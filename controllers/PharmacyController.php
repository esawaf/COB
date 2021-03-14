<?php

namespace app\controllers;

use Yii;
use app\models\Pharmacy;
use app\models\Login;
use app\models\PharmacyLocation;
use yii\data\ActiveDataProvider;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;

/**
 * PharmacyController implements the CRUD actions for Pharmacy model.
 */
class PharmacyController extends Controller
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
            'access' => [
                'class' => \yii\filters\AccessControl::className(),
                'only' => ['index', 'create', 'update', 'view', 'delete'],
                'ruleConfig' => ['class' => \app\components\AccessRule::className()],
                'rules' => [
                    // allow authenticated users
                    [
                        'allow' => true,
                        'roles' => ['Admin'],
                    ],
                ],
            ],
        ];
    }

    /**
     * Lists all Pharmacy models.
     * @return mixed
     */
    public function actionIndex()
    {
        $dataProvider = new ActiveDataProvider([
            'query' => Pharmacy::find(),
        ]);

        return $this->render('index', [
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single Pharmacy model.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionView($id)
    {
        return $this->render('view', [
            'model' => $this->findModel($id),
        ]);
    }

    /**
     * Creates a new Pharmacy model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new Pharmacy();
        $login = new Login();
        $pharmacyLocation = new PharmacyLocation();

        if ($model->load(Yii::$app->request->post()) && $pharmacyLocation->load(Yii::$app->request->post()) && $login->load(Yii::$app->request->post())) {
            $passwordConfirmation = Yii::$app->request->post()["password_conf"];
            $login->active = 1;
            $login->confirmed = 1;
            $login->type = 'Pharmacy';
            if ($login->validatePassword($login->password, $passwordConfirmation)) {
                $login->password = sha1($login->password);
                if ($model->save()) {
                    $login->organization_id = $model->id;
                    if ($login->save()) {
                        $pharmacyLocation->pharmacy_id = $model->id;
                        if ($pharmacyLocation->save()) {
                            return $this->redirect(['view', 'id' => $model->id]);
                        }
                    }
                }
            }
        }
        return $this->render('create', [
                    'model' => $model,
                    'login' => $login,
                    'pharmacyLocation' => $pharmacyLocation,
        ]);
        
        
        
        
    }

    /**
     * Updates an existing Pharmacy model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->id]);
        }

        return $this->render('update', [
            'model' => $model,
        ]);
    }
    
    public function actionAddLocation() {
        $pharmacyLocation = new PharmacyLocation();
        if($pharmacyLocation->load(Yii::$app->request->post())){
            
            $pharmacyLocation->pharmacy_id =Yii::$app->user->identity->organization_id;
            if($pharmacyLocation->save()){
                return $this->redirect(['location', 'id' => $pharmacyLocation->id]);
            }
        }
        return $this->render('add-location', [
                    'pharmacyLocation' => $pharmacyLocation,
        ]);
    }

    /**
     * Deletes an existing Pharmacy model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionDelete($id)
    {
        $this->findModel($id)->delete();

        return $this->redirect(['index']);
    }

    /**
     * Finds the Pharmacy model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Pharmacy the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Pharmacy::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }
}
