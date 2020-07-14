<?php

namespace app\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\Pendapatan;

/**
 * PendapatanSearch represents the model behind the search form about `app\models\Pendapatan`.
 */
class PendapatanJasaSearch extends Pendapatan
{
    /**
     * @inheritdoc
     */
    public $idfakultas;

    public function rules()
    {
        return [
            [['id', 'idfakultas', 'idprodi', 'idjenispendapatan', 'jumlah', 'idtahunajaran'], 'integer'],
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
        $query = Pendapatan::find()->joinWith('fakultas');

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
            'idprodi'=>$prodi,
            'idjenispendapatan' => 6,
            'jumlah' => $this->jumlah,
            'idtahunajaran' => $tahunajaran,
            'tanggal' => $this->tanggal,
        ]);

        return $dataProvider;
    }

    public function searchProdi($namaprodi){
        $hasil = 0;
        $data = Prodi::find()->where(['nama_prodi'=>$namaprodi])->one();
        if(empty($data['id'])){
            $hasil = '4';
        }
        else{
            $hasil = $data['id'];
        }
        return $hasil;
    }
}
