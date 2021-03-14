<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model app\models\OrganizationInsurance */

$this->title = $model->id;
$this->params['breadcrumbs'][] = ['label' => 'Organization Insurances', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
\yii\web\YiiAsset::register($this);
?>
<div class="organization-insurance-view">

    <h1><?= Html::encode($this->title) ?></h1>


    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            'id',
            ['attribute'=> 'organization.name','label'=>'Organization Name'],
            ['attribute'=> 'insuranceCompany.company_name','label'=>'Insurance Company Name'],
//            'insurance_company_id',
            'status',
        ],
    ]) ?>

</div>
