<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "organization".
 *
 * @property int $id
 * @property string $name
 * @property string $address
 * @property string $contact_person
 * @property string $phone_code
 * @property string $phone
 * @property int $doctors_count
 * @property string $city
 * @property string $state
 * @property string $zip
 * @property string $federal_tax_id
 * @property string $npi
 *
 * @property OrganizationInsurance[] $organizationInsurances
 * @property OrganizationLocation[] $organizationLocations
 * @property Template[] $templates
 */
class Organization extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'organization';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['name', 'address', 'contact_person', 'phone_code', 'phone', 'doctors_count', 'city', 'state', 'zip', 'federal_tax_id', 'npi'], 'required'],
            [['doctors_count'], 'integer'],
            [['name', 'address', 'contact_person', 'phone', 'city', 'state', 'zip'], 'string', 'max' => 500],
            [['phone_code'], 'string', 'max' => 50],
            [['federal_tax_id', 'npi'], 'string', 'max' => 100],
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
            'phone_code' => 'Phone Code',
            'phone' => 'Phone',
            'doctors_count' => 'Doctors Count',
            'city' => 'City',
            'state' => 'State',
            'zip' => 'Zip',
            'federal_tax_id' => 'Federal Tax ID',
            'npi' => 'Npi',
        ];
    }

    /**
     * Gets query for [[OrganizationInsurances]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getOrganizationInsurances()
    {
        return $this->hasMany(OrganizationInsurance::className(), ['organization_id' => 'id']);
    }

    /**
     * Gets query for [[OrganizationLocations]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getOrganizationLocations()
    {
        return $this->hasMany(OrganizationLocation::className(), ['organization_id' => 'id']);
    }

    /**
     * Gets query for [[Templates]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getTemplates()
    {
        return $this->hasMany(Template::className(), ['organization_id' => 'id']);
    }
}
