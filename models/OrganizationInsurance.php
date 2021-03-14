<?php

namespace app\models;

use Yii;
use yii\data\ActiveDataProvider;

/**
 * This is the model class for table "organization_insurance".
 *
 * @property int $id
 * @property int $organization_id
 * @property int $insurance_company_id
 * @property string $status
 *
 * @property InsuranceCompany $insuranceCompany
 * @property Organization $organization
 */
class OrganizationInsurance extends \yii\db\ActiveRecord {

    public $insuranceCompanyName;
    public $organizationName;

    /**
     * {@inheritdoc}
     */
    public static function tableName() {
        return 'organization_insurance';
    }

    /**
     * {@inheritdoc}
     */
    public function rules() {
        return [
            [['organization_id', 'insurance_company_id', 'status'], 'required'],
            [['organization_id', 'insurance_company_id'], 'integer'],
            [['status'], 'string'],
            [['insurance_company_id'], 'exist', 'skipOnError' => true, 'targetClass' => InsuranceCompany::className(), 'targetAttribute' => ['insurance_company_id' => 'id']],
            [['organization_id'], 'exist', 'skipOnError' => true, 'targetClass' => Organization::className(), 'targetAttribute' => ['organization_id' => 'id']],
            [['insuranceCompanyName'], 'safe'],
            [['organizationName'], 'safe'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels() {
        return [
            'id' => 'ID',
            'organization_id' => 'Organization ID',
            'insurance_company_id' => 'Insurance Company ID',
            'status' => 'Status',
            'insuranceCompanyName' => Yii::t('app', 'Insuracne Company Name'),
            'organizationName' => Yii::t('app', 'Organization Name'),
        ];
    }

    /**
     * Gets query for [[InsuranceCompany]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getInsuranceCompany() {
        return $this->hasOne(InsuranceCompany::className(), ['id' => 'insurance_company_id']);
    }

    /**
     * Gets query for [[Organization]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getOrganization() {
        return $this->hasOne(Organization::className(), ['id' => 'organization_id']);
    }

    public function getInsuranceCompanyName() {
        return $this->insuranceCompany->company_name;
    }

    public function getOrganizationName() {
        return $this->organization->name;
    }

    public function search($params) {
        $query = self::find();
        $query->joinWith('insuranceCompany', true);
        $query->joinWith('organization', true);

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'sort' => ['attributes' =>
                [
                    'status',
                    'insuranceCompanyName' => [
                        'asc' => ['insurance_company.company_name' => SORT_ASC],
                        'desc' => ['insurance_company.company_name' => SORT_DESC],
                        'label' => 'Insurance Company Name'],
                    'organizationName' => [
                        'asc' => ['organization.name' => SORT_ASC],
                        'desc' => ['organization.name' => SORT_DESC],
                        'label' => 'Organuization Name'],
                ]
            ]
        ]);
        $this->load($params);

        $query->andFilterWhere(['like', 'status', $this->status])
                ->andFilterWhere(['like', 'insurance_company.company_name', $this->insuranceCompanyName])
                ->andFilterWhere(['like', 'organization.name', $this->organizationName]);



        return $dataProvider;
    }

}
