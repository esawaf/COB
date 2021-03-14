<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use meysampg\formbuilder\FormBuilder;

$this->registerCssFile('/css/bootstrap-datepicker.min.css');
$this->registerJsFile('/js/form-builder.min.js');
$this->registerJsFile('/js/form-render.min.js');
$this->registerJsFile('/js/bootstrap-datepicker.min.js',['depends' => [\yii\web\JqueryAsset::className()]]);

$templateJsonArray = array();
/* @var $this yii\web\View */
/* @var $model app\models\Template */
/* @var $form yii\widgets\ActiveForm */
?>
    <div id="loader" class="row" style="align-items: center;justify-content: center;display: flex;">
        <div class="loader"></div>
    </div>
    <div class="template-form" style="display: none;">

        <?php $form = ActiveForm::begin(['id' => 'templateForm']); ?>

        <?= $form->field($model, 'template_name')->textInput(['maxlength' => true]) ?>


        <h2>Visit Note Template </h2>
        <p>
            <button id="btn-add-tab" type="button" class="btn btn-primary pull-right">Add Tab</button>
        </p>

        <div class="tabbable">
            <ul id="tab-list" class="nav nav-tabs" role="tablist">
                <?php
                if ($model->isNewRecord) {
                    ?>
                    <li class="active">
                        <a href="#tab-1" role="tab" data-toggle="tab">
                            <span id="tab-span-1">Tab 1 </span>
                            <span class="glyphicon glyphicon-pencil text-muted edit"></span>
                        </a>
                    </li>
                    <?php
                } else {
                    $templateJsonArray = json_decode($model->template);

                    $i = 1;
                    foreach ($templateJsonArray as $templateForm) {
                        ?>
                        <li class="<?= $i == 1 ? 'active' : '' ?>">
                            <a href="#tab-<?= $i ?>" role="tab" data-toggle="tab">
                                <span id="tab-span-<?= $i ?>"><?= $templateForm->tabHead ?></span>
                                <span class="glyphicon glyphicon-pencil text-muted edit"></span>
                                <?php
                                if ($i != 1) {
                                    ?>
                                    <button class="close" type="button" title="Remove this page">×</button>
                                    <?php
                                }
                                ?>
                            </a>
                        </li>
                        <?php
                        $i++;
                    }
                }
                ?>
            </ul>
            <!-- Tab panes -->
            <div id="tab-content" class="tab-content">
                <?php
                if ($model->isNewRecord) {
                    ?>
                    <div class="tab-pane fade in active" id="tab-1">

                        <div id="form-editor-1">

                        </div>
                    </div>
                    <?php
                } else {
                    $i = 1;
                    foreach ($templateJsonArray as $templateForm) {
                        ?>
                        <div class="tab-pane fade <?= $i == 1 ? 'in active' : '' ?>" id="tab-<?= $i ?>">

                            <div id="form-editor-<?= $i ?>">

                            </div>
                        </div>
                        <?php
                        $i++;
                    }
                }
                ?>
            </div>
        </div>
        <?= $form->field($model, 'template')->hiddenInput(['id' => 'forms-json', 'maxlength' => true])->label(false) ?>
        <div class="form-group">
            <?= Html::submitButton('Save', ['id' => 'submit-button', 'class' => 'btn btn-success']) ?>
        </div>

        <?php ActiveForm::end(); ?>

    </div>
    <script type="text/javascript">
        var tabID = 1;
        let tabHeadList = new Map();
        let formsMap = new Map();
        var formsDataJsonStrArr = [];
        var fields = [{
            label: 'Date',
            attrs: {
                type: 'customDate'
            },
            icon:'📅'
            // iconCss:'formbuilder-icon-date'
        }];
        var templates = {

            customDate: function(fieldData) {
                return {
                    field: '<input type="text" id="'+fieldData.name+'" name="'+fieldData.name+'" class="form-control" data-date-format="mm/dd/yyyy" />',
                    onRender: function() {
                        $('#' + fieldData.name).datepicker({});

                    }
                };
            },
        };
        let options = {
            templates,
            fields,
            showActionButtons: false,
            controlOrder: [
                'text',
                'textarea',
                'number',
                'customDate',
                'radio-group',
                'checkbox-group',
                'select'

            ]
        };
        jQuery(function ($) {
            <?php
            if($model->isNewRecord){
            ?>
            formsMap.set(1, $('#form-editor-1').formBuilder(options));
            tabHeadList.set('#tab-1', "Tab 1");
            <?php
            }else{

            $i = 1;
            ?>
            tabID = 0;

            <?php
            foreach ($templateJsonArray as $templateForm){

            ?>
            formsDataJsonStrArr.push('<?=$templateForm->formData?>');
            tabHeadList.set('#tab-<?=$i?>', "<?=$templateForm->tabHead?>");
            tabID++;
            <?php
            $i++;
            }
            ?>
            for (var i = 1; i <= formsDataJsonStrArr.length; i++) {
                setTimeout(addFormToMap, (i-1)*3000,i);

            }
            function addFormToMap(index){
                let updateOptions = {
                    templates,
                    fields,
                    showActionButtons: false,
                    dataType: 'json',
                    formData: formsDataJsonStrArr[index-1],

                    controlOrder: [
                        // templates,
                        // fields,
                        'text',
                        'textarea',
                        'number',
                        'customDate',
                        'radio-group',
                        'checkbox-group',
                        'select'
                    ]
                };
                formsMap.set(index, $('#form-editor-'+index).formBuilder(updateOptions));
            }

            <?php
            }
            ?>

        });


        var button = '<button class="close" type="button" title="Remove this page">×</button>';


        function resetTab() {
            var tabs = $("#tab-list li:not(:first)");
            var len = 1
            $(tabs).each(function (k, v) {
                len++;
                $(this).find('a').html('Tab ' + len + button);
            })
            tabID--;
        }


        $(document).ready(function () {
            $('#templateForm').submit(function () {
                let formsJson = [];

                for (let [key, value] of formsMap) {
                    let formData = value.actions.getData('json');
                    let tabHead = tabHeadList.get('#tab-' + key);
                    if (formData != null && formData != "" && tabHead != null && tabHead != "") {
                        formsJson.push({formData, tabHead});
                    }else if(tabHead==null || tabHead==""){
                        alert("The tab name can't be empty");
                        return false;
                    }

                }
                let jsonString = JSON.stringify(formsJson);
                $('#forms-json').val(jsonString);
                return true; // return false to cancel form action
            });



            $('#btn-add-tab').click(function () {
                tabID++;
                $('#tab-list').append($('<li><a href="#tab-' + tabID + '" role="tab" data-toggle="tab"><span id="tab-span-' + tabID + '">Tab ' + tabID + '</span> <span class="glyphicon glyphicon-pencil text-muted edit"></span> <button class="close" type="button" title="Remove this page">×</button></a></li>'));
                $('#tab-content').append($('<div class="tab-pane fade" id="tab-' + tabID + '"><div id="form-editor-' + tabID + '"></div></div>'));
                $(".edit").click(editHandler);
                formsMap.set(tabID, $('#form-editor-' + tabID).formBuilder(options));
                tabHeadList.set('#tab-' + tabID, "Tab " + tabID);
            });

            $('#tab-list').on('click', '.close', function () {
                var tabID = $(this).parents('a').attr('href');

                $(this).parents('li').remove();
                $(tabID).remove();
                tabHeadList.delete(tabID);
                var n = tabID.lastIndexOf("-");
                var numericId = tabID.substr(n + 1);
                formsMap.delete(numericId);
                //display first tab
                var tabFirst = $('#tab-list a:first');
                tabFirst.tab('show');
            });

            var list = document.getElementById("tab-list");
        });

        var editHandler = function () {
            var t = $(this);
            t.css("visibility", "hidden");
            $(this).prev().attr("contenteditable", "true").focusout(function () {
                $(this).removeAttr("contenteditable").off("focusout");
                var tabID = $(this).parents('a').attr('href');
                tabHeadList.set(tabID, $(this).text());
                t.css("visibility", "visible");
            });
        };

        $(".edit").click(editHandler);
        setTimeout(function (){
            $('#loader').hide();
            $('.template-form').show();
        }, 10000);
    </script>
<?php

?>