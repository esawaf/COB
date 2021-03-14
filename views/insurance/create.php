<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\models\InsuranceCompany */

$this->title = 'Create Insurance Company';
$this->params['breadcrumbs'][] = ['label' => 'Insurance Companies', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="insurance-company-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
        'login' => $login,
    ]) ?>

</div>
