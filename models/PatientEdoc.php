<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "patient_edoc".
 *
 * @property int $id
 * @property int $patient_id
 * @property string $name
 * @property string $date
 * @property string $file_path
 *
 * @property Patient $patient
 */
class PatientEdoc extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'patient_edoc';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['patient_id', 'name', 'file_path'], 'required'],
            [['patient_id'], 'integer'],
            [['date'], 'safe'],
            [['name'], 'string', 'max' => 250],
            [['file_path'], 'string', 'max' => 500],
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
            'patient_id' => 'Patient ID',
            'name' => 'Name',
            'date' => 'Date',
            'file_path' => 'File Path',
        ];
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
}
