<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "custom_icd10".
 *
 * @property int $id
 * @property int $login_id
 * @property int $icd10_id
 * @property string $custom_id
 * @property string $custom_description
 *
 * @property Icd10 $icd10
 * @property Login $login
 * @property VisitIcd10[] $visitIcd10s
 */
class CustomIcd10 extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'custom_icd10';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['login_id', 'icd10_id', 'custom_id', 'custom_description'], 'required'],
            [['login_id', 'icd10_id'], 'integer'],
            [['custom_id'], 'string', 'max' => 100],
            [['custom_description'], 'string', 'max' => 500],
            [['icd10_id'], 'exist', 'skipOnError' => true, 'targetClass' => Icd10::className(), 'targetAttribute' => ['icd10_id' => 'id']],
            [['login_id'], 'exist', 'skipOnError' => true, 'targetClass' => Login::className(), 'targetAttribute' => ['login_id' => 'id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'login_id' => 'Login ID',
            'icd10_id' => 'Icd10 ID',
            'custom_id' => 'Custom ID',
            'custom_description' => 'Custom Description',
        ];
    }

    /**
     * Gets query for [[Icd10]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getIcd10()
    {
        return $this->hasOne(Icd10::className(), ['id' => 'icd10_id']);
    }

    /**
     * Gets query for [[Login]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getLogin()
    {
        return $this->hasOne(Login::className(), ['id' => 'login_id']);
    }

    /**
     * Gets query for [[VisitIcd10s]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getVisitIcd10s()
    {
        return $this->hasMany(VisitIcd10::className(), ['custom_icd10_id' => 'id']);
    }
}
