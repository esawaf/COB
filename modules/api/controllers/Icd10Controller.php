<?php

namespace app\modules\api\controllers;
use app\models\Icd10;

class Icd10Controller extends \yii\web\Controller
{
    public function actionIndex()
    {
        return $this->render('index');
    }

    public function actionList()
    { 
		\Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
		//$getRequest = \Yii::$app->request->get();
		//$code = $getRequest['code'];
		//$desc = $getRequest['desc'];
		$data = Icd10::find()->all();
		//$query = Icd10::find();
		//$query->andFilterWhere(['like', 'description', $desc]);
		//$data = $query->andFilterWhere(['like', 'icd10_code', $code])->all();
		return $data;
    }

}
