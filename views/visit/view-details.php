<?php

use yii\helpers\Html;
use yii\widgets\DetailView;
use yii\widgets\ActiveForm;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $model app\models\Visit */
$this->registerJsFile('/js/form-builder.min.js');
$this->registerJsFile('/js/form-render.min.js');
$this->registerJsFile('/js/bootstrap-datepicker.min.js');
$this->registerCssFile('/css/bootstrap-datepicker.min.css');
$this->title = $model->visit->id;
$this->params['breadcrumbs'][] = ['label' => 'Visits', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
\yii\web\YiiAsset::register($this);

?>

<div class="visit-view">

    <h1><?= Html::encode($this->title) ?></h1>
    <p>
        <?php
        if (Yii::$app->user->identity->type != "Insurance Profile") {
            ?>
            <?= Html::a('Update', ['update', 'id' => $model->visit->id], ['class' => 'btn btn-primary']) ?>
            <?php
        } else {
            ?>
            <?= Html::a('Approve Payment', ['approve-insurance', 'id' => $model->visit->id], ['class' => 'btn btn-cob']) ?>
            <?= Html::a('Reject Payment', ['reject-insurance', 'id' => $model->visit->id], ['class' => 'btn btn-cob']) ?>
            <?= Html::a('Partialy Approve Payment', ['partial-approve-insurance', 'id' => $model->visit->id], ['class' => 'btn btn-cob']) ?>
            <?php
        }
        if ($model->visit->patient->insurance != null) {
            echo Html::a('Message', ['inbox/start-thread', 'id' => $model->visit->id], ['class' => 'btn btn-cob']);
        }
        ?>
        <?php
        if (Yii::$app->user->identity->type == "Doctor") {
            echo Html::a('Add Addendum', ['addendum', 'id' => $model->visit->id], ['class' => 'btn btn-cob']);
        }
        ?>
        <?= Html::a('Billing Report', ['report', 'id' => $model->visit->id], ['class' => 'btn btn-cob']); ?>
        <?= Html::a('Download Note Details', ['note-report', 'id' => $model->visit->id], ['class' => 'btn btn-cob']); ?>
    </p>

    <?=
    DetailView::widget([
        'model' => $model->visit,
        'attributes' => [
            'id',
            'uuid',
            'visit_date:datetime',
            'patient.name',
        ],
    ]);
    $templateJson = $model->visitReportDatas[0]->visitTemplate->template;
    $template = json_decode($templateJson);
    $visitData = $model->visitReportDatas[0]->visit_data;
    $visitDataArray = json_decode($visitData,true);
    ?>

</div>
<?php $form = ActiveForm::begin(); ?>

<div class="tabbable">
    <ul class="nav nav-tabs">
        <?php
        $i = 0;
        foreach ($template as $templateElement) {
            ?>
            <li class="nav-item <?= $i == 0 ? 'active' : '' ?>">
                <a class="nav-link <?= $i == 0 ? 'active show' : '' ?> show" href="#tab-<?= $i ?>"
                   data-toggle="tab"><?= $templateElement->tabHead ?></a>
            </li>
            <?php
            $i++;
        }
        ?>

        <li class="nav-item">
            <a class="nav-link" href="#tab-billing" data-toggle="tab">Billing</a>
        </li>

        <?php
        if (count($model->visit->visitReports) > 1) {
            ?>
            <li class="nav-item">
                <a class="nav-link" href="#tab-history" data-toggle="tab">History</a>
            </li>
            <?php
        }
        ?>

    </ul>
    <div class="tab-content">
        <?php
        $i = 0;
        foreach ($template as $templateElement) {
            ?>
            <div class="tab-pane  tab-pane-div <?= $i == 0 ? 'active' : '' ?>" id="tab-<?= $i ?>">
                <div id="form-editor-<?= $i ?>">

                </div>
            </div>
            <?php
            $i++;
        }
        ?>
        <div class="tab-pane  tab-pane-div" id="tab-billing">
            <div id="icd10-codes-div">
                <div class="row" style="margin-top: 5px;margin-bottom: 5px;">
                    <div class="col-md-2">
                        <label>ICD10 Code</label>
                    </div>
                    <div class="col-md-9">
                        <label>Description</label>
                    </div>
                </div>
                <div id="addedIcd10Codes" style="margin-bottom: 10px;">
                    <?php
                    $j=1;
                    foreach ($model->visitIcd10s as $icd10) {
                        ?>
                        <div class='row' style='margin-top:5px;'>
                            <div class='col-md-1 icd10-number-col'>
                                <?=$j?>
                            </div>
                            <div class='col-md-2'>
                                <input type='text' name='idc10-codes[]' class='form-control' readonly='readonly'
                                       value='<?= $icd10->customIcd10->custom_id ?>'/>
                            </div>
                            <div class='col-md-8'>
                                <input type='text' name='idc10-descriptions[]' class='form-control' readonly='readonly'
                                       value='<?= $icd10->customIcd10->custom_description ?>'/>
                            </div>
                        </div>
                        <?php
                        $j++;
                    }
                    ?>
                </div>
            </div>


            <div id="billing-div">
                <div class="row">
                    <div class="col-md-1">
                        <label>No. Of Units</label>
                    </div>
                    <div class="col-md-2">
                        <label>CPT Code</label>
                    </div>
                    <div class="col-md-3">
                        <label>Description</label>
                    </div>
                    <div class="col-md-1">
                        <label>Charge</label>
                    </div>
                    <div class="col-md-1">
                        <label>Related ICD10s</label>
                    </div>
                    <div class="col-md-4">

                        <label>Modifiers</label>
                    </div>
                </div>


                <div id="addedCptCodes">
                    <?php
                    foreach ($model->visitBillings[0]->billCpts as $cpt) {
                        ?>
                        <div class='row' style='margin-top:5px;'>
                            <div class='col-md-1'>
                                <input type='hidden' name='cpt-ids[]' value="<?=$cpt->id?>"/>
                                <input type='text' name='cpt-units[]' class='form-control target' onchange="recalculate();"
                                       value='<?= $cpt->no_of_units ?>'/>
                            </div>
                            <div class='col-md-2'>
                                <input type='text' name='cpt-codes[]' class='form-control' readonly='readonly'
                                       value='<?= $cpt->cpt->custom_code ?>'/>
                            </div>
                            <div class='col-md-3'>
                                <input type='text' name='cpt-descriptions[]' class='form-control' readonly='readonly'
                                       value='<?= $cpt->cpt->custom_description ?>'/>
                            </div>
                            <div class='col-md-1'>
                                <input type='text' name='cpt-charges[]' class='form-control' onchange="recalculate();"
                                       value='<?= $cpt->charge ?>'/>
                            </div>
                            <div class='col-md-1'>
                                <input type='text' name='related-icd10s[]' class='form-control' readonly="readonly"
                                       value='<?= $cpt->related_icd10 ?>'/>
                            </div>
                            <div class="col-md-1">
                                <input type='text' name='cpt-modifier1[]' class='form-control'
                                       value='<?= $cpt->identifier1 ?>'/>
                            </div>
                            <div class="col-md-1">
                                <input type='text' name='cpt-modifier2[]' class='form-control'
                                       value='<?= $cpt->identifier2 ?>'/>
                            </div>
                            <div class="col-md-1">
                                <input type='text' name='cpt-modifier3[]' class='form-control'
                                       value='<?= $cpt->identifier3 ?>'/>
                            </div>
                            <div class="col-md-1">
                                <input type='text' name='cpt-modifier4[]' class='form-control'
                                       value='<?= $cpt->identifier4 ?>'/>
                            </div>
                        </div>
                        <?php
                    }
                    ?>
                </div>
                <div class="row" style="margin-top: 5px;">
                    <div class="col-md-4"></div>
                    <div class="col-md-2">Total Charge</div>
                    <div class='col-md-2'>
                        <input class="form-control" id="total-rate" name="total-rate"
                               value="<?= $model->visitBillings[0]->cost ?>" readonly="readonly"/>
                    </div>

                </div>
            </div>
            <div class="form-group" style="margin-top: 10px;">
                <?= Html::submitButton('Save', ['class' => 'btn btn-cob btn-block', "id" => "save-btn"]) ?>
            </div>
        </div>
        <?php

        ?>
        <?php
        if (count($model->visit->visitReports) > 1) {
            ?>
            <div class="tab-pane tab-pane-div" id="tab-history">
                <?=
                GridView::widget([
                    'dataProvider' => $dataProvider,
                    'columns' => [
                        ['class' => 'yii\grid\SerialColumn'],
                        'id',
                        [
                            'format' => 'raw',
                            'label' => 'Date',
                            'value' => function ($data) {

                                return Html::a($data->date, ['view-details', 'id' => $data->visit->id, 'report' => $data->id]);
                            }
                        ],
                        ['label' => 'Doctor Name', 'attribute' => 'doctor.name'],
                    ],
                ]);
                ?>
            </div>
            <?php
        }
        ?>


    </div>

</div>
<script type="text/javascript">
    var formsTemplateData = [];
    let formsData = new Map();

    function recalculate() {
        var totalCharge = 0;
        var rows = $("#addedCptCodes > div.row");
        for (i = 0; i < rows.length; i++) {
            var row = rows[i];
            var unit = row.querySelector("input[name='cpt-units[]']").value;
            var charge = row.querySelector("input[name='cpt-charges[]']").value;
            totalCharge += unit * charge;
        }
        //var insuranceCharge = (<?//=$insuracneCoverage?>// / 100) * totalCharge;
        //var patientCharge = totalCharge - insuranceCharge;
        $("#total-rate").val(totalCharge);
        // $("#insuracne-charge").val(insuranceCharge);
        // $("#patient-charge").val(patientCharge);
    }

    <?php
    foreach ($template as $templateElement) {
    ?>
    formsTemplateData.push('<?=$templateElement->formData?>');
    <?php
    }

    foreach ($visitDataArray as $key=>$value){
        if(is_array($value)){
            $jsArrayStr = "[";
            foreach ($value as $val){
                if(strlen($jsArrayStr)>1){
                    $jsArrayStr.=",";
                }
                $jsArrayStr.='"'.trim($val).'"';
            }
            $jsArrayStr.="]";
            ?>
            formsData.set('<?=trim($key)?>',<?=$jsArrayStr?>);
            <?php
        }else{
        ?>
        formsData.set('<?=trim($key)?>',`<?=trim($value)?>`);
        <?php
        }
    }
    ?>
    $(document).ready(function () {


        var mTemplates = {
            customDate: function(fieldData) {
                return {
                    field: '<input type="text" id="'+fieldData.name+'" name="'+fieldData.name+'" class="form-control" data-date-format="mm/dd/yyyy" />',
                    onRender: function() {
                    }
                };
            }
        };
        var tabID=0;
        formsTemplateData.forEach(function (element){
            var container = document.getElementById('form-editor-'+tabID);
            var formData = element;
            var formRenderOpts = {
                container,
                formData,
                templates:mTemplates,
                dataType: 'json'
            };
            $(container).formRender(formRenderOpts);
            tabID++;
        });


        for (let [key, value] of  formsData.entries()) {
            if($('#'+key).length && $('#'+key)!="undefined"){
                $('#'+key).val(value);
            }else{
                if($("input[name="+key+"]").length){
                    $("input[name="+key+"][value=" + value + "]").prop('checked', true);
                }else{
                    for(var i=0;i<value.length;i++){
                        var tVal = value[i];
                        $("input[name='"+key+"[]'][value=" + tVal + "]").prop('checked', true);
                    }
                }
            }
        }

    });

</script>
<?php

?>
<?php $form = ActiveForm::end(); ?>
