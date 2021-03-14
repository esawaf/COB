<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "login".
 *
 * @property int $id
 * @property string $username
 * @property string $password
 * @property string $email
 * @property string $type
 * @property int $active
 * @property int $confirmed
 * @property string|null $last_login
 * @property string $register_date
 * @property string $name
 * @property string|null $phone
 * @property string|null $id_number
 * @property string|null $speciality
 * @property string|null $provider_id
 * @property int|null $organization_id
 * @property int|null $selected_location
 * @property int|null $insurance_id
 *
 * @property CustomCpt[] $customCpts
 * @property CustomIcd10[] $customIcd10s
 * @property InboxThread[] $inboxThreads
 * @property InboxThread[] $inboxThreads0
 * @property InsuranceCompany $insurance
 * @property LoginLocation[] $loginLocations
 * @property Message[] $messages
 * @property Prescription[] $prescriptions
 * @property Visit[] $visits
 * @property VisitReport[] $visitReports
 */
class Login extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'login';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['username', 'password', 'email', 'type', 'active', 'confirmed', 'name'], 'required'],
            [['type'], 'string'],
            [['active', 'confirmed', 'organization_id', 'selected_location', 'insurance_id'], 'integer'],
            [['last_login', 'register_date'], 'safe'],
            [['username', 'password'], 'string', 'max' => 100],
            [['email', 'name', 'phone', 'id_number', 'speciality', 'provider_id'], 'string', 'max' => 500],
            [['username'], 'unique'],
            [['insurance_id'], 'exist', 'skipOnError' => true, 'targetClass' => InsuranceCompany::className(), 'targetAttribute' => ['insurance_id' => 'id']],
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
            'username' => 'Username',
            'password' => 'Password',
            'email' => 'Email',
            'type' => 'Type',
            'active' => 'Active',
            'confirmed' => 'Confirmed',
            'last_login' => 'Last Login',
            'register_date' => 'Register Date',
            'name' => 'Name',
            'phone' => 'Phone',
            'id_number' => 'Id Number',
            'speciality' => 'Speciality',
            'provider_id' => 'Provider ID',
            'organization_id' => 'Organization ID',
            'selected_location' => 'Selected Location',
            'insurance_id' => 'Insurance ID',
        ];
    }

    /**
     * Gets query for [[Cpts]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getCustomCpts()
    {
        return $this->hasMany(CustomCpt::className(), ['doctor_id' => 'id']);
    }

    /**
     * Gets query for [[CustomIcd10s]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getCustomIcd10s()
    {
        return $this->hasMany(CustomIcd10::className(), ['login_id' => 'id']);
    }

    /**
     * Gets query for [[InboxThreads]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getInboxThreads()
    {
        return InboxThread::find()->where(['sender_id' => $this->id])
                ->orWhere(['receiver_id' => $this->id])
                ->addOrderBy("last_message_time DESC")
                ->all();
        //return $this->hasMany(InboxThread::className(), ['sender_id' => 'id']);
    }

    /**
     * Gets query for [[InboxThreads0]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getInboxThreads0()
    {
        return $this->hasMany(InboxThread::className(), ['receiver_id' => 'id']);
    }

    /**
     * Gets query for [[Insurance]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getInsurance()
    {
        return $this->hasOne(InsuranceCompany::className(), ['id' => 'insurance_id']);
    }

    /**
    * Gets query for [[Prescriptions]]. 
    * 
    * @return \yii\db\ActiveQuery 
    */ 
   public function getPrescriptions() 
   { 
       return $this->hasMany(Prescription::className(), ['pharmaciest_id' => 'id']); 
   } 

    /**
     * Gets query for [[LoginLocations]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getLoginLocations()
    {
        return $this->hasMany(LoginLocation::className(), ['login_id' => 'id']);
    }

    /**
     * Gets query for [[Messages]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getMessages()
    {
        return $this->hasMany(Message::className(), ['sender_id' => 'id']);
    }

    /**
     * Gets query for [[Visits]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getVisits()
    {
        return $this->hasMany(Visit::className(), ['doctor_id' => 'id']);
    }
    
    public function validatePassword($password,$passwordConfirmation) {  
      if($password!==$passwordConfirmation){  
          $this->addError('password','Password and password confirmation are not identical!');  
          return false;  
      }  
      return true;  
  }
  
  /**
    * Gets query for [[VisitReports]].
    *
    * @return \yii\db\ActiveQuery
    */
   public function getVisitReports()
   {
       return $this->hasMany(VisitReport::className(), ['doctor_id' => 'id']);
   }
  
}
