<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "pharmacy_insurance".
 *
 * @property int $id
 * @property int $pharmacy_id
 * @property int $insurance_company_id
 * @property string $status
 *
 * @property InsuranceCompany $insuranceCompany
 * @property Pharmacy $pharmacy
 */
class PharmacyInsurance extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'pharmacy_insurance';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['pharmacy_id', 'insurance_company_id', 'status'], 'required'],
            [['pharmacy_id', 'insurance_company_id'], 'integer'],
            [['status'], 'string'],
            [['insurance_company_id'], 'exist', 'skipOnError' => true, 'targetClass' => InsuranceCompany::className(), 'targetAttribute' => ['insurance_company_id' => 'id']],
            [['pharmacy_id'], 'exist', 'skipOnError' => true, 'targetClass' => Pharmacy::className(), 'targetAttribute' => ['pharmacy_id' => 'id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'pharmacy_id' => 'Pharmacy ID',
            'insurance_company_id' => 'Insurance Company ID',
            'status' => 'Status',
        ];
    }

    /**
     * Gets query for [[InsuranceCompany]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getInsuranceCompany()
    {
        return $this->hasOne(InsuranceCompany::className(), ['id' => 'insurance_company_id']);
    }

    /**
     * Gets query for [[Pharmacy]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getPharmacy()
    {
        return $this->hasOne(Pharmacy::className(), ['id' => 'pharmacy_id']);
    }
}
