<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "bill_cpt".
 *
 * @property int $id
 * @property int $visit_bill_id
 * @property int $cpt_id
 * @property int $no_of_units
 * @property float $charge
 * @property string $related_icd10
 * @property string|null $identifier1
 * @property string|null $identifier2
 * @property string|null $identifier3
 * @property string|null $identifier4
 *
 * @property CustomCpt $cpt
 * @property VisitBilling $visitBill
 * @property BillingPostCpt[] $billingPostCpts
 */
class BillCpt extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'bill_cpt';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['visit_bill_id', 'cpt_id', 'no_of_units', 'charge', 'related_icd10'], 'required'],
            [['visit_bill_id', 'cpt_id', 'no_of_units'], 'integer'],
            [['charge'], 'number'],
            [['related_icd10'], 'string', 'max' => 300],
            [['identifier1', 'identifier2', 'identifier3', 'identifier4'], 'string', 'max' => 50],
            [['cpt_id'], 'exist', 'skipOnError' => true, 'targetClass' => CustomCpt::className(), 'targetAttribute' => ['cpt_id' => 'id']],
            [['visit_bill_id'], 'exist', 'skipOnError' => true, 'targetClass' => VisitBilling::className(), 'targetAttribute' => ['visit_bill_id' => 'id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'visit_bill_id' => 'Visit Bill ID',
            'cpt_id' => 'Cpt ID',
            'no_of_units' => 'No Of Units',
            'charge' => 'Charge',
            'related_icd10' => 'Related Icd10',
            'identifier1' => 'Identifier1',
            'identifier2' => 'Identifier2',
            'identifier3' => 'Identifier3',
            'identifier4' => 'Identifier4',
        ];
    }

    /**
     * Gets query for [[Cpt]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getCpt()
    {
        return $this->hasOne(CustomCpt::className(), ['id' => 'cpt_id']);
    }

    /**
     * Gets query for [[VisitBill]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getVisitBill()
    {
        return $this->hasOne(VisitBilling::className(), ['id' => 'visit_bill_id']);
    }

    /**
     * Gets query for [[BillingPostCpts]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getBillingPostCpts()
    {
        return $this->hasMany(BillingPostCpt::className(), ['visit_cpt_id' => 'id']);
    }
}
