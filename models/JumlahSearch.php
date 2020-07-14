<?php

namespace app\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\Jumlah;

/**
 * JumlahSearch represents the model behind the search form about `app\models\Jumlah`.
 */
class JumlahSearch extends Jumlah
{
    /**
     * @inheritdoc
     */
    public $idfakultas;

    public function rules()
    {
        return [
            [['id', 'idfakultas', 'idprodi', 'idtahunajaran', 'jumlah_mahasiswa', 'jumlah_dosen'], 'integer'],
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
        $query = Jumlah::find()->joinWith('fakultas');

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        $this->load($params);

        if (!$this->validate()) {
            // uncomment the following line if you do not want to return any records when validation fails
            // $query->where('0=1');
            return $dataProvider;
        }

        $tahunajaran = $this->idtahunajaran;
        $prodi       = Yii::$app->user->identity->idprodi;

        if(empty($tahunajaran)){
            $tahunajaran = $_SESSION['idtahunajaran'];
        }

        if($prodi==0){
            $prodi = $this->idprodi;
        }

        $query->andFilterWhere([
            'id' => $this->id,
            'fakultas.id' => $this->idfakultas,
            'idprodi' => $prodi,
            'idtahunajaran' => $tahunajaran,
            'jumlah_mahasiswa' => $this->jumlah_mahasiswa,
            'jumlah_dosen' => $this->jumlah_dosen,
        ]);

        return $dataProvider;
    }
}
