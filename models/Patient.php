<?php

namespace app\models;

use Yii;
use yii\data\ActiveDataProvider;

/**
 * This is the model class for table "patient".
 *
 * @property int $id
 * @property string $name
 * @property string|null $national_id
 * @property string|null $passport_number
 * @property string $phone_code
 * @property string $phone
 * @property string $birth_date
 * @property string $gender
 * @property string|null $email
 * @property string|null $address
 * @property string|null $city
 * @property string|null $state
 * @property string|null $zip
 * @property int|null $insurance_id
 * @property string|null $insurance_number
 * @property string|null $insurance_expiry_date
 * @property int|null $insurance_plan_id
 * @property string|null $insurance_status
 * @property string $patient_relationship_to_insured
 * @property int $is_employment
 * @property int $is_auto_accident
 * @property string|null $auto_accident_place
 * @property int $is_other_accident
 * @property string|null $referring_provider_name
 * @property string|null $referring_provider_npi
 * @property string|null $prior_authorization_number
 * @property string|null $date_of_injury
 * @property string|null $additional_info

 *
 * @property InsuranceCompany $insurance
 * @property InsurancePlan $insurancePlan
 * @property PatientEdoc[] $patientEdocs
 * @property Prescription[] $prescriptions
 * @property Visit[] $visits
 */
class Patient extends \yii\db\ActiveRecord {

    /**
     * {@inheritdoc}
     */
    public $insuranceCompanyName;

    public static function tableName() {
        return 'patient';
    }

    /**
     * {@inheritdoc}
     */
    public function rules() {
        return [
            [['name', 'phone_code', 'phone', 'birth_date', 'gender', 'patient_relationship_to_insured', 'is_employment', 'is_auto_accident', 'is_other_accident'], 'required'],
            [['birth_date', 'insurance_expiry_date', 'date_of_injury'], 'safe'],
            [['address', 'insurance_status', 'patient_relationship_to_insured', 'additional_info'], 'string'],
            [['insurance_id', 'insurance_plan_id', 'is_employment', 'is_auto_accident', 'is_other_accident'], 'integer'],
            [['name', 'national_id', 'passport_number', 'phone', 'email', 'insurance_number', 'referring_provider_name'], 'string', 'max' => 500],
            [['phone_code'], 'string', 'max' => 20],
            [['gender'], 'string', 'max' => 45],
            [['auto_accident_place'], 'string', 'max' => 50],
            [['referring_provider_npi', 'prior_authorization_number'], 'string', 'max' => 100],
            [['city', 'state', 'zip'], 'string', 'max' => 250],
            [['insurance_id'], 'exist', 'skipOnError' => true, 'targetClass' => InsuranceCompany::className(), 'targetAttribute' => ['insurance_id' => 'id']],
            [['insurance_plan_id'], 'exist', 'skipOnError' => true, 'targetClass' => InsurancePlan::className(), 'targetAttribute' => ['insurance_plan_id' => 'id']],
            [['insuranceCompanyName'], 'safe'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels() {
        return [
            'id' => 'ID',
            'name' => 'Name',
            'national_id' => 'National ID',
            'passport_number' => 'Passport Number',
            'phone_code' => 'Phone Code',
            'phone' => 'Phone',
            'birth_date' => 'Birth Date',
            'gender' => 'Gender',
            'email' => 'Email',
            'address' => 'Address',
            'city' => 'City',
            'state' => 'State',
            'zip' => 'Zip',
            'insurance_id' => 'Insurance Company',
            'insurance_number' => 'Insurance Number',
            'insurance_expiry_date' => 'Insurance Expiry Date',
            'insurance_plan_id' => 'Insurance Plan',
            'insurance_status' => 'Insurance Status',
            'insuranceCompanyName' => Yii::t('app', 'Insuracne Company Name'),
            'patient_relationship_to_insured' => 'Patient Relationship To Insured',
            'is_employment' => 'Is Employment',
            'is_auto_accident' => 'Is Auto Accident',
            'auto_accident_place' => 'Auto Accident Place',
            'is_other_accident' => 'Is Other Accident',
            'referring_provider_name' => 'Referring Provider Name',
            'referring_provider_npi' => 'Referring Provider Npi',
            'prior_authorization_number' => 'Prior Authorization Number',
            'date_of_injury' => 'Date Of Injury',
            'additional_info' => 'Additional Info',
        ];
    }

    /**
     * Gets query for [[Insurance]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getInsurance() {
        return $this->hasOne(InsuranceCompany::className(), ['id' => 'insurance_id']);
    }

    /**
     * Gets query for [[InsurancePlan]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getInsurancePlan() {
        return $this->hasOne(InsurancePlan::className(), ['id' => 'insurance_plan_id']);
    }

    /**
     * Gets query for [[PatientEdocs]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getPatientEdocs() {
        return $this->hasMany(PatientEdoc::className(), ['patient_id' => 'id']);
    }

    /**
     * Gets query for [[Prescriptions]]. 
     * 
     * @return \yii\db\ActiveQuery 
     */
    public function getPrescriptions() {
        return $this->hasMany(Prescription::className(), ['patient_id' => 'id']);
    }

    /**
     * Gets query for [[Visits]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getVisits() {
        return $this->hasMany(Visit::className(), ['patient_id' => 'id']);
    }

    public function getInsuranceCompanyName() {
        return $this->insurance->company_name;
    }

    public function beforeValidate() {

        if (parent::beforeValidate()) {

            $valid = true;
            if ($this->insurance_id != null) {
                if ($this->insurance_number == null) {
                    $this->addError('insurance_number', 'Insurance number should be filled');
                    $valid = false;
                }
                if ($this->insurance_expiry_date == null) {
                    $this->addError('insurance_expiry_date', 'Insurance expiry date should be filled');
                    $valid = false;
                }
                if ($this->insurance_plan_id == null) {
                    $this->addError('insurance_plan_id', 'Insurance plan date should be selected');
                    $valid = false;
                }
                return $valid;
            }


            return true;
        }

        return false;
    }

    public function search($params) {
        $query = self::find();
        $query->joinWith('insurance', true);
        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'sort' => ['attributes' => ['name', 'national_id', 'passport_number', 'insurance_number',
                    'insuranceCompanyName' => ['asc' => ['insurance_company.company_name' => SORT_ASC],
                        'desc' => ['insurance_company.company_name' => SORT_DESC],
                        'label' => 'Insurance Company Name']]]
        ]);
        $this->load($params);

        $query->andFilterWhere(['like', 'name', $this->name])
                ->andFilterWhere(['like', 'national_id', $this->national_id])
                ->andFilterWhere(['like', 'passport_number', $this->passport_number])
                ->andFilterWhere(['like', 'phone', $this->phone])
                ->andFilterWhere(['like', 'insurance_number', $this->insurance_number])
                ->andFilterWhere(['like', 'insurance_status', $this->insurance_status])
                ->andFilterWhere(['like', 'insurance_company.company_name', $this->insuranceCompanyName]);



        return $dataProvider;
    }

}
