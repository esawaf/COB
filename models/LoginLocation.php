<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "login_location".
 *
 * @property int $id
 * @property int $login_id
 * @property int $location_id
 *
 * @property Login $login
 * @property OrganizationLocation $location
 */
class LoginLocation extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'login_location';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['login_id', 'location_id'], 'required'],
            [['login_id', 'location_id'], 'integer'],
            [['login_id'], 'exist', 'skipOnError' => true, 'targetClass' => Login::className(), 'targetAttribute' => ['login_id' => 'id']],
            [['location_id'], 'exist', 'skipOnError' => true, 'targetClass' => OrganizationLocation::className(), 'targetAttribute' => ['location_id' => 'id']],
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
            'location_id' => 'Location ID',
        ];
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
     * Gets query for [[Location]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getLocation()
    {
        return $this->hasOne(OrganizationLocation::className(), ['id' => 'location_id']);
    }
}
