<?php

namespace app\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\SaldoAwal;

/**
 * SaldoAwalSearch represents the model behind the search form about `app\models\SaldoAwal`.
 */
class SaldoAwalSearch extends SaldoAwal
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id', 'idsubakun'], 'integer'],
            [['debet', 'kredit'], 'number'],
            [['tanggal'], 'safe'],
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
        $query = SaldoAwal::find();

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
            'idsubakun' => $this->idsubakun,
            'debet' => $this->debet,
            'kredit' => $this->kredit,
            'tanggal' => $this->tanggal,
        ]);

        return $dataProvider;
    }
}
