<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "insurance_company".
 *
 * @property int $id
 * @property string $company_name
 * @property string $telephone_number
 * @property string|null $extension
 * @property string|null $fax_no
 * @property string $address
 * @property string $city
 * @property string $state
 * @property string $zip
 * @property string $contact_person
 * @property string $contact_person_phone
 * @property string $contact_person_email
 * @property int $accounts_number
 *
 * @property BillingPost[] $billingPosts
 * @property InsurancePlan[] $insurancePlans
 * @property Login[] $logins
 * @property OrganizationInsurance[] $organizationInsurances
 * @property Patient[] $patients
 * @property PharmacyInsurance[] $pharmacyInsurances
 */
class InsuranceCompany extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'insurance_company';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['company_name', 'telephone_number', 'address', 'city', 'state', 'zip', 'contact_person', 'contact_person_phone', 'contact_person_email', 'accounts_number'], 'required'],
            [['accounts_number'], 'integer'],
            [['company_name', 'address'], 'string', 'max' => 500],
            [['telephone_number', 'extension', 'fax_no', 'city', 'contact_person', 'contact_person_phone', 'contact_person_email'], 'string', 'max' => 200],
            [['state', 'zip'], 'string', 'max' => 100],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'company_name' => 'Company Name',
            'telephone_number' => 'Telephone Number',
            'extension' => 'Extension',
            'fax_no' => 'Fax No',
            'address' => 'Address',
            'city' => 'City',
            'state' => 'State',
            'zip' => 'Zip',
            'contact_person' => 'Contact Person',
            'contact_person_phone' => 'Contact Person Phone',
            'contact_person_email' => 'Contact Person Email',
            'accounts_number' => 'Accounts Number',
        ];
    }

    /**
     * Gets query for [[BillingPosts]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getBillingPosts()
    {
        return $this->hasMany(BillingPost::className(), ['insurance_id' => 'id']);
    }

    /**
     * Gets query for [[InsurancePlans]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getInsurancePlans()
    {
        return $this->hasMany(InsurancePlan::className(), ['insurance_id' => 'id']);
    }

    /**
     * Gets query for [[Logins]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getLogins()
    {
        return $this->hasMany(Login::className(), ['insurance_id' => 'id']);
    }

    /**
     * Gets query for [[OrganizationInsurances]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getOrganizationInsurances()
    {
        return $this->hasMany(OrganizationInsurance::className(), ['insurance_company_id' => 'id']);
    }

    /**
     * Gets query for [[Patients]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getPatients()
    {
        return $this->hasMany(Patient::className(), ['insurance_id' => 'id']);
    }

    /**
     * Gets query for [[PharmacyInsurances]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getPharmacyInsurances()
    {
        return $this->hasMany(PharmacyInsurance::className(), ['insurance_company_id' => 'id']);
    }
}
