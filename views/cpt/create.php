<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\models\Cpt */

$this->title = 'Create Cpt';
$this->params['breadcrumbs'][] = ['label' => 'Cpts', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="cpt-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
