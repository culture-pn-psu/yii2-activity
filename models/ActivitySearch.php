<?php

namespace culturePnPsu\activity\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use culturePnPsu\activity\models\Activity;

/**
 * ActivitySearch represents the model behind the search form about `culturePnPsu\activity\models\Activity`.
 */
class ActivitySearch extends Activity
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id', 'calendar_id', 'status', 'article_id', 'created_at', 'created_by', 'updated_at', 'updated_by'], 'integer'],
            [['title', 'detail', 'link', 'start', 'end', 'allday'], 'safe'],
        ];
    }

    /**
     * @inheritdoc
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
     * @return ActiveDataProvider
     */
    public function search($params)
    {
        $query = Activity::find();

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        $this->load($params);

        if (!$this->validate()) {
            // uncomment the following line if you do not want to return any records when validation fails
            // $query->where('0=1');
            return $dataProvider;
        }

        $query->andFilterWhere([
            'id' => $this->id,
            'calendar_id' => $this->calendar_id,
            'status' => $this->status,
            'start' => $this->start,
            'end' => $this->end,
            'article_id' => $this->article_id,
            'created_at' => $this->created_at,
            'created_by' => $this->created_by,
            'updated_at' => $this->updated_at,
            'updated_by' => $this->updated_by,
        ]);

        $query->andFilterWhere(['like', 'title', $this->title])
            ->andFilterWhere(['like', 'detail', $this->detail])
            ->andFilterWhere(['like', 'link', $this->link])
            ->andFilterWhere(['like', 'allday', $this->allday]);

        return $dataProvider;
    }
}
