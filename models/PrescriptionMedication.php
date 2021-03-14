<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "prescription_medication".
 *
 * @property int $id
 * @property int $prescripton_bill_id
 * @property int $no_of_units
 * @property string $medication_name
 * @property float $charge
 *
 * @property PrescriptionBilling $prescriptonBill
 */
class PrescriptionMedication extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'prescription_medication';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['prescripton_bill_id', 'no_of_units', 'medication_name', 'charge'], 'required'],
            [['prescripton_bill_id', 'no_of_units'], 'integer'],
            [['charge'], 'number'],
            [['medication_name'], 'string', 'max' => 500],
            [['prescripton_bill_id'], 'exist', 'skipOnError' => true, 'targetClass' => PrescriptionBilling::className(), 'targetAttribute' => ['prescripton_bill_id' => 'id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'prescripton_bill_id' => 'Prescripton Bill ID',
            'no_of_units' => 'No Of Units',
            'medication_name' => 'Medication Name',
            'charge' => 'Charge',
        ];
    }

    /**
     * Gets query for [[PrescriptonBill]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getPrescriptonBill()
    {
        return $this->hasOne(PrescriptionBilling::className(), ['id' => 'prescripton_bill_id']);
    }
}
