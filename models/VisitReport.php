<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "visit_report".
 *
 * @property int $id
 * @property int $visit_id
 * @property int $doctor_id
 * @property string $date
 *
 * @property VisitAssessment[] $visitAssessments
 * @property VisitBilling[] $visitBillings
 * @property VisitIcd10[] $visitIcd10s
 * @property VisitObjective[] $visitObjectives
 * @property Login $doctor
 * @property Visit $visit
 * @property VisitReportData[] $visitReportDatas
 * @property VisitSubjective[] $visitSubjectives
 */
class VisitReport extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'visit_report';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['visit_id', 'doctor_id'], 'required'],
            [['visit_id', 'doctor_id'], 'integer'],
            [['date'], 'safe'],
            [['doctor_id'], 'exist', 'skipOnError' => true, 'targetClass' => Login::className(), 'targetAttribute' => ['doctor_id' => 'id']],
            [['visit_id'], 'exist', 'skipOnError' => true, 'targetClass' => Visit::className(), 'targetAttribute' => ['visit_id' => 'id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'visit_id' => 'Visit ID',
            'doctor_id' => 'Doctor ID',
            'date' => 'Date',
        ];
    }

    /**
     * Gets query for [[VisitAssessments]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getVisitAssessments()
    {
        return $this->hasMany(VisitAssessment::className(), ['visit_report_id' => 'id']);
    }

    /**
     * Gets query for [[VisitBillings]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getVisitBillings()
    {
        return $this->hasMany(VisitBilling::className(), ['visit_report_id' => 'id']);
    }

    /**
     * Gets query for [[VisitIcd10s]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getVisitIcd10s()
    {
        return $this->hasMany(VisitIcd10::className(), ['visit_report_id' => 'id']);
    }

    /**
     * Gets query for [[VisitObjectives]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getVisitObjectives()
    {
        return $this->hasMany(VisitObjective::className(), ['visit_report_id' => 'id']);
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
     * Gets query for [[Visit]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getVisit()
    {
        return $this->hasOne(Visit::className(), ['id' => 'visit_id']);
    }

    /**
     * Gets query for [[VisitReportDatas]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getVisitReportDatas()
    {
        return $this->hasMany(VisitReportData::className(), ['visit_report_id' => 'id']);
    }

    /**
     * Gets query for [[VisitSubjectives]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getVisitSubjectives()
    {
        return $this->hasMany(VisitSubjective::className(), ['visit_report_id' => 'id']);
    }
}
