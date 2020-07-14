<?php

namespace app\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\Pendapatan;

/**
 * PendapatanSearch represents the model behind the search form about `app\models\Pendapatan`.
 */
class PendapatanSearch extends Pendapatan
{
    /**
     * @inheritdoc
     */
    public $idfakultas;

    public function rules()
    {
        return [
            [['id', 'idfakultas', 'idprodi', 'idsubakun', 'jumlah', 'idtahunajaran'], 'integer'],
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
        if(isset($params['Pendapatan']['tanggal_dari']) || isset($params['Pendapatan']['tanggal_sampai'])){

            $vtanggaldari   = explode('-', $params['Pendapatan']['tanggal_dari']);
            $vtanggalsampai = explode('-', $params['Pendapatan']['tanggal_sampai']);

            $tanggaldari    = $vtanggaldari[2]."-".$vtanggaldari[1]."-".$vtanggaldari[0];
            $tanggalsampai  = $vtanggalsampai[2]."-".$vtanggalsampai[1]."-".$vtanggalsampai[0];
        }else{
            $tanggaldari = date('Y-m-d');
            $tanggalsampai = date('Y-m-d');
        }

        $query = Pendapatan::find()
                    ->joinWith('fakultas')
                    ->where(['>=', 'tanggal', $tanggaldari])
                    ->andWhere(['<=', 'tanggal', $tanggalsampai]);

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
            'idsubakun' => $this->idsubakun,
            'jumlah' => $this->jumlah,
            'idtahunajaran' => $tahunajaran,
            'tanggal' => $this->tanggal,
        ]);

        return $dataProvider;
    }

    public function searchProdi($kodeprodi){
        $hasil = 0;
        $data = Prodi::find()->where(['kode'=>$kodeprodi])->one();
        if(empty($data['id'])){
            $hasil = '0';
        }
        else{
            $hasil = $data['id'];
        }
        return $hasil;
    }
}
