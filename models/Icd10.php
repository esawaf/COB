<?php

namespace app\models;

use yii\data\ActiveDataProvider;
use Yii;

/**
 * This is the model class for table "icd10".
 *
 * @property int $id
 * @property string $icd10_code
 * @property resource $description
 *
 * @property CustomIcd10[] $customIcd10s
 * @property VisitAssessment[] $visitAssessments
 */
class Icd10 extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'icd10';
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

    /**
     * Gets query for [[CustomIcd10s]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getCustomIcd10s()
    {
        return $this->hasMany(CustomIcd10::className(), ['icd10_id' => 'id']);
    }

    /**
     * Gets query for [[VisitAssessments]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getVisitAssessments()
    {
        return $this->hasMany(VisitAssessment::className(), ['icd10_id' => 'id']);
    }
    
    public function search($params) {
        $query = self::find();
        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'sort' => ['attributes' => ['icd10_code', 'description']]
        ]);
        $this->load($params);
        
        $query->andFilterWhere(['like', 'icd10_code', $this->icd10_code])
              ->andFilterWhere(['like', 'description', $this->description]);

        return $dataProvider;

    }
}
