<?php
if (!Yii::$app->user->isGuest) {
    if (Yii::$app->user->identity->type == "Admin") {
        Yii::$app->response->redirect(['organization/index']);
    } else if (Yii::$app->user->identity->type == "Organization") {
        Yii::$app->response->redirect(['organization/locations']);
    } else if (Yii::$app->user->identity->type == "Insurance Company") {
        Yii::$app->response->redirect(['insurance-profile/index']);
    } else if (Yii::$app->user->identity->type == "Insurance Profile") {
        Yii::$app->response->redirect(['inbox/index']);
    } else{
        Yii::$app->response->redirect(['visit/index']);
    }
}

/* @var $this yii\web\View */

$this->title = 'COB';
?>
<div class="site-index">

    <div class="jumbotron">
        <h1>COB Home!</h1>
    </div>


</div>
