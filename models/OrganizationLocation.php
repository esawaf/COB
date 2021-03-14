<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "organization_location".
 *
 * @property int $id
 * @property int $organization_id
 * @property string $name
 * @property string $address
 * @property string $city
 * @property string $state
 * @property string $zip
 * @property string $phone
 * @property string $location
 * @property string $email
 *
 * @property LoginLocation[] $loginLocations
 * @property Organization $organization
 * @property Visit[] $visits
 */
class OrganizationLocation extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'organization_location';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['organization_id', 'name', 'address', 'city', 'state', 'zip', 'phone', 'location', 'email'], 'required'],
            [['organization_id'], 'integer'],
            [['name', 'address', 'phone', 'location', 'email'], 'string', 'max' => 500],
            [['city', 'state', 'zip'], 'string', 'max' => 200],
            [['organization_id'], 'exist', 'skipOnError' => true, 'targetClass' => Organization::className(), 'targetAttribute' => ['organization_id' => 'id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'organization_id' => 'Organization ID',
            'name' => 'Name',
            'address' => 'Address',
            'city' => 'City',
            'state' => 'State',
            'zip' => 'Zip',
            'phone' => 'Phone',
            'location' => 'Location',
            'email' => 'Email',
        ];
    }

    /**
     * Gets query for [[LoginLocations]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getLoginLocations()
    {
        return $this->hasMany(LoginLocation::className(), ['location_id' => 'id']);
    }

    /**
     * Gets query for [[Organization]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getOrganization()
    {
        return $this->hasOne(Organization::className(), ['id' => 'organization_id']);
    }

    /**
     * Gets query for [[Visits]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getVisits()
    {
        return $this->hasMany(Visit::className(), ['location_id' => 'id']);
    }
}
