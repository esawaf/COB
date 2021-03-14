<?php

namespace app\controllers;
use app\models\InsuranceCompany;
use app\models\Login;
use app\models\Organization;
use Yii;
class AccountController extends \yii\web\Controller
{
    public function actionChangePassword()
    {
        $request = Yii::$app->request;
        $passwordChanged= false;
        $wrongCurrentPassword=false;
        $newPasswordNotMatch = false;
        $somethingWentWrong=false;
        if ($request->isPost) {

            $currentPassword = $request->post("current-password");
            $newPassword = $request->post('new-password');
            $newPasswordConf = $request->post('new-password-confirmation');
            $user = Login::findOne(Yii::$app->user->id);

            if($user->password == sha1($currentPassword)){
                if($newPassword==$newPasswordConf){
                    $user->password = sha1($newPassword);
                    if($user->save()){
                        $passwordChanged=true;
                    }else{
                        $somethingWentWrong=true;
                    }
                }else{
                    $newPasswordNotMatch=true;
                }
            }else{
                $wrongCurrentPassword=true;
            }
        }
        return $this->render('change-password',["wrongCurrentPassword"=>$wrongCurrentPassword,
                                                    "newPasswordNotMatch"=>$newPasswordNotMatch,
                                                    "passwordChanged"=>$passwordChanged,
                                                    "somethingWentWrong"=>$somethingWentWrong]);
    }

    public function actionIndex()
    {
        return $this->redirect(['management']);
    }

    public function actionManagement()
    {
        $model = Login::findOne(Yii::$app->user->id);
        $saved=false;
        $role = Yii::$app->user->identity->role;
        if($role =="Doctor"){

            if ($model->load(Yii::$app->request->post())) {
                $model->save();
                $saved=true;
            }
            return $this->render('doctor',['model'=>$model,'saved'=>$saved]);
        } else if($role =="Front Desk"){

            if ($model->load(Yii::$app->request->post())) {
                $model->save();
                $saved=true;
            }
            return $this->render('front-desk',['model'=>$model,'saved'=>$saved]);
        } else if($role =="Organization"){
            $organizationId = Yii::$app->user->identity->organization_id;
            $organization = Organization::findOne($organizationId);
            if ($model->load(Yii::$app->request->post()) && $organization->load(Yii::$app->request->post())) {
                $model->name = $organization->name;

                $model->save();
                $organization->save();
                $saved=true;
            }
            return $this->render('organization',['model'=>$model,'saved'=>$saved,"organization"=>$organization]);
        } else if($role =="Insurance Company"){
            $insuranceId = Yii::$app->user->identity->insurance_id;
            $insurance = InsuranceCompany::findOne($insuranceId);
            if ($model->load(Yii::$app->request->post()) && $insurance->load(Yii::$app->request->post())) {
                $model->name = $insurance->company_name;
                $model->save();
                $insurance->save();
                $saved=true;
            }
            return $this->render('insurance-company',['model'=>$model,'saved'=>$saved,"insurance"=>$insurance]);
        } else if($role =="Insurance Profile" || $role=="Admin"){

            if ($model->load(Yii::$app->request->post())) {
                $model->save();
                $saved=true;
            }
            return $this->render('insurance-profile',['model'=>$model,'saved'=>$saved]);
        }
        var_dump($role);
        exit();
        return $this->render('management');
    }

}
