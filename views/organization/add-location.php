<?php
use yii\helpers\Html;
use yii\widgets\ActiveForm;
$this->title="Add Location";
$this->params['breadcrumbs'][] = ['label' => 'Locations', 'url' => ['locations']];
$this->params['breadcrumbs'][] = $this->title;
?>

<div class="organization-form">

    <h1><?= Html::encode($this->title) ?></h1>


    <?= $this->render('_location-form', [
        'organizationLocation' => $organizationLocation,
    ]) ?>
</div>
