<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "pharmacy".
 *
 * @property int $id
 * @property string $name
 * @property string $address
 * @property string $contact_person
 * @property string $phone
 *
 * @property PharmacyInsurance[] $pharmacyInsurances
 * @property PharmacyLocation[] $pharmacyLocations
 */
class Pharmacy extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'pharmacy';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['name', 'address', 'contact_person', 'phone'], 'required'],
            [['name', 'address', 'contact_person', 'phone'], 'string', 'max' => 500],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'name' => 'Name',
            'address' => 'Address',
            'contact_person' => 'Contact Person',
            'phone' => 'Phone',
        ];
    }

    /**
     * Gets query for [[PharmacyInsurances]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getPharmacyInsurances()
    {
        return $this->hasMany(PharmacyInsurance::className(), ['pharmacy_id' => 'id']);
    }

    /**
     * Gets query for [[PharmacyLocations]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getPharmacyLocations()
    {
        return $this->hasMany(PharmacyLocation::className(), ['pharmacy_id' => 'id']);
    }
}
