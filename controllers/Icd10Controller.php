<?php

namespace app\controllers;

use app\models\Cpt;
use app\models\CustomCpt;
use app\models\Login;
use Yii;
use app\models\Icd10;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use app\models\CustomIcd10;

/**
 * Icd10Controller implements the CRUD actions for Icd10 model.
 */
class Icd10Controller extends Controller
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
     * Lists all Icd10 models.
     * @return mixed
     */
    public function actionIndex()
    {
        
        $searchModel = new Icd10();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);
        return $this->render('index', [
            'dataProvider' => $dataProvider,
            'searchModel' => $searchModel,
        ]);
    }

    /**
     * Displays a single Icd10 model.
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
     * Creates a new Icd10 model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new Icd10();

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->id]);
        }

        return $this->render('create', [
            'model' => $model,
        ]);
    }

    /**
     * Updates an existing Icd10 model.
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

    public function actionSync(){
        $action = Yii::$app->request->get('action');
        $message = "";
        if($action!=null){
            $doctors = Login::find()->where(['type'=>"Doctor"])->all();
            if($action=="icd10"){
                $icd10Codes = Icd10::find()->all();
                foreach ($doctors as $doctor){
                    $doctorId = $doctor->id;
                    foreach ($icd10Codes as $icd10Code){
                        $icd10Id = $icd10Code->id;
//                        var_dump($icd10Id);
                        $count = CustomIcd10::find()->where(['login_id'=>$doctorId,'icd10_id'=>$icd10Id])->count();
                        if($count==0){
                            $customIcd10 = new CustomIcd10();
                            $customIcd10->login_id = $doctorId;
                            $customIcd10->icd10_id = $icd10Id;
                            $customIcd10->custom_id = $icd10Code->icd10_code;
                            $customIcd10->custom_description = $icd10Code->description;
                            $customIcd10->save();
                        }
                    }
                }
                $message = "The ICD10 Codes has been successfully synchronized.";
            }else if($action=="cpt"){
                $cptCodes = Cpt::find()->all();
                foreach ($doctors as $doctor){
                    $doctorId = $doctor->id;
                    foreach ($cptCodes as $cptCode){
                        $cptId = $cptCode->id;
                        $count = CustomCpt::find()->where(['doctor_id'=>$doctorId,'cpt_id'=>$cptId])->count();
                        if($count==0){
                            $customCpt = new CustomCpt();
                            $customCpt->doctor_id = $doctorId;
                            $customCpt->cpt_id = $cptId;
                            $customCpt->custom_code = $cptCode->code;
                            $customCpt->custom_description = $cptCode->description;
                            $customCpt->charge=200;
                            $customCpt->save();
                        }
                    }
                }
                $message = "The CPT Codes has been successfully synchronized.";
            }
        }
        return $this->render('sync', ["message"=>$message
        ]);
    }
    

    /**
     * Finds the Icd10 model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Icd10 the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Icd10::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }
    
}
