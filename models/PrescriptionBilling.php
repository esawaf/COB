<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "prescription_billing".
 *
 * @property int $id
 * @property int $prescription_id
 * @property float $insurance_charge
 * @property float $patient_charge
 * @property float $total_charge
 *
 * @property Prescription $prescription
 * @property PrescriptionMedication[] $prescriptionMedications
 */
class PrescriptionBilling extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'prescription_billing';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['prescription_id', 'insurance_charge', 'patient_charge', 'total_charge'], 'required'],
            [['prescription_id'], 'integer'],
            [['insurance_charge', 'patient_charge', 'total_charge'], 'number'],
            [['prescription_id'], 'exist', 'skipOnError' => true, 'targetClass' => Prescription::className(), 'targetAttribute' => ['prescription_id' => 'id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'prescription_id' => 'Prescription ID',
            'insurance_charge' => 'Insurance Charge',
            'patient_charge' => 'Patient Charge',
            'total_charge' => 'Total Charge',
        ];
    }

    /**
     * Gets query for [[Prescription]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getPrescription()
    {
        return $this->hasOne(Prescription::className(), ['id' => 'prescription_id']);
    }

    /**
     * Gets query for [[PrescriptionMedications]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getPrescriptionMedications()
    {
        return $this->hasMany(PrescriptionMedication::className(), ['prescripton_bill_id' => 'id']);
    }
}
