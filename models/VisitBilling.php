<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "visit_billing".
 *
 * @property int $id
 * @property int $visit_report_id
 * @property float $cost
 * @property float $insurance_charge
 * @property float $patient_charge
 *
 * @property BillCpt[] $billCpts
 * @property VisitReport $visitReport
 */
class VisitBilling extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'visit_billing';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['visit_report_id', 'cost', 'insurance_charge', 'patient_charge'], 'required'],
            [['visit_report_id'], 'integer'],
            [['cost', 'insurance_charge', 'patient_charge'], 'number'],
            [['visit_report_id'], 'exist', 'skipOnError' => true, 'targetClass' => VisitReport::className(), 'targetAttribute' => ['visit_report_id' => 'id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'visit_report_id' => 'Visit Report ID',
            'cost' => 'Cost',
            'insurance_charge' => 'Insurance Charge',
            'patient_charge' => 'Patient Charge',
        ];
    }

    /**
     * Gets query for [[BillCpts]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getBillCpts()
    {
        return $this->hasMany(BillCpt::className(), ['visit_bill_id' => 'id']);
    }

    /**
     * Gets query for [[VisitReport]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getVisitReport()
    {
        return $this->hasOne(VisitReport::className(), ['id' => 'visit_report_id']);
    }
}
