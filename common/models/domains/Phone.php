<?php

namespace common\models\domains;

use Yii;

/**
 * This is the model class for table "phone".
 *
 * @property int $id
 * @property int $type
 * @property string $number
 * @property int $user_id
 * @property int $create_at
 * @property int $update_at
 * @property int $is_deleted
 *
 * @property User $user
 */
class Phone extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'phone';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['type', 'number', 'user_id', 'create_at', 'update_at'], 'required'],
            [['type', 'user_id', 'create_at', 'update_at', 'is_deleted'], 'integer'],
            [['number'], 'string', 'max' => 20],
            [['user_id'], 'exist', 'skipOnError' => true, 'targetClass' => User::className(), 'targetAttribute' => ['user_id' => 'id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'type' => 'Type',
            'number' => 'Number',
            'user_id' => 'User ID',
            'create_at' => 'Create At',
            'update_at' => 'Update At',
            'is_deleted' => 'Is Deleted',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getUser()
    {
        return $this->hasOne(User::className(), ['id' => 'user_id']);
    }
}
