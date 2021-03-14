<?php

use yii\bootstrap\ActiveForm;

//var_dump($thread->messages);
/* @var $this yii\web\View */
$this->registerCssFile("/css/chat.css");

//$this->registerCssFile("http://netdna.bootstrapcdn.com/bootstrap/4.5.0/css/bootstrap.min.css");
function getMessageDateTime($datetime) {
    $timestamp = strtotime($datetime);
    $time = date('G:i', $timestamp);
    $date = new DateTime(date("Y-m-d"));
    $match_date = new DateTime(date("Y-m-d", $timestamp));
    $interval = $date->diff($match_date);

    if ($interval->days == 0) {
        return $time;
    } else if ($interval->days == 1 && $interval->invert == 1) {
        return "Yesterday " . $time;
    } else {
        return date('d/m/Y', $timestamp) . " $time";
    }
}
?>



<div class="card">
    <div class="row g-0" style="height: 100%;">

        <div class="col-12" style="padding: 20px;width: 100%;height: 100%;position: relative;">


            <div class="position-relative" style="height: 95%;position: absolute;padding-bottom: 20px">
                <div id="messages-div" class="chat-messages p-4" style="height: 100%;">
                    <?php
                    foreach ($thread->messages as $message) {
                        $cssClass = "chat-message-left pb-4";
                        if (Yii::$app->user->identity->organization_id == $message->sender->organization_id) {
                            $cssClass = "chat-message-right pb-4";
                        }
                        ?>
                        <div class="<?= $cssClass ?>">
                            <div>
                                <img src="https://bootdey.com/img/Content/avatar/avatar1.png" class="rounded-circle mr-1" alt="<?= $message->sender->name ?>" width="40" height="40">
                            </div>
                            <div class="flex-shrink-1 bg-light rounded py-2 px-3 mr-3">
                                <div class="font-weight-bold mb-1"><?= $message->sender->name ?></div>
                                <?= $message->message ?>
                                <div style="color:grey;text-align: right"><?= getMessageDateTime($message->date) ?></div>
                            </div>
                        </div>
                        <?php
                    }
                    ?>




                </div>
            </div>

            <div class="flex-grow-0 py-3 px-4 border-top" style="position:absolute;bottom: 0px;width: 95%;">
                <?php $form = ActiveForm::begin(); ?>
                <div class="input-group">
                    <input type="text" name="message" class="form-control" placeholder="Type your message" style="flex: 1 1 auto;position: relative;width: 1%;margin-bottom: 0;">
                    <button type="submit" class="btn btn-primary">Send</button>
                </div>
                <?php ActiveForm::end(); ?>
            </div>

        </div>
    </div>
</div>

<script type="text/javascript">
    $(document).ready(function () {
        $("#messages-div").animate({scrollTop: $('#messages-div').prop("scrollHeight")}, 1000);
    });
</script>