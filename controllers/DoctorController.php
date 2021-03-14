<?php

namespace app\controllers;

use app\models\Cpt;
use app\models\CustomCpt;
use app\models\CustomIcd10;
use app\models\Icd10;
use Yii;
use app\models\Login;
use app\models\OrganizationLocation;
use yii\data\ActiveDataProvider;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;

/**
 * DoctorController implements the CRUD actions for Doctor model.
 */
class DoctorController extends Controller
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
                        'roles' => ['Organization'],
                    ],
                    // everything else is denied
                ],
            ],
        ];
    }

    /**
     * Lists all Doctor models.
     * @return mixed
     */
    public function actionIndex()
    {
        $organizationId = Yii::$app->user->identity->organization_id;
        $dataProvider = new ActiveDataProvider([
            'query' => Login::find()->where(["type" => "Doctor"]),
        ]);
        $dataProvider->query->andFilterWhere(['organization_id' => $organizationId]);

        return $this->render('index', [
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single Doctor model.
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
     * Creates a new Doctor model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $login = new Login();
        $organizationLocation = new OrganizationLocation();
        if ($login->load(Yii::$app->request->post())) {
            $passwordConfirmation = Yii::$app->request->post()["password_conf"];
            $locations = Yii::$app->request->post()["locations"];
            $login->active = 1;
            $login->confirmed = 1;
            $login->type = 'Doctor';
            $login->organization_id = Yii::$app->user->identity->organization_id;
            if ($login->validatePassword($login->password, $passwordConfirmation)) {
                $login->password = sha1($login->password);
                if ($login->save()) {
                    foreach ($locations as $location) {
                        $loginLocation = new \app\models\LoginLocation();
                        $loginLocation->location_id = $location;
                        $loginLocation->login_id = $login->id;
                        if ($loginLocation->save()) {

                        } else {
//                            var_dump($loginLocation->errors);
                        }

                    }


                    $this->addICd10CodesToDoctor($login->id);
                    $this->addCptCodesToDoctor($login->id);

                    return $this->redirect(['view', 'id' => $login->id]);
                }
            }
        }
        $locations = $organizationLocation->findAll(["organization_id" => Yii::$app->user->identity->organization_id]);
        return $this->render('create', [
            'model' => $login,
            'locations' => $locations,
        ]);
    }

    /**
     * Updates an existing Doctor model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);
        $organizationLocation = new OrganizationLocation();
        $locations = $organizationLocation->findAll(["organization_id" => Yii::$app->user->identity->organization_id]);

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->id]);
        }

        return $this->render('update', [
            'model' => $model,
            'locations' => $locations,
        ]);
    }

    /**
     * Deletes an existing Doctor model.
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
     * Finds the Doctor model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Doctor the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Login::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }

    protected function addICd10CodesToDoctor($doctorId)
    {
        $icd10Codes = Icd10::find()->all();
        foreach ($icd10Codes as $icd10Code) {
            $icd10Id = $icd10Code->id;
            $customIcd10 = new CustomIcd10();
            $customIcd10->login_id = $doctorId;
            $customIcd10->icd10_id = $icd10Id;
            $customIcd10->custom_id = $icd10Code->icd10_code;
            $customIcd10->custom_description = $icd10Code->description;
            $customIcd10->save();
        }

    }

    protected function addCptCodesToDoctor($doctorId)
    {
        $cptCodes = Cpt::find()->all();
        foreach ($cptCodes as $cptCode) {
            $cptId = $cptCode->id;
            $customCpt = new CustomCpt();
            $customCpt->doctor_id = $doctorId;
            $customCpt->cpt_id = $cptId;
            $customCpt->custom_code = $cptCode->code;
            $customCpt->custom_description = $cptCode->description;
            $customCpt->charge = 200;
            $customCpt->save();
        }
    }

}
