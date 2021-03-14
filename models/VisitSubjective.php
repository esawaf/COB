<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "visit_subjective".
 *
 * @property int $id
 * @property int $visit_report_id
 * @property resource|null $history
 * @property resource|null $chief_complaint
 * @property int|null $pain_level
 * @property resource|null $medical_history
 * @property resource|null $medication_history
 *
 * @property VisitReport $visitReport
 */
class VisitSubjective extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'visit_subjective';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['visit_report_id'], 'required'],
            [['visit_report_id', 'pain_level'], 'integer'],
            [['history', 'chief_complaint', 'medical_history', 'medication_history'], 'string'],
            [['visit_report_id'], 'exist', 'skipOnError' => true, 'targetClass' => VisitReport::className(), 'targetAttribute' => ['visit_report_id' => 'id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'visit_report_id' => 'Visit Report ID',
            'history' => 'History',
            'chief_complaint' => 'Chief Complaint',
            'pain_level' => 'Pain Level',
            'medical_history' => 'Medical History',
            'medication_history' => 'Medication History',
        ];
    }

    /**
     * Gets query for [[VisitReport]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getVisitReport()
    {
        return $this->hasOne(VisitReport::className(), ['id' => 'visit_report_id']);
    }
}
