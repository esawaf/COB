<?php

use yii\helpers\Html;
use yii\widgets\DetailView;
use yii\widgets\ActiveForm;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $model app\models\Visit */
/* @var $templates app\models\Template */
$this->registerJsFile('/js/form-builder.min.js');
$this->registerJsFile('/js/form-render.min.js');
$this->registerJsFile('/js/bootstrap-datepicker.min.js');
$this->registerCssFile('/css/bootstrap-datepicker.min.css');

$this->title = $model->id;
$this->params['breadcrumbs'][] = ['label' => 'Visits', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
\yii\web\YiiAsset::register($this);
$visitDataArray = array();

if (Yii::$app->controller->action->id == "addendum" || Yii::$app->controller->action->id == "view-details" ||
    (isset(Yii::$app->request->get()['carry']) && Yii::$app->request->get()['carry'] == 1 && $visitReport != null)) {
//var_dump($visitReport->visitReportDatas[0]->visit_template_id);
    $visitData = $visitReport->visitReportDatas[0]->visit_data;
    $visitDataArray = json_decode($visitData, true);
}

$insuracneCoverage = 0;
if ($model->patient->insurancePlan != null) {
    $insuracneCoverage = $model->patient->insurancePlan->coverage_percantage;
}
$templateExists = true;
if (Yii::$app->controller->action->id == "addendum" || Yii::$app->controller->action->id == "view-details" ||
    (isset(Yii::$app->request->get()['carry']) && Yii::$app->request->get()['carry'] == 1 && $visitReport != null)) {
    if ($visitReport->visitReportDatas[0]->visitTemplate->active == 0) {
        $templateExists = false;
    }
}
if ($templateExists) {
    ?>

    <div class="visit-view">


        <h1><?= Html::encode($this->title) ?></h1>

        <p>
            <?= Html::a('Update', ['update', 'id' => $model->id], ['class' => 'btn btn-primary']) ?>
            <?php
            if (Yii::$app->controller->action->id == "view-details") {
                if (Yii::$app->user->identity->type == "Doctor") {
                    echo Html::a('Add Addendum', ['addendum', 'id' => $model->id], ['class' => 'btn btn-cob']);
                }
                ?>
                <?= Html::a('Billing Report', ['report', 'id' => $model->id], ['class' => 'btn btn-cob']); ?>
                <?= Html::a('Download Note Details', ['note-report', 'id' => $model->id], ['class' => 'btn btn-cob']); ?>
                <?php
            } else {
                if (Yii::$app->user->identity->type == "Doctor" && count($model->patient->visits) > 1) {
                    ?>
                    <?= Html::a('Carry Out from last visit', ['view', 'id' => $model->id, 'carry' => 1], ['class' => 'btn btn-primary']) ?>
                    <?php
                    if (isset(Yii::$app->request->get()['carry']) && Yii::$app->request->get()['carry'] == 1) {
                        ?>
                        <?= Html::a('Clear Carried Out Notes', ['view', 'id' => $model->id], ['class' => 'btn btn-primary']) ?>
                        <?php
                    }
                }
            }


            ?>
        </p>

        <?=
        DetailView::widget([
            'model' => $model,
            'attributes' => [
                'id',
                'uuid',
                'visit_date:datetime',
                'patient.name',
            ],
        ])
        ?>
    </div>


    <?php
    if (Yii::$app->user->identity->type == "Doctor") {
        ?>


        <?php $form = ActiveForm::begin(); ?>
        <div class="row" style="margin-bottom: 20px;">
            <div class="col-md-9">
                <select class="form-control" name="template" id="template-list">
                    <?php
                    foreach ($templates as $template) {
                        ?>
                        <option value="<?= $template->id ?>"
                                data-template="<?= htmlspecialchars($template->template) ?>"><?= $template->template_name ?></option>
                        <?php
                    }

                    ?>
                </select>
                <?php
                if (Yii::$app->controller->action->id == "addendum" ||
                    (isset(Yii::$app->request->get()['carry']) && Yii::$app->request->get()['carry'] == 1 && $visitReport != null)) {
                    ?>
                    <input type="hidden" name="template" id="template-hidden"/>
                    <?php
                }
                ?>
            </div>
            <input type="hidden" name="removed-cpts" id="removed-cpts"/>
            <input type="hidden" name="removed-icd10s" id="removed-icd10s"/>
            <div class="col-md-3">
                <button class="btn btn-cob" id="select-template-btn" onclick="selectTemplate();return false;">Apply
                    Template
                </button>
            </div>
        </div>

        <div class="tabbable" style="display: none;">
            <ul id="tab-list" class="nav nav-tabs" role="tablist">
            </ul>

            <!-- Tab panes -->
            <div id="tab-content" class="tab-content" style="padding-top: 20px;padding-bottom: 20px;">

            </div>
        </div>


        <?php
        $list = array(
            "1" => "Yes",
            "0" => "No",
        );
        echo $form->field($model, 'send_to_insurance')->radioList($list, array("separator" => "     "));

        $model->isNewRecord == 1 ? $model->review = 1 : $model->review;
        $list = array(
            "1" => "Yes",
            "0" => "No",
        );
        echo $form->field($model, 'review')->radioList($list, array("separator" => "     "));
        ?>

        <div class="form-group">
            <?= Html::button('Next', ['class' => 'btn btn-cob', "disabled" => "disabled", "id" => "nxt-btn"]) ?>
            <?= Html::submitButton('Save', ['class' => 'btn btn-cob', "disabled" => "disabled", "id" => "save-btn"]) ?>

        </div>
        <?php $form = ActiveForm::end(); ?>


        <script type="text/javascript">
            var icd10s = [];
            var cpts = [];
            let formsData = new Map();

            function addIcd10Row(visitIcd10Id = "") {
                var selected = $("#icd10-list option:selected");
                var code = selected.data('code');
                var description = selected.data('description');
                var id = selected.data('custom_id');
                // var id = $("#icd10-list").val();
                if (icd10s.includes(id)) {
                    alert("ICD10 Code already exists");
                } else {
                    icd10s.push(id);
                    var icd10RowCode = "<div class='row' id='icd10_row_" + id + "' style='margin-top:5px;'>" +
                        "<div class='col-md-1 icd10-number-col'>" +
                        "</div>" +
                        "<div class='col-md-2'>" +

                        "<input type='text' name='idc10-codes[]' class='form-control' readonly='readonly' value='" + code + "'  />" +
                        "<input type='hidden' name='idc10-ids[]' value='" + id + "' />" +
                        "<input type='hidden' name='visit-idc10-ids[]' value='" + visitIcd10Id + "' />" +
                        "</div>" +
                        "<div class='col-md-8'>" +
                        "<input type='text' name='idc10-descriptions[]' class='form-control' readonly='readonly' value='" + description + "'  />" +
                        "</div>" +
                        "<div class='col-md-1'>" +
                        "<button class='btn btn-cob' onclick='removeIcd10Row(\"" + id + "\");return false;'>-</button>"
                    "</div>" +
                    "</div>";
                    $("#addedIcd10Codes").append(icd10RowCode);
                }
                $('#icd10-codes-div').show();
                updateColumnNumbers();
                return false;
            }

            function updateColumnNumbers() {
                var numberDivs = $('.icd10-number-col');
                for (var i = 0; i < numberDivs.length; i++) {
                    var nDiv = numberDivs[i];
                    nDiv.innerHTML = i + 1;
                }
                if (cpts.length > 0 && icd10s.length > 0) {
                    $('#save-btn').prop("disabled", false);
                } else {
                    $('#save-btn').prop("disabled", true);
                }

            }

            function removeIcd10Row(id) {
                var row = $('#icd10_row_' + id);
                var visitIcd10Id = row.children("div.col-md-2")[0].querySelector("input[name='visit-idc10-ids[]']").value;
                if (visitIcd10Id != "") {
                    var removedIcd10s = $('#removed-icd10s').val();
                    removedIcd10s += visitIcd10Id + ",";
                    $('#removed-icd10s').val(removedIcd10s);
                }

                var index = icd10s.indexOf(id);

                if (index > -1) {
                    icd10s.splice(index, 1);
                }
                row.remove();
                updateColumnNumbers();
            }

            function addCptRow(noOfUnits = 1, _charge = 0, relatedIcd10 = '', modifier1 = "", modifier2 = "", modifier3 = "", modifier4 = "", billCptId = "") {


                var selected = $("#cpt-list option:selected");

                var code = selected.data('code');
                var description = selected.data('description');
                var charge = selected.data('charge');
                var id = selected.data('custom_id');

                if (_charge !== 0) {
                    charge = _charge;
                }
                // var id = $("#cpt-list").val();
                if (cpts.includes(id)) {
                    alert("CPT Code already exists");
                } else {
                    cpts.push(id);
                    var cptRowCode = "<div class='row' id='cpt_row_" + id + "' style='margin-top:5px;'>" +
                        "<div class='col-md-1'>" +
                        "<input type='hidden' name='cpt-ids[]' value=" + id + " />" +
                        "<input type='hidden' name='bill-cpt-ids[]' value='" + billCptId + "' />" +
                        "<input type='text'  name='cpt-units[]' class='form-control target' value='" + noOfUnits + "' onchange='recalculate()'  />" +
                        "</div>" +
                        "<div class='col-md-1'>" +
                        "<input type='text' name='cpt-codes[]' class='form-control' readonly='readonly' value='" + code + "'  />" +
                        "</div>" +
                        "<div class='col-md-3'>" +
                        "<input type='text' name='cpt-descriptions[]' class='form-control' readonly='readonly' value='" + description + "'  />" +
                        "</div>" +
                        "<div class='col-md-1'>" +
                        "<input type='text' name='cpt-charges[]' required='required'  class='form-control' value='" + charge + "' onchange='recalculate()' />" +
                        "</div>" +
                        "<div class='col-md-1'>" +
                        "<input type='text' name='related-icd10s[]'  required='required' class='form-control' value='" + relatedIcd10 + "' />" +
                        "</div>" +


                        "<div class='col-md-3'>" +
                        "<div class='col-md-3' style='padding: 0px;'>" +
                        "<input type='text' name='cpt-modifier1[]'  class='form-control' value='" + modifier1 + "' />" +
                        "</div>" +
                        "<div class='col-md-3' style='padding: 0px;'>" +
                        "<input type='text' name='cpt-modifier2[]'  class='form-control' value='" + modifier2 + "' />" +
                        "</div>" +
                        "<div class='col-md-3' style='padding: 0px;'>" +
                        "<input type='text' name='cpt-modifier3[]'  class='form-control' value='" + modifier3 + "' />" +
                        "</div>" +
                        "<div class='col-md-3' style='padding: 0px;'>" +
                        "<input type='text' name='cpt-modifier4[]'  class='form-control' value='" + modifier4 + "' />" +
                        "</div>" +
                        "</div>" +


                        "<div class='col-md-1'>" +
                        "<button class='btn btn-cob' onclick='removeCptRow(\"" + id + "\");return false;'>-</button>"
                    "</div>" +

                    "</div>";
                    $("#addedCptCodes").append(cptRowCode);
                    recalculate();
                }
                return false;
            }

            function selectTemplate() {
                $('.tabbable').show();
                var selected = $("#template-list option:selected");
                var tabID = 0;
                var template = selected.data('template');
                $('#tab-list').empty();
                $('#tab-content').empty();
                template.forEach(function (element) {
                    $('#tab-list').append($('<li><a href="#tab-' + tabID + '" role="tab" data-toggle="tab"><span id="tab-span-' + tabID + '">' + element.tabHead + '</span> </a></li>'));
                    $('#tab-content').append($('<div class="tab-pane fade" id="tab-' + tabID + '"><div id="form-editor-' + tabID + '"></div></div>'));
                    var container = document.getElementById('form-editor-' + tabID);
                    var formData = element.formData;

                    var mTemplates = {
                        customDate: function (fieldData) {
                            return {
                                field: '<input type="text" id="' + fieldData.name + '" name="' + fieldData.name + '" class="form-control" data-date-format="mm/dd/yyyy" />',
                                onRender: function () {
                                    $('#' + fieldData.name).datepicker({});
                                }
                            };
                        }
                    };
                    var formRenderOpts = {
                        container,
                        formData,
                        templates: mTemplates,
                        dataType: 'json'
                    };
                    $(container).formRender(formRenderOpts);

                    tabID++;
                });
                $('#tab-list').append($('<li><a href="#tab-billing" role="tab" data-toggle="tab"><span id="tab-span-billing">Billing</span> </a></li>'));
                $('#tab-content').append($('<div class="tab-pane fade" id="tab-billing"></div>'));

                <?php
                if (Yii::$app->controller->action->id == "view-details" && count($model->visitReports) > 1) {
                ?>
                $('#tab-list').append($('<li class="nav-item"><a class="nav-link" href="#tab-history" data-toggle="tab">History</a></li>'));
                var historyTabContent = `<?=
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
                    ?>`;
                $('#tab-content').append($('<div class="tab-pane fade" id="tab-history">'+historyTabContent+'</div>'));
                <?php
                }
                ?>

                addBillingContent();
                $('a[href="#tab-0"]').tab('show');
                $('#nxt-btn').prop("disabled", false);
                $('#nxt-btn').click(function () {
                    $('.nav-tabs > .active').next('li').find('a').trigger('click');
                });
            }

            function addBillingContent() {
                $('#tab-billing').append("<h4>ICD10</h4>");
                $('#tab-billing').append('<div class="row" style="margin-bottom: 10px;"><div class="col-md-9"><select class="form-control" id="icd10-list"><?php foreach ($icd10s as $icd10) { ?><option data-code="<?= $icd10->custom_id ?>" data-custom_id="<?=$icd10->id?>" data-description="<?= $icd10->custom_description ?>" value="<?= $icd10->icd10->id ?>"><?= $icd10->custom_id ?></option><?php } ?></select></div><div class="col-md-3"><button class="btn btn-cob" id="add-icd10-button" onclick="addIcd10Row();return false;">+</button></div></div>');
                $('#tab-billing').append('<div id="icd10-codes-div" style="display: none;"><div class="row" style="margin-top: 5px;margin-bottom: 5px;"><div class="col-md-1"><label>#</label></div><div class="col-md-2"><label>ICD10 Code</label></div><div class="col-md-8"><label>Description</label></div></div><div id="addedIcd10Codes" style="margin-bottom: 10px;"></div></div>');
                $('#tab-billing').append("<h4>CPT</h4>");
                $('#tab-billing').append('<div class="row"><div class="col-md-9"><select class="form-control" id="cpt-list"> <?php foreach ($cpts as $cpt) { ?> <option value="<?= $cpt->cpt->id ?>" data-code="<?= $cpt->custom_code ?>" data-custom_id="<?=$cpt->id?>" data-description="<?= $cpt->custom_description ?>" data-charge="<?= $cpt->charge ?>" ><?= $cpt->custom_code ?></option> <?php } ?> </select></div><div class="col-md-3"><button class="btn btn-cob" id="add-cpt-button" onclick="addCptRow();return false;">+</button></div></div>');
                $('#tab-billing').append('<div id="billing-div" style="display: none;"><div class="row"><div class="col-md-1"><label>No. Of Units</label></div><div class="col-md-1"><label>CPT Code</label></div><div class="col-md-3"><label>Description</label></div><div class="col-md-1"><label>Charge</label></div><div class="col-md-1"><label>Related ICD10s</label></div><div class="col-md-3"><label>Modifiers</label></div></div><div id="addedCptCodes"></div><div class="row" style="margin-top: 5px;"><div class="col-md-3"></div><div class="col-md-2"><label>Total Charge</label></div><div class="col-md-1"><input class="form-control" id="total-rate" name="total-rate" readonly="readonly" /></div></div>');
                cpts = [];
                icd10s = [];
            }

            function removeCptRow(id) {
                var row = $('#cpt_row_' + id);

                var billCptId = row.children("div.col-md-1")[0].querySelector("input[name='bill-cpt-ids[]']").value;
                if (billCptId != "") {
                    var removedCpts = $('#removed-cpts').val();
                    removedCpts += billCptId + ",";
                    $('#removed-cpts').val(removedCpts);
                }
                var index = cpts.indexOf(id);
                if (index > -1) {
                    cpts.splice(index, 1);
                }
                row.remove();
                recalculate();

            }

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
                if (cpts.length > 0 && icd10s.length > 0) {
                    $('#save-btn').prop("disabled", false);
                } else {
                    $('#save-btn').prop("disabled", true);
                }
                $('#billing-div').show();
            }

            <?php
            if (Yii::$app->controller->action->id == "addendum" || Yii::$app->controller->action->id == "view-details" ||
            (isset(Yii::$app->request->get()['carry']) && Yii::$app->request->get()['carry'] == 1 && $visitReport != null)) {
            ?>
            $(document).ready(function () {


                $('#template-list').val('<?=$visitReport->visitReportDatas[0]->visit_template_id?>');
                selectTemplate();
                $('#template-list').prop("disabled", true);

                $('#select-template-btn').prop("disabled", true);
                $('#template-hidden').val('<?=$visitReport->visitReportDatas[0]->visit_template_id?>');



                <?php
                foreach ($visitDataArray as $key=>$value){
                if(is_array($value)){
                $jsArrayStr = "[";
                foreach ($value as $val) {
                    if (strlen($jsArrayStr) > 1) {
                        $jsArrayStr .= ",";
                    }
                    $jsArrayStr .= '"' . trim($val) . '"';
                }
                $jsArrayStr .= "]";
                ?>
                formsData.set('<?=trim($key)?>', <?=$jsArrayStr?>);
                <?php
                }else{
                ?>
                formsData.set('<?=trim($key)?>', `<?=trim($value)?>`);
                <?php
                }
                }
                ?>


                for (let [key, value] of formsData.entries()) {
                    if ($('#' + key).length && $('#' + key) != "undefined") {
                        $('#' + key).val(value);
                    } else {
                        if ($("input[name=" + key + "]").length) {
                            $("input[name=" + key + "][value=" + value + "]").prop('checked', true);
                        } else {
                            for (var i = 0; i < value.length; i++) {
                                var tVal = value[i];
                                $("input[name='" + key + "[]'][value=" + tVal + "]").prop('checked', true);
                            }
                        }
                    }

                }

                <?php
                foreach ($visitReport->visitIcd10s as $customIcd10) {
                ?>
                $('#icd10-list').val('<?= $customIcd10->customIcd10->icd10->id ?>');
                addIcd10Row('<?=$customIcd10->id?>');
                <?php
                }
                foreach ($visitReport->visitBillings[0]->billCpts as $cpt) {
                ?>
                $('#cpt-list').val('<?= $cpt->cpt->cpt->id ?>');
                addCptRow('<?= $cpt->no_of_units ?>', '<?= $cpt->charge ?>', '<?=$cpt->related_icd10?>', '<?=$cpt->identifier1?>', '<?=$cpt->identifier2?>', '<?=$cpt->identifier3?>', '<?=$cpt->identifier4?>', '<?=$cpt->id?>');
                <?php
                }
                ?>
            });
            <?php
            }
            ?>

        </script>
        <?php
    }
} else {
    ?>
    <div class="alert alert-danger">
        Error! The required template wasn't found, it may be deleted or edited.
    </div>
    <?php
}
?>