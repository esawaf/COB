<?php

namespace app\controllers;

use Yii;
use app\models\Organization;
use app\models\Login;
use app\models\OrganizationLocation;
use yii\data\ActiveDataProvider;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;

/**
 * OrganizationController implements the CRUD actions for Organization model.
 */
class OrganizationController extends Controller {

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
            [
                'class' => \yii\filters\AccessControl::className(),
                'only' => ['add-location', 'locations', 'update-location', 'location'],
                'ruleConfig' => ['class' => \app\components\AccessRule::className()],
                'rules' => [
                    // allow authenticated users
                    [
                        'allow' => true,
                        'roles' => ['Organization'],
                    ],
                ],
            ],
        ];
    }

    /**
     * Lists all Organization models.
     * @return mixed
     */
    public function actionIndex() {
        $dataProvider = new ActiveDataProvider([
            'query' => Organization::find(),
        ]);

        return $this->render('index', [
                    'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single Organization model.
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
     * Creates a new Organization model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate() {
        $organization = new Organization();
        $login = new Login();
        $organizationLocation = new OrganizationLocation();
        if ($organization->load(Yii::$app->request->post()) && $organizationLocation->load(Yii::$app->request->post()) && $login->load(Yii::$app->request->post())) {
            $passwordConfirmation = Yii::$app->request->post()["password_conf"];
            $login->active = 1;
            $login->confirmed = 1;
            $login->type = 'Organization';
            $login->name = $organization->name;
            if ($login->validatePassword($login->password, $passwordConfirmation)) {
                $login->password = sha1($login->password);
                if ($organization->save()) {
                    $login->organization_id = $organization->id;
                    if ($login->save()) {
                        $organizationLocation->organization_id = $organization->id;
                        if ($organizationLocation->save()) {
                            return $this->redirect(['view', 'id' => $organization->id]);
                        }
                    }
                }
            }
        }
        return $this->render('create', [
                    'organization' => $organization,
                    'login' => $login,
                    'organizationLocation' => $organizationLocation,
        ]);
    }

    /**
     * Updates an existing Organization model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionUpdate($id) {
        $organization = $this->findModel($id);
        $login = Login::find()->where(["type" => "Organization", "organization_id" => $id])->one();
        $organizationLocation = OrganizationLocation::find()->where(["organization_id" => $id])->one();
        if ($organization->load(Yii::$app->request->post()) && $organization->save()) {
            $organizationLocation->load(Yii::$app->request->post());
            $organizationLocation->save();
            $login->load(Yii::$app->request->post());
            $login->name = $organization->name;
            $login->save();
            return $this->redirect(['view', 'id' => $organization->id]);
        }

        return $this->render('update', [
                    'organization' => $organization,
                    'login' => $login,
                    'organizationLocation' => $organizationLocation,
        ]);
    }
    
    public function actionAddLocation() {
        $organizationLocation = new OrganizationLocation();
        if($organizationLocation->load(Yii::$app->request->post())){
            
            $organizationLocation->organization_id=Yii::$app->user->identity->organization_id;
            if($organizationLocation->save()){
                return $this->redirect(['location', 'id' => $organizationLocation->id]);
            }
        }
        return $this->render('add-location', [
                    'organizationLocation' => $organizationLocation,
        ]);
    }

    public function actionUpdateLocation($id){
        $organizationLocation = OrganizationLocation::findOne($id);
        if($organizationLocation->load(Yii::$app->request->post())){
            if($organizationLocation->save()){
                return $this->redirect(['location', 'id' => $organizationLocation->id]);
            }
        }
        return $this->render('update-location', [
            'organizationLocation' => $organizationLocation,
        ]);
    }
    
    public function actionLocation($id) {
        return $this->render('location', [
                    'model' => $this->findLocation($id),
        ]);
    }
    
    public function actionLocations() {
        $locations = OrganizationLocation::find()->where(["organization_id"=>Yii::$app->user->identity->organization_id]);
        
        $dataProvider = new ActiveDataProvider([
            'query' => $locations,
        ]);

        return $this->render('locations', [
                    'dataProvider' => $dataProvider,
        ]);
        
    }

    /**
     * Deletes an existing Organization model.
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
     * Finds the Organization model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Organization the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id) {
        if (($model = Organization::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }
    
    
    protected function findLocation($id) {
        if (($model = OrganizationLocation::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }
    
    

}
