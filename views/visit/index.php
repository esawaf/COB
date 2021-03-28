<?php

use yii\helpers\Html;
use yii\grid\GridView;
use app\models\OrganizationLocation;

/* @var $this yii\web\View */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Visits';
$this->params['breadcrumbs'][] = $this->title;
$this->registerJsFile(
        '@web/js/jquery.datetimepicker.min.js',
        ['depends' => [\yii\web\JqueryAsset::className()]]
);
$this->registerCssFile("@web/css/jquery.datetimepicker.min.css",
        ['depends' => [\yii\bootstrap\BootstrapAsset::className()]]);
$this->registerCss(".buttonpane { display:none; } td.weekend {color: #163a5f!important;} td.selected{border: 1px solid #30c184!important;background-color: #30c184!important;}");
?>
<?php
//var_dump($dataProvider);
//var_dump(Yii::$app->controller->action->id);
if (Yii::$app->user->isGuest) {
    echo 'User is not logged!';
} else {
//    var_dump(Yii::$app->user->identity->type);
}
if (Yii::$app->controller->action->id != "patient") {


    $locations = OrganizationLocation::findAll(array('organization_id' => Yii::$app->user->identity->organization_id));
    $selectedLocation = Yii::$app->user->identity->selected_location;
    if (Yii::$app->user->identity->type == "Doctor" || Yii::$app->user->identity->type == "Front Desk") {
        ?>
        <select name="locations" id="locations" class="form-control">
            <?php
            foreach ($locations as $location) {
                $selected = $location->id == $selectedLocation ? " selected='selected' " : "";
                ?>
                <option <?= $selected ?> value="<?= $location->id ?>"><?= $location->name ?></option>
                <?php
            }
            ?>
        </select>
        <script type="text/javascript">
            $("#locations").change(function () {

                window.location.href = "/visit/change-location?id=" + $(this).val();
            });
        </script>
        <?php
    }
}
?>
<div class="visit-index">

    <h1><?= Html::encode($this->title) ?></h1>
    <?php
    if (Yii::$app->controller->action->id != "patient") {
        $n = date('Y-m-d', strtotime(" +2 days"));
        ?>
        <p>
            <?php
            if (Yii::$app->user->identity->type == "Front Desk") {
                echo " ";
                echo Html::a('Unpaid Visits', ['unpaid'], ['class' => 'btn btn-cob']);
            }
            if (Yii::$app->user->identity->type == "Doctor") {
                echo Html::a('Incomplete Visits', ['incomplete'], ['class' => 'btn btn-cob']);
                echo " ";
                echo Html::a('Pending Review', ['pending-review'], ['class' => 'btn btn-cob']);
            }
            if (Yii::$app->user->identity->type == "Insurance Profile") {
                echo " ";
                echo Html::a('Pending Payment', ['insurance-payment-pending'], ['class' => 'btn btn-cob']);
            }
            
            ?>

            <?= Html::a('Today Visits', ['index', "date" => date('Y-m-d')], ['class' => 'btn btn-cob']) ?>
        </p>

        <?php
        if ($incompleteCount > 0 || $reviewCount > 0) {
            ?>
            <div class="alert alert-warning">You have <?= $incompleteCount ?> incomplete visit(s) and <?= $reviewCount ?> visit(s) are pending for review.</div>
            <?php
        }else if($insurancePendingPaymentCount>0){
            ?>
            <div class="alert alert-warning">You have <?=$insurancePendingPaymentCount?> pending payments</div>
            <?php
        }
    } else {
        ?>
        <?=
        yii\widgets\DetailView::widget([
            'model' => $patient,
            'attributes' => [
                'name',
                'national_id',
                'passport_number',
                'birth_date',
                'gender',
                'insurance.company_name',
                'insurance_number',
            ],
        ])
        ?>
        <?php
    }
    $gridViewColumnts = [
        'id',
        [
            'attribute' => 'visit_date',
            'format' => 'raw',
            'value' => function($data) {
                $visitDateTime = Yii::$app->formatter->asDate($data->visit_date, 'php: m/d/Y G:i:s');
                return Html::a($visitDateTime, ['view', 'id' => $data->id]);
            }
        ],
        'status',];
    if (Yii::$app->controller->action->id != "patient") {
        $gridViewColumnts[] = [
            'label' => 'Patient Name',
            'attribute' => 'patient.name',
            'format' => 'raw',
            'value' => function($data) {
                return Html::a($data->patient->name, ['patient/view', 'id' => $data->patient->id]);
            }
        ];
    }
    $gridViewColumnts[] = ['label' => 'Doctor Name', 'attribute' => 'doctor.name'];
//    $gridViewColumnts[] = ['class' => 'yii\grid\ActionColumn'];
//    if (Yii::$app->user->identity->type == "Front Desk") {
    $gridViewColumnts[] = [
        'format' => 'raw',
        'value' => function($data) {
            $column = "";
            $column .= Html::a(Html::tag('span', "", ['class' => "glyphicon glyphicon-eye-open"]), ['view', 'id' => $data->id], ['title' => "View", "aria-label" => "View"]);
            if ($data->status == app\models\VisitStatus::PENDING) {
                $column .= " ";
                $column .= Html::a(Html::tag('span', "", ['class' => "glyphicon glyphicon-pencil"]), ['update', 'id' => $data->id], ['title' => "Edit", "aria-label" => "Edit"]);
            }
            if (Yii::$app->user->identity->type == "Front Desk") {
                if ($data->status == app\models\VisitStatus::PENDING) {
                    $column .= " ";
                    $column .= Html::a(Html::tag('span', "", ['class' => "glyphicon glyphicon-log-in"]), ['checkin', 'id' => $data->id], ['title' => "Check in", "aria-label" => "Check in"]);
                    $column .= " ";
                    $column .= Html::a(Html::tag('span', "", ['class' => "glyphicon glyphicon-remove-sign"]), ['cancel', 'id' => $data->id], ['title' => "Cancel", "aria-label" => "Cancel"]);
                } else if ($data->status == \app\models\VisitStatus::COMPLETED) {
                    $column .= " ";
                    $column .= Html::a(Html::tag('span', "", ['class' => "glyphicon glyphicon-gbp"]), ['paid', 'id' => $data->id], ['title' => "Mark as paid", "aria-label" => "Mark as paid"]);
                }
            }
            return $column;
        }
    ];
//    }
    ?>
    <div class="row">
        <div class="col-md-9">
            <?=
            GridView::widget([
                'dataProvider' => $dataProvider,
                'columns' => $gridViewColumnts,
            ]);
            ?>
        </div>
        <div class="col-md-3" style="padding-top: 15px;">
            <div id="calendar"></div>

            <div style="margin-top: 10px;">
                <table class="table table-bordered table-condensed table-hover">
                    <thead>
                        <tr>
                            <th>Doctor Name</th><th>Visists Count</th>
                        </tr>
                    </thead>
                    <tbody>
                    <?php
                    foreach ($doctorVisitCount as $doctorName=>$visitsCount){
                        ?>
                        <tr>
                            <td><?=$doctorName?></td>
                            <td><?=$visitsCount?></td>
                        </tr>
                        <?php
                    }
                    ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>



</div>
<?php
//            var_dump(Yii::$app->request->get()['date']);
?>
<script>
    $(document).ready(function () {
    $('#calendar').datetimepicker({

    viewMode: 'YMD',
<?php
if (isset(Yii::$app->request->get()['date'])) {
    ?>
        date: new Date('<?= Yii::$app->request->get()['date'] ?>'),
    <?php
} else {
    ?>
        date: new Date(),
    <?php
}
?>

    onDateChange: function () {
//                $('#date-text-ymd2').text(this.getText('YYYY-MM-DD'));
//                alert(this.getText('YYYY-MM-DD'));
    var url = '<?= yii\helpers\Url::toRoute(["visit/index"]); ?>?date=' + this.getText('YYYY-MM-DD');
            location.href = url;
//                alert(url);
    }

    });
    }
    );
</script>