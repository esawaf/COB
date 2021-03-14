<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "visit_objective".
 *
 * @property int $id
 * @property int $visit_report_id
 * @property int|null $weight
 * @property int|null $height
 * @property resource|null $vitals
 * @property resource|null $functional_measurement
 * @property resource|null $observation
 *
 * @property VisitReport $visitReport
 */
class VisitObjective extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'visit_objective';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['visit_report_id'], 'required'],
            [['visit_report_id', 'weight', 'height'], 'integer'],
            [['vitals', 'functional_measurement', 'observation'], 'string'],
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
            'weight' => 'Weight',
            'height' => 'Height',
            'vitals' => 'Vitals',
            'functional_measurement' => 'Functional Measurement',
            'observation' => 'Observation',
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
