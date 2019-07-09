<?php

namespace common\models\domains;

use common\models\enums\NumberType;
use Yii;
use yii\behaviors\TimestampBehavior;
use yii\db\ActiveRecord;
use yii2tech\ar\softdelete\SoftDeleteBehavior;
use borales\extensions\phoneInput\PhoneInputValidator;

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
            [['type', 'number'], 'required'],
            [['type', 'user_id', 'create_at', 'update_at', 'is_deleted'], 'integer'],
            ['type', 'in', 'range' => NumberType::getConstantsByName()],
            [['number'], 'string'],
            [['number'], PhoneInputValidator::className()],
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
            'type' => 'Тип',
            'number' => 'Номер',
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
