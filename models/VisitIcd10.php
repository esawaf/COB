<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "visit_icd10".
 *
 * @property int $id
 * @property int $visit_report_id
 * @property int $custom_icd10_id
 *
 * @property CustomIcd10 $customIcd10
 * @property VisitReport $visitReport
 */
class VisitIcd10 extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'visit_icd10';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['visit_report_id', 'custom_icd10_id'], 'required'],
            [['visit_report_id', 'custom_icd10_id'], 'integer'],
            [['custom_icd10_id'], 'exist', 'skipOnError' => true, 'targetClass' => CustomIcd10::className(), 'targetAttribute' => ['custom_icd10_id' => 'id']],
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
            'custom_icd10_id' => 'Custom Icd10 ID',
        ];
    }

    /**
     * Gets query for [[CustomIcd10]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getCustomIcd10()
    {
        return $this->hasOne(CustomIcd10::className(), ['id' => 'custom_icd10_id']);
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
