<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "cpt".
 *
 * @property int $id
 * @property string $code
 * @property resource $description
 *
 * @property CustomCpt[] $customCpts
 */
class Cpt extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'cpt';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['code', 'description'], 'required'],
            [['description'], 'string'],
            [['code'], 'string', 'max' => 500],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'code' => 'Code',
            'description' => 'Description',
        ];
    }

    /**
     * Gets query for [[CustomCpts]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getCustomCpts()
    {
        return $this->hasMany(CustomCpt::className(), ['cpt_id' => 'id']);
    }
}
