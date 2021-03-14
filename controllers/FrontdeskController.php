<?php

namespace app\controllers;

use Yii;
use app\models\Login;
use yii\data\ActiveDataProvider;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use app\models\OrganizationLocation;

/**
 * FrontdeskController implements the CRUD actions for Login model.
 */
class FrontdeskController extends Controller
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
     * Lists all Login models.
     * @return mixed
     */
    public function actionIndex()
    {
        
        
        
        $organizationId = Yii::$app->user->identity->organization_id;
        $dataProvider = new ActiveDataProvider([
            'query' => Login::find()->where(["type" => "Front Desk"]),
        ]);
        $dataProvider->query->andFilterWhere(['organization_id'=>$organizationId]);
        

        return $this->render('index', [
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single Login model.
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
     * Creates a new Login model.
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
            $login->type = 'Front Desk';
            $login->organization_id = Yii::$app->user->identity->organization_id;
            if ($login->validatePassword($login->password, $passwordConfirmation)) {
                $login->password = sha1($login->password);
                if ($login->save()) {
                    foreach ($locations as $location){
                        $loginLocation = new \app\models\LoginLocation();
                        $loginLocation->location_id = $location;
                        $loginLocation->login_id = $login->id;
                        if($loginLocation->save()){
                            
                        }else{
//                            var_dump($loginLocation->errors);
                        }
                        
                    }
                    return $this->redirect(['view', 'id' => $login->id]);
                }
            }
        }
        $locations = $organizationLocation->findAll(["organization_id"=> Yii::$app->user->identity->organization_id]);
        return $this->render('create', [
                    'model' => $login,
                    'locations'=>$locations,
        ]);
    }

    /**
     * Updates an existing Login model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionUpdate($id)
    {
        $organizationLocation = new OrganizationLocation();
        $locations = $organizationLocation->findAll(["organization_id"=> Yii::$app->user->identity->organization_id]);
        $model = $this->findModel($id);

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->id]);
        }

        return $this->render('update', [
            'model' => $model,
            'locations'=>$locations,
        ]);
    }

    /**
     * Deletes an existing Login model.
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
     * Finds the Login model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Login the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Login::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }
}
