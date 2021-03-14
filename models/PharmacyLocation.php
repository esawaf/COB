<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "pharmacy_location".
 *
 * @property int $id
 * @property int $pharmacy_id
 * @property string $name
 * @property string $address
 * @property string $phone
 * @property string|null $location
 * @property string $email
 *
 * @property Pharmacy $pharmacy
 */
class PharmacyLocation extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'pharmacy_location';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['pharmacy_id', 'name', 'address', 'phone', 'email'], 'required'],
            [['pharmacy_id'], 'integer'],
            [['name'], 'string', 'max' => 500],
            [['address'], 'string', 'max' => 1000],
            [['phone'], 'string', 'max' => 100],
            [['location'], 'string', 'max' => 2000],
            [['email'], 'string', 'max' => 250],
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
            'name' => 'Name',
            'address' => 'Address',
            'phone' => 'Phone',
            'location' => 'Location',
            'email' => 'Email',
        ];
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
