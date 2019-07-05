<?php

namespace common\models\domains;

use Yii;
use yii\behaviors\TimestampBehavior;
use yii\db\ActiveRecord;
use yii2tech\ar\softdelete\SoftDeleteBehavior;

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

    public function behaviors()
    {
        return [
            'timestamp' => [
                'class' => TimestampBehavior::className(),
                'attributes' => [
                    ActiveRecord::EVENT_BEFORE_INSERT => ['create_at', 'update_at'],
                    ActiveRecord::EVENT_BEFORE_UPDATE => ['update_at'],
                ],
            ],
            'softDelete' => [
                'class' => SoftDeleteBehavior:: className(),
                'softDeleteAttributeValues' => ['is_deleted' => true], 'replaceRegularDelete' => true
            ],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['date_birth'], 'date', 'format' => 'd.m.y'],
            [['name', 'surname', 'patronymic'], 'string', 'max' => 50],
            [['name', 'surname', 'patronymic'], 'filter', 'filter' => 'trim', 'skipOnArray' => true],
            [['name', 'surname', 'patronymic', 'date_birth'], 'required'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'name' => 'Имя',
            'surname' => 'Фамилия',
            'patronymic' => 'Отчество',
            'date_birth' => 'Дата рождения',
            'create_at' => 'Создан',
            'update_at' => 'Последнее изменение',
            'is_deleted' => 'Is Deleted',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getPhones()
    {
        return $this->hasMany(Phone::className(), ['user_id' => 'id'])->andWhere(['is_deleted' => false]);
    }
}
