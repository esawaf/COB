<?php

namespace app\controllers;

use Yii;
use app\models\Template;
use yii\data\ActiveDataProvider;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;

/**
 * TemplateController implements the CRUD actions for Template model.
 */
class TemplateController extends Controller
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
     * Lists all Template models.
     * @return mixed
     */
    public function actionIndex()
    {
        $dataProvider = new ActiveDataProvider([
            'query' => Template::find(),

        ]);
        $dataProvider->query->andWhere(['active'=>1]);
        $dataProvider->query->andWhere(['organization_id'=>Yii::$app->user->identity->organization_id]);
        return $this->render('index', [
            'dataProvider' => $dataProvider,
        ]);
    }


    /**
     * Creates a new Template model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new Template();
//        var_dump(Yii::$app->request->post());
//        exit();

        if ($model->load(Yii::$app->request->post())) {
            $model->organization_id = Yii::$app->user->identity->organization_id;
            $model->active=1;
            if($model->save()){
                return $this->redirect(['index']);
            }
        }

        return $this->render('create', [
            'model' => $model,
        ]);
    }

    /**
     * Updates an existing Template model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);
        $model2 = new Template();
        if ($model2->load(Yii::$app->request->post())) {
            $model->active=0;
            $model->save();
            $model2->organization_id = Yii::$app->user->identity->organization_id;
            $model2->active=1;
            if($model2->save()){
                return $this->redirect(['index']);
            }

        }
        return $this->render('update', [
            'model' => $model,
        ]);
    }

    public function actionCopy($id){
        $model = $this->findModel($id);
        $model->template_name=null;
        $model2 = new Template();
        if ($model2->load(Yii::$app->request->post())) {
            $model2->organization_id = Yii::$app->user->identity->organization_id;
            $model2->active=1;
            if($model2->save()){
                return $this->redirect(['index']);
            }

        }
        return $this->render('copy', [
            'model' => $model,
        ]);
    }

    /**
     * Deletes an existing Template model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionDelete($id)
    {
        $model =$this->findModel($id);
        $model->active=0;
        $model->save();
        return $this->redirect(['index']);
    }

    /**
     * Finds the Template model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Template the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Template::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }
}
