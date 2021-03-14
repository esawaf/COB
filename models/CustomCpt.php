<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "custom_cpt".
 *
 * @property int $id
 * @property string $custom_code
 * @property resource $custom_description
 * @property float $charge
 * @property int $cpt_id
 * @property int $doctor_id
 *
 * @property BillCpt[] $billCpts
 * @property Login $doctor
 * @property Cpt $cpt
 */
class CustomCpt extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'custom_cpt';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['custom_code', 'custom_description', 'charge', 'cpt_id', 'doctor_id'], 'required'],
            [['custom_description'], 'string'],
            [['charge'], 'number'],
            [['cpt_id', 'doctor_id'], 'integer'],
            [['custom_code'], 'string', 'max' => 500],
            [['doctor_id'], 'exist', 'skipOnError' => true, 'targetClass' => Login::className(), 'targetAttribute' => ['doctor_id' => 'id']],
            [['cpt_id'], 'exist', 'skipOnError' => true, 'targetClass' => Cpt::className(), 'targetAttribute' => ['cpt_id' => 'id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'custom_code' => 'Code',
            'custom_description' => 'Description',
            'charge' => 'Charge',
            'cpt_id' => 'Cpt ID',
            'doctor_id' => 'Doctor ID',
        ];
    }

    /**
     * Gets query for [[BillCpts]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getBillCpts()
    {
        return $this->hasMany(BillCpt::className(), ['cpt_id' => 'id']);
    }

    /**
     * Gets query for [[Doctor]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getDoctor()
    {
        return $this->hasOne(Login::className(), ['id' => 'doctor_id']);
    }

    /**
     * Gets query for [[Cpt]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getCpt()
    {
        return $this->hasOne(Cpt::className(), ['id' => 'cpt_id']);
    }
}
