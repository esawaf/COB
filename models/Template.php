<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "template".
 *
 * @property int $id
 * @property string $template_name
 * @property string $template
 * @property int $organization_id
 * @property int $active
 *
 * @property Organization $organization
 * @property VisitReportData[] $visitReportDatas
 */
class Template extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'template';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['template_name', 'template', 'organization_id', 'active'], 'required'],
            [['template'], 'string'],
            [['organization_id', 'active'], 'integer'],
            [['template_name'], 'string', 'max' => 200],
            [['organization_id'], 'exist', 'skipOnError' => true, 'targetClass' => Organization::className(), 'targetAttribute' => ['organization_id' => 'id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'template_name' => 'Template Name',
            'template' => 'Template',
            'organization_id' => 'Organization ID',
            'active' => 'Active',
        ];
    }

    /**
     * Gets query for [[Organization]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getOrganization()
    {
        return $this->hasOne(Organization::className(), ['id' => 'organization_id']);
    }

    /**
     * Gets query for [[VisitReportDatas]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getVisitReportDatas()
    {
        return $this->hasMany(VisitReportData::className(), ['visit_template_id' => 'id']);
    }
}
