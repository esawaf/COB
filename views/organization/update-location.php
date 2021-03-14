<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\models\Organization */

$this->title = 'Update Location: ' . $organizationLocation->name;
$this->params['breadcrumbs'][] = ['label' => 'Locations', 'url' => ['locations']];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="organization-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_location-form', [
        'organizationLocation' => $organizationLocation,
    ]) ?>

</div>
