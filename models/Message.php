<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "message".
 *
 * @property int $id
 * @property int $thread_id
 * @property int $sender_id
 * @property string $message
 * @property string|null $attachment_path
 * @property int $read
 * @property string $date
 *
 * @property InboxThread $thread
 * @property Login $sender
 */
class Message extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'message';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['thread_id', 'sender_id', 'message', 'read'], 'required'],
            [['thread_id', 'sender_id', 'read'], 'integer'],
            [['message'], 'string'],
            [['date'], 'safe'],
            [['attachment_path'], 'string', 'max' => 500],
            [['thread_id'], 'exist', 'skipOnError' => true, 'targetClass' => InboxThread::className(), 'targetAttribute' => ['thread_id' => 'id']],
            [['sender_id'], 'exist', 'skipOnError' => true, 'targetClass' => Login::className(), 'targetAttribute' => ['sender_id' => 'id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'thread_id' => 'Thread ID',
            'sender_id' => 'Sender ID',
            'message' => 'Message',
            'attachment_path' => 'Attachment Path',
            'read' => 'Read',
            'date' => 'Date',
        ];
    }

    /**
     * Gets query for [[Thread]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getThread()
    {
        return $this->hasOne(InboxThread::className(), ['id' => 'thread_id']);
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
}
