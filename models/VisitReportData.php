<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "visit_report_data".
 *
 * @property int $id
 * @property int $visit_report_id
 * @property int $visit_template_id
 * @property string $visit_data
 *
 * @property Template $visitTemplate
 * @property VisitReport $visitReport
 */
class VisitReportData extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'visit_report_data';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['visit_report_id', 'visit_template_id', 'visit_data'], 'required'],
            [['visit_report_id', 'visit_template_id'], 'integer'],
            [['visit_data'], 'string'],
            [['visit_template_id'], 'exist', 'skipOnError' => true, 'targetClass' => Template::className(), 'targetAttribute' => ['visit_template_id' => 'id']],
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
            'visit_template_id' => 'Visit Template ID',
            'visit_data' => 'Visit Data',
        ];
    }

    /**
     * Gets query for [[VisitTemplate]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getVisitTemplate()
    {
        return $this->hasOne(Template::className(), ['id' => 'visit_template_id']);
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
