<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "icd10_copy".
 *
 * @property int $id
 * @property string $icd10_code
 * @property resource $description
 */
class Icd10Copy extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'icd10_copy';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['icd10_code', 'description'], 'required'],
            [['description'], 'string'],
            [['icd10_code'], 'string', 'max' => 500],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'icd10_code' => 'Icd10 Code',
            'description' => 'Description',
        ];
    }
}
