<?php
use yii\helpers\Html;
use yii\widgets\ActiveForm;
?>
<div class="patient-form">

    <?php $form = ActiveForm::begin(); ?>
    
    <?= $form->field($model, 'title')->textInput() ?>
    
    <?= $form->field($message, 'message')->textarea(["rows" => 5]) ?>
    <?= $form->field($model, 'badge')->textInput() ?>
    <label><input type="radio" name = "InboxThread[badge_color]" value="label-danger"> <label class="label label-danger">Red</label></input></label>
    <label><input type="radio" name = "InboxThread[badge_color]" value="label-success"> <label class="label label-success">Green</label></input></label>
    <label><input type="radio" name = "InboxThread[badge_color]" value="label-info"> <label class="label label-info">Aqua</label></input></label>
    <label><input type="radio" name = "InboxThread[badge_color]" value="label-primary"> <label class="label label-primary">Blue</label></input></label>
    <label><input type="radio" name = "InboxThread[badge_color]" value="label-warning"> <label class="label label-warning">Orange</label></input></label>
    
    <div class="form-group">
        <?= Html::submitButton('Save', ['class' => 'btn btn-cob']) ?>
    </div>

    <?php ActiveForm::end(); ?>


</div>
