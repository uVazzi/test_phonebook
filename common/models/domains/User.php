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

    protected $itemsToDelete;

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

    /** Загружает данные в модель, заполняет связи
     * @param $data
     * @param null $formName
     * @return bool
     */
    public function loadWithRelations($data, $formName = null)
    {
        if (empty($data)) {
            return false;
        }

        $this->load($data);

        $phones = $this->phones;
        $idToItem = [];
        foreach ($phones as $item) {
            $idToItem[$item->id] = $item;
        }
        if (!empty($data['Phone'])) {
            $actualItems = [];
            $phonesFormData = $data['Phone'];
            foreach ($phonesFormData as $item) {
                if (!empty($item['id'])) {
                    $phoneModel = $idToItem[$item['id']];
                    unset($idToItem[$item['id']]);
                } else {
                    $phoneModel = new Phone();
                }
                $phoneModel->load($item, '');
                $actualItems[] = $phoneModel;
            }
            $this->populateRelation('phones', $actualItems);
        }
        $this->itemsToDelete = array_values($idToItem);
        return true;
    }

    /** Валидация номеров
     * @return bool
     */
    public function validatePhones()
    {
        $hasError = false;
        if (!empty($this->phones)) {
            foreach ($this->phones as $item) {
                if (!$item->validate()) {
                    $hasError = true;
                }
            }
        }
        return !$hasError;
    }

    public function transactionsWithRelations()
    {
        $hasError = false;
        $transaction = \Yii::$app->db->beginTransaction();
        if ($this->save(false)) {
            /** @var ActiveRecord $itemToDelete */
            foreach ($this->itemsToDelete as $itemToDelete) {
                if ($itemToDelete->delete()) {
                    $hasError = true;
                }
            }
            foreach ($this->phones as $item) {
                $item->user_id = $this->id;
                if (!$item->save(false)) {
                    $hasError = true;
                }
            }
        } else {
            $hasError = true;
        }
        if ($hasError == false) {
            $transaction->commit();
            return true;
        } else {
            $transaction->rollback();
            return false;
        }
    }
}
