<?php

use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Organization Insurances';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="organization-insurance-index">

    <h1><?= Html::encode($this->title) ?></h1>
    <?php
    if (Yii::$app->user->identity->organization_id != null) {
        ?>
        <p>
            <?= Html::a('Add Organization Insurance', ['create'], ['class' => 'btn btn-cob']) ?>
        </p>

        <?php
    } else if (Yii::$app->user->identity->insurance_id != null) {
        ?>
        <p>
            <?= Html::a('All', ['index'], ['class' => 'btn btn-cob']) ?>
            <?= Html::a('Prending', ['pending'], ['class' => 'btn btn-cob']) ?>
            <?= Html::a('Approved', ['approved'], ['class' => 'btn btn-cob']) ?>
            <?= Html::a('Rejected', ['rejected'], ['class' => 'btn btn-cob']) ?>
        </p>
        <?php
    }
    $columns = [
        'id'];
    if (Yii::$app->user->identity->insurance_id != null) {
        $columns[] = ['attribute' => 'organizationName', 'value' => 'organization.name'];
    } else if (Yii::$app->user->identity->organization_id != null) {
        $columns[] = ['attribute' => 'insuranceCompanyName', 'value' => 'insuranceCompany.company_name'];
    }
    $columns[] = 'status';

    $columns[] = [
        'format' => 'raw',
        'value' => function($data) {
            $column = "";
            $column .= Html::a(Html::tag('span', "", ['class' => "glyphicon glyphicon-eye-open"]), ['view', 'id' => $data->id], ['title' => "View", "aria-label" => "View"]);
            if (Yii::$app->user->identity->insurance_id != null) {
                if ($data->status == "Pending") {
                    $column .= " ";
                    $column .= Html::a(Html::tag('span', "", ['class' => "glyphicon glyphicon-ok"]), ['confirm', 'id' => $data->id], ['title' => "Confirm", "aria-label" => "Confirm"]);
                }
                $column .= " ";
                $column .= Html::a(Html::tag('span', "", ['class' => "glyphicon glyphicon-remove-sign"]), ['reject', 'id' => $data->id], ['title' => "Reject", "aria-label" => "Reject"]);
            }
            return $column;
        }
    ];
    ?>
    <?=
    GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => $columns,
    ]);
    ?>


</div>
