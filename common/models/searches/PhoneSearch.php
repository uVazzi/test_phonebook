<?php

namespace common\models\searches;

use common\models\enums\NumberType;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use common\models\domains\Phone;

/**
 * PhoneSearch represents the model behind the search form of `common\models\domains\Phone`.
 */
class PhoneSearch extends Phone
{
    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id', 'type', 'user_id', 'create_at', 'update_at', 'is_deleted'], 'integer'],
            [['number'], 'safe'],
            ['type', 'in', 'range' => NumberType::getConstantsByName()],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function scenarios()
    {
        // bypass scenarios() implementation in the parent class
        return Model::scenarios();
    }

    /**
     * Creates data provider instance with search query applied
     *
     * @param array $params
     *
     * @param $userId
     * @return ActiveDataProvider
     */
    public function search($params, $userId)
    {
        $query = Phone::find()->where(['user_id' => $userId, 'is_deleted' => false]);

        // add conditions that should always apply here

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        $this->load($params);

        if (!$this->validate()) {
            // uncomment the following line if you do not want to return any records when validation fails
            // $query->where('0=1');
            return $dataProvider;
        }

        // grid filtering conditions
        $query->andFilterWhere([
            'id' => $this->id,
            'type' => $this->type,
            'user_id' => $this->user_id,
            'create_at' => $this->create_at,
            'update_at' => $this->update_at,
            'is_deleted' => $this->is_deleted,
        ]);

        $query->andFilterWhere(['like', 'number', $this->number]);

        return $dataProvider;
    }
}
