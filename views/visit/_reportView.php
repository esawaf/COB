<?php

use yii\widgets\DetailView;
use yii\grid\GridView;

?>
<br /> <br />
<?=

DetailView::widget([
    'model' => $model->visit,
    'attributes' => [
        'visit_date:datetime',
        [
            'label' => 'Patient Name',
            'value' => $model->visit->patient->name,
        ],
        [
            'label' => 'Doctor Name',
            'value' => $model->doctor->name,
        ],
        [
            'label' => 'Doctor NPI',
            'value' => $model->doctor->provider_id,
        ],
        [
            'label' => 'Company Name',
            'value' => $model->doctor->loginLocations[0]->location->organization->name,
        ],
        [
            'label' => 'Company NPI',
            'value' => $model->doctor->loginLocations[0]->location->organization->npi,
        ],
        [
            'label' => 'Insurance Company Name',
            'value' => $model->visit->patient->insurance->company_name,
        ],
        [
            'label' => 'Patient Insurance Number',
            'value' => $model->visit->patient->insurance_number,
        ],
    ],
])
?>
<h2>ICD10</h2>
<?= GridView::widget([
        'dataProvider' => $icd10s,
        'summary'=>'', 
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],
            ['label'=> 'ICD10 Code','attribute'=>'customIcd10.icd10.icd10_code'],
            ['label'=> 'ICD10 Description','attribute'=>'customIcd10.icd10.description'],
        ],
    ]); ?>

<h2>CPT</h2>

<?= GridView::widget([
        'dataProvider' => $cpts,
        'sorter' => [],

        'summary'=>'', 
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],
            ['label'=> 'No of Units','attribute'=>'no_of_units'],
            ['label'=> 'CPT Code','attribute'=>'cpt.cpt.code'],
            ['label'=> 'CPT Description','attribute'=>'cpt.cpt.description'],
//            ['label'=> 'Charge','attribute'=>'charge'],
        ],
    ]); ?>
<hr />

<?php
foreach ($outputData as $tabHead=>$tabElements){
    ?>
    <h2><?=$tabHead?></h2>
    <?php
    foreach ($tabElements as $elementLabel=>$elementValue){
        ?>
        <b><?=$elementLabel?>:</b><br /> <?=$elementValue?><br />
        <?php
    }

}
?>