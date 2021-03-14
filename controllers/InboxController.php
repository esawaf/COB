<?php

namespace app\controllers;


use Yii;
use app\models\Login;

class InboxController extends \yii\web\Controller {

    public function actionIndex() {
        $organizationAccount= null;
        if(Yii::$app->user->identity->type=="Insurance Profile"){
            $organizationAccount = Login::find()->where(['type'=>"Insurance Company"])->andWhere(['insurance_id'=>Yii::$app->user->identity->insurance_id])->one();
        }else{
            $organizationAccount = Login::find()->where(['type'=>"Organization"])->andWhere(['organization_id'=>Yii::$app->user->identity->organization_id])->one();
        }
        $threads = $organizationAccount->inboxThreads;
        return $this->render('index', ["threads" => $threads]);
    }

    public function actionThread($id) {
        if (isset(Yii::$app->request->post()["message"])) {
            $messageTxt = Yii::$app->request->post()["message"];
            $message = new \app\models\Message();
            $message->message = $messageTxt;
            $message->read = 0;
            $message->sender_id = Yii::$app->user->identity->id;
            $message->thread_id = $id;
            $message->save();
        }
        $thread = \app\models\InboxThread::findOne($id);
        return $this->render('thread', ["thread" => $thread]);
    }

    public function actionStartThread($id) {
        $thread = \app\models\InboxThread::findOne(['visit_id' => $id]);
        if ($thread != null) {
            $this->redirect(["thread", "id" => $thread->id]);
        } else {
            $this->redirect(["new-message", "id" => $id]);
        }
    }

    public function actionNewMessage($id) {
        $model = new \app\models\InboxThread();
        $message = new \app\models\Message();
        $visit = \app\models\Visit::findOne($id);

        if ($model->load(Yii::$app->request->post()) && $message->load(Yii::$app->request->post())) {
            $senderId = null;
            $receiverId = null;
            if(Yii::$app->user->identity->type=="Insurance Profile"){
                $insuranceAccount = Login::find()->where(['type'=>"Insurance Company"])->andWhere(['insurance_id'=>Yii::$app->user->identity->insurance_id])->one();
                $senderId = $insuranceAccount->id;
                
                $organizationId = $visit->doctor->organization_id;
                $organizationLogin = Login::find()->where(['type'=>"Organization"])->andWhere(['organization_id'=>$organizationId])->one();
                $receiverId = $organizationLogin->id;
            }else{
                $organizationAccount = Login::find()->where(['type'=>"Organization"])->andWhere(['organization_id'=>Yii::$app->user->identity->organization_id])->one();
                $senderId = $organizationAccount->id;
                $insuranceId = $visit->patient->insurance_id;
                $insuranceAccount = Login::find()->where(['type'=>"Insurance Company"])->andWhere(['insurance_id'=>$insuranceId])->one();
                $receiverId = $insuranceAccount->id;
                
            }
            $model->sender_id = $senderId;
            $model->receiver_id = $receiverId;
            
            $model->visit_id = $id;
            
            if($model->save()){
                $message->read=0;
                $message->thread_id = $model->id;
                $message->sender_id = Yii::$app->user->identity->id;
                $message->save();
                return $this->redirect(['thread', 'id' => $model->id]);
            }            
        }

        return $this->render('create', [
                    'model' => $model,
                    'message'=>$message,
        ]);
    }

}
