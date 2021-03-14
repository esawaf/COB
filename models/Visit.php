<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "visit".
 *
 * @property int $id
 * @property string $uuid
 * @property string $visit_date
 * @property int $patient_id
 * @property int $doctor_id
 * @property int $location_id
 * @property string $status
 * @property string|null $insurance_payment_status
 * @property int $send_to_insurance
 * @property int $review
 *
 * @property InboxThread[] $inboxThreads
 * @property Login $doctor
 * @property OrganizationLocation $location
 * @property Patient $patient
 * @property VisitReport[] $visitReports
 */
class Visit extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'visit';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['uuid', 'patient_id', 'doctor_id', 'location_id', 'status', 'send_to_insurance', 'review'], 'required'],
            [['visit_date'], 'safe'],
            [['patient_id', 'doctor_id', 'location_id', 'send_to_insurance', 'review'], 'integer'],
            [['status', 'insurance_payment_status'], 'string'],
            [['uuid'], 'string', 'max' => 300],
            [['doctor_id'], 'exist', 'skipOnError' => true, 'targetClass' => Login::className(), 'targetAttribute' => ['doctor_id' => 'id']],
            [['location_id'], 'exist', 'skipOnError' => true, 'targetClass' => OrganizationLocation::className(), 'targetAttribute' => ['location_id' => 'id']],
            [['patient_id'], 'exist', 'skipOnError' => true, 'targetClass' => Patient::className(), 'targetAttribute' => ['patient_id' => 'id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'uuid' => 'Uuid',
            'visit_date' => 'Visit Date',
            'patient_id' => 'Patient ID',
            'doctor_id' => 'Doctor ID',
            'location_id' => 'Location ID',
            'status' => 'Status',
            'insurance_payment_status' => 'Insurance Payment Status',
            'send_to_insurance' => 'Send To Insurance',
            'review' => 'Review',
        ];
    }

    /**
     * Gets query for [[InboxThreads]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getInboxThreads()
    {
        return $this->hasMany(InboxThread::className(), ['visit_id' => 'id']);
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
     * Gets query for [[Location]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getLocation()
    {
        return $this->hasOne(OrganizationLocation::className(), ['id' => 'location_id']);
    }

    /**
     * Gets query for [[Patient]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getPatient()
    {
        return $this->hasOne(Patient::className(), ['id' => 'patient_id']);
    }

    /**
     * Gets query for [[VisitReports]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getVisitReports()
    {
        return $this->hasMany(VisitReport::className(), ['visit_id' => 'id']);
    }
}
