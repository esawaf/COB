<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "prescription".
 *
 * @property int $id
 * @property string $uuid
 * @property string $date
 * @property int $patient_id
 * @property int $pharmaciest_id
 * @property string $status
 * @property string $insurance_payment_status
 * @property int $send_to_insurance
 *
 * @property Login $pharmaciest
 * @property Patient $patient
 * @property PrescriptionBilling[] $prescriptionBillings
 */
class Prescription extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'prescription';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['uuid', 'patient_id', 'pharmaciest_id', 'status', 'insurance_payment_status', 'send_to_insurance'], 'required'],
            [['date'], 'safe'],
            [['patient_id', 'pharmaciest_id', 'send_to_insurance'], 'integer'],
            [['status', 'insurance_payment_status'], 'string'],
            [['uuid'], 'string', 'max' => 45],
            [['pharmaciest_id'], 'exist', 'skipOnError' => true, 'targetClass' => Login::className(), 'targetAttribute' => ['pharmaciest_id' => 'id']],
            [['patient_id'], 'exist', 'skipOnError' => true, 'targetClass' => Patient::className(), 'targetAttribute' => ['patient_id' => 'id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'uuid' => 'Uuid',
            'date' => 'Date',
            'patient_id' => 'Patient ID',
            'pharmaciest_id' => 'Pharmaciest ID',
            'status' => 'Status',
            'insurance_payment_status' => 'Insurance Payment Status',
            'send_to_insurance' => 'Send To Insurance',
        ];
    }

    /**
     * Gets query for [[Pharmaciest]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getPharmaciest()
    {
        return $this->hasOne(Login::className(), ['id' => 'pharmaciest_id']);
    }

    /**
     * Gets query for [[Patient]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getPatient()
    {
        return $this->hasOne(Patient::className(), ['id' => 'patient_id']);
    }

    /**
     * Gets query for [[PrescriptionBillings]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getPrescriptionBillings()
    {
        return $this->hasMany(PrescriptionBilling::className(), ['prescription_id' => 'id']);
    }
}
