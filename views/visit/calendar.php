<?php
//$this->registerJsFile(
//        '@web/js/full-calendar.js',
//        ['depends' => [\yii\web\JqueryAsset::className()]]
//);
//$this->registerCssFile("@web/css/full-calendar.css",
//        ['depends' => [\yii\bootstrap\BootstrapAsset::className()]]);



$this->registerJsFile(
        '@web/js/jquery.datetimepicker.min.js',
        ['depends' => [\yii\web\JqueryAsset::className()]]
);
$this->registerCssFile("@web/css/jquery.datetimepicker.min.css",
        ['depends' => [\yii\bootstrap\BootstrapAsset::className()]]);
$this->registerCss(".buttonpane { display:none; }");

$this->title = "Visits Calendar"
?>
<div id="demo2"></div>

<script>
    $(document).ready(function () {
        $('#demo2').datetimepicker({
            date: new Date(),
            viewMode: 'YMD',
            onClear:null,
            onDateChange: function () {
//                $('#date-text-ymd2').text(this.getText('YYYY-MM-DD'));
//                alert(this.getText('YYYY-MM-DD'));
                var url = '<?= yii\helpers\Url::toRoute(["visit/day"]);?>?date='+ this.getText('YYYY-MM-DD');
                location.href = url;
//                alert(url);
            }
            
        });
    });
</script>
<!--<div id='calendar' style="max-width: 1100px;"></div>-->

<!--<script>

    document.addEventListener('DOMContentLoaded', function () {
        var calendarEl = document.getElementById('calendar');

        var calendar = new FullCalendar.Calendar(calendarEl, {
            initialDate: '<?= date('Y-m-d') ?>',
            initialView: 'dayGridMonth',
            nowIndicator: true,
            headerToolbar: {
                left: 'prev,next today',
                center: 'title',
                right: 'dayGridMonth,timeGridWeek,timeGridDay,listWeek'
            },
            navLinks: true, // can click day/week names to navigate views
            editable: true,
            selectable: true,
            selectMirror: true,
            dayMaxEvents: true, // allow "more" link when too many events
            events: <?= json_encode($events) ?>
        });

        calendar.render();
    });

</script>-->