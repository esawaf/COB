<?php
use yii\helpers\Html;
$this->title = 'ICD10 & CPT Sync';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="icd10-index">

    <h1><?= Html::encode($this->title) ?></h1>

    <?php
    if($message!=""){
        ?>
        <div class="alert alert-success"><?=$message?></div>
        <?php
    }
    ?>

    <?= Html::a('Sync ICD10', ['sync',"action"=>'icd10'], ['class' => 'btn btn-cob btn-block']) ?>
    <?= Html::a('Sync CPT', ['sync',"action"=>'cpt'], ['class' => 'btn btn-cob btn-block']) ?>
</div>

