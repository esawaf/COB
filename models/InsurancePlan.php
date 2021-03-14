<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "insurance_plan".
 *
 * @property int $id
 * @property int $insurance_id
 * @property string $plan_name
 * @property int $coverage_percantage
 * @property int $medication_coverage_percentage
 *
 * @property InsuranceCompany $insurance
 * @property Patient[] $patients
 */
class InsurancePlan extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'insurance_plan';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['insurance_id', 'plan_name', 'coverage_percantage', 'medication_coverage_percentage'], 'required'],
            [['insurance_id', 'coverage_percantage', 'medication_coverage_percentage'], 'integer'],
            [['plan_name'], 'string', 'max' => 500],
            [['insurance_id'], 'exist', 'skipOnError' => true, 'targetClass' => InsuranceCompany::className(), 'targetAttribute' => ['insurance_id' => 'id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'insurance_id' => 'Insurance ID',
            'plan_name' => 'Plan Name',
            'coverage_percantage' => 'Coverage Percantage',
            'medication_coverage_percentage' => 'Medication Coverage Percentage',
        ];
    }

    /**
     * Gets query for [[Insurance]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getInsurance()
    {
        return $this->hasOne(InsuranceCompany::className(), ['id' => 'insurance_id']);
    }

    /**
     * Gets query for [[Patients]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getPatients()
    {
        return $this->hasMany(Patient::className(), ['insurance_plan_id' => 'id']);
    }
}
