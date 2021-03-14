<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "inbox_thread".
 *
 * @property int $id
 * @property int $sender_id
 * @property int $receiver_id
 * @property int $visit_id
 * @property string $title
 * @property string $date
 * @property string $last_message_time
 * @property string|null $badge
 * @property string|null $badge_color
 *
 * @property Login $sender
 * @property Login $receiver
 * @property Visit $visit
 * @property Message[] $messages
 */
class InboxThread extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'inbox_thread';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['sender_id', 'receiver_id', 'visit_id', 'title'], 'required'],
            [['sender_id', 'receiver_id', 'visit_id'], 'integer'],
            [['date', 'last_message_time'], 'safe'],
            [['badge_color'], 'string'],
            [['title'], 'string', 'max' => 500],
            [['badge'], 'string', 'max' => 100],
            [['sender_id'], 'exist', 'skipOnError' => true, 'targetClass' => Login::className(), 'targetAttribute' => ['sender_id' => 'id']],
            [['receiver_id'], 'exist', 'skipOnError' => true, 'targetClass' => Login::className(), 'targetAttribute' => ['receiver_id' => 'id']],
            [['visit_id'], 'exist', 'skipOnError' => true, 'targetClass' => Visit::className(), 'targetAttribute' => ['visit_id' => 'id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'sender_id' => 'Sender ID',
            'receiver_id' => 'Receiver ID',
            'visit_id' => 'Visit ID',
            'title' => 'Title',
            'date' => 'Date',
            'last_message_time' => 'Last Message Time',
            'badge' => 'Badge',
            'badge_color' => 'Badge Color',
        ];
    }

    /**
     * Gets query for [[Sender]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getSender()
    {
        return $this->hasOne(Login::className(), ['id' => 'sender_id']);
    }

    /**
     * Gets query for [[Receiver]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getReceiver()
    {
        return $this->hasOne(Login::className(), ['id' => 'receiver_id']);
    }

    /**
     * Gets query for [[Visit]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getVisit()
    {
        return $this->hasOne(Visit::className(), ['id' => 'visit_id']);
    }

    /**
     * Gets query for [[Messages]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getMessages()
    {
        return $this->hasMany(Message::className(), ['thread_id' => 'id']);
    }
    public function getLastMessage() {
        return Message::find()->where(['thread_id' => $this->id])->addOrderBy("date DESC")->one();
    }
}
