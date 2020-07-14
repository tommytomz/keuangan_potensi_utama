<?php

namespace app\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\SubAkun;

/**
 * SubAkunSearch represents the model behind the search form about `app\models\SubAkun`.
 */
class SubAkunSearch extends SubAkun
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id', 'idakun', 'kode_akun'], 'integer'],
            [['nama_sub_akun'], 'safe'],
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
        $query = SubAkun::find();

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
            'idakun' => $this->idakun,
            'kode_akun' => $this->kode_akun,
        ]);

        $query->andFilterWhere(['like', 'nama_sub_akun', $this->nama_sub_akun]);

        return $dataProvider;
    }
}
