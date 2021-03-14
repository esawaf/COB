<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "visit_assessment".
 *
 * @property int $id
 * @property int $visit_report_id
 * @property resource|null $doctor_assessment
 * @property resource|null $recommendations
 *
 * @property VisitReport $visitReport
 */
class VisitAssessment extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'visit_assessment';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['visit_report_id'], 'required'],
            [['visit_report_id'], 'integer'],
            [['doctor_assessment', 'recommendations'], 'string'],
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
            'doctor_assessment' => 'Doctor Assessment',
            'recommendations' => 'Recommendations',
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
