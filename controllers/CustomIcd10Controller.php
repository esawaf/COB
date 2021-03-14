<?php

namespace app\controllers;

use Yii;
use app\models\CustomIcd10;
use yii\data\ActiveDataProvider;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;

/**
 * CustomIcd10Controller implements the CRUD actions for CustomIcd10 model.
 */
class CustomIcd10Controller extends Controller
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
     * Lists all CustomIcd10 models.
     * @return mixed
     */
    public function actionIndex()
    {
        $dataProvider = new ActiveDataProvider([
            'query' => CustomIcd10::find(),
        ]);
        $dataProvider->query->andFilterWhere(['login_id'=> Yii::$app->user->identity->id]);

        return $this->render('index', [
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single CustomIcd10 model.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionView($id)
    {  
        $model = $this->findModel($id);
        $originalIcd10 = $this->findOriginalModel($model->icd10_id);
        return $this->render('view', [
            'model' => $model,
            'originalIcd10' => $originalIcd10,
        ]);
    }

    /**
     * Creates a new CustomIcd10 model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate($id)
    {
        $icd10 = $this->findOriginalModel($id);
        $customIdc10 = new CustomIcd10();
        if ($customIdc10->load(Yii::$app->request->post())) {
            $customIdc10->icd10_id = $id;
            $customIdc10->login_id = Yii::$app->user->identity->id;
            if($customIdc10->save()){
                return $this->redirect(['view', 'id' => $customIdc10->id]);
            }
        }
        return $this->render('create', [
            'icd10' => $icd10,
            'customIcd10' => $customIdc10,
        ]);
    }

    /**
     * Updates an existing CustomIcd10 model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionUpdate($id)
    {
        $customIcd10 = $this->findModel($id);
        $icd10 = $this->findOriginalModel($customIcd10->icd10_id);

        if ($customIcd10->load(Yii::$app->request->post()) && $customIcd10->save()) {
            return $this->redirect(['view', 'id' => $customIcd10->id]);
        }

        return $this->render('update', [
            'customIcd10' => $customIcd10,
            'icd10' => $icd10,
        ]);
    }

    /**
     * Deletes an existing CustomIcd10 model.
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
     * Finds the CustomIcd10 model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return CustomIcd10 the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = CustomIcd10::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }
    
    protected function findOriginalModel($id)
    {
        if (($model = \app\models\Icd10::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }
}
