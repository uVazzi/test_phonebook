<?php

namespace common\models\domains;

use Yii;

/**
 * This is the model class for table "user".
 *
 * @property int $id
 * @property string $name
 * @property string $surname
 * @property string $patronymic
 * @property string $date_birth
 * @property int $create_at
 * @property int $update_at
 * @property int $is_deleted
 *
 * @property Phone[] $phones
 */
class User extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'user';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['name', 'surname', 'patronymic', 'date_birth', 'create_at', 'update_at'], 'required'],
            [['create_at', 'update_at', 'is_deleted'], 'integer'],
            [['name', 'surname', 'patronymic'], 'string', 'max' => 50],
            [['date_birth'], 'string', 'max' => 10],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'name' => 'Name',
            'surname' => 'Surname',
            'patronymic' => 'Patronymic',
            'date_birth' => 'Date Birth',
            'create_at' => 'Create At',
            'update_at' => 'Update At',
            'is_deleted' => 'Is Deleted',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getPhones()
    {
        return $this->hasMany(Phone::className(), ['user_id' => 'id']);
    }
}
