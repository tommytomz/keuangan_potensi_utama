<?php

namespace app\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\Prodi;

/**
 * ProdiSearch represents the model behind the search form about `app\models\Prodi`.
 */
class ProdiSearch extends Prodi
{
    /**
     * @inheritdoc
     */
    public $nama_fakultas;
    
    public function rules()
    {
        return [
            [['id', 'idfakultas'], 'integer'],
            [['nama_fakultas', 'nama_prodi'], 'safe'],
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
        $query = Prodi::find();

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
            'idfakultas' => $this->idfakultas,
        ]);

        $query->andFilterWhere(['like', 'nama_prodi', $this->nama_prodi]);
        $query->andFilterWhere(['like', 'fakultas.nama_fakultas', $this->nama_fakultas]);

        return $dataProvider;
    }
}
