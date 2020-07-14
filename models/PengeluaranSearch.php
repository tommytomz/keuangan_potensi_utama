<?php

namespace app\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\Pengeluaran;
use app\models\Fakultas;
use app\models\Prodi;
use app\models\SubAkun;

/**
 * PengeluaranSearch represents the model behind the search form about `app\models\Pengeluaran`.
 */
class PengeluaranSearch extends Pengeluaran
{
    /**
     * @inheritdoc
     */

    public $idfakultas;

    public function rules()
    {
        return [
            [['id', 'idprodi', 'idfakultas', 'jumlah', 'idtahunajaran', 'idsubakun'], 'integer'],
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
        if(isset($params['Pengeluaran']['tanggal_dari']) || isset($params['Pengeluaran']['tanggal_sampai'])){

            $vtanggaldari   = explode('-', $params['Pengeluaran']['tanggal_dari']);
            $vtanggalsampai = explode('-', $params['Pengeluaran']['tanggal_sampai']);

            $tanggaldari    = $vtanggaldari[2]."-".$vtanggaldari[1]."-".$vtanggaldari[0];
            $tanggalsampai  = $vtanggalsampai[2]."-".$vtanggalsampai[1]."-".$vtanggalsampai[0];
        }else{
            $tanggaldari = date('Y-m-d');
            $tanggalsampai = date('Y-m-d');
        }

        $query = Pengeluaran::find()
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
            'idsubakun'=>$this->idsubakun,
            'jumlah' => $this->jumlah,
            'idtahunajaran' => $tahunajaran,
            'tanggal' => $this->tanggal,
        ]);

        //$query->andFilterWhere(['like', 'kegiatan', $this->kegiatan]);

        return $dataProvider;
    }

    public function searchLaporan($params)
    {
        $querypengeluaran = Pengeluaran::find()
            ->select([
                'idprodi, sum(jumlah) as jumlah'
            ])
            ->groupBy(['idprodi']);

        $query = Prodi::find()
                            ->select(['
                                        
                                        fakultas.nama_fakultas, 
                                        nama_prodi, 
                                        sum(pendapatan.jumlah) as pendapatan,
                                        COALESCE(pengeluaran.jumlah, 0) as pengeluaran,
                                        pendapatan.jumlah - COALESCE(pengeluaran.jumlah, 0) as jumlah'])
                            ->joinWith('fakultas')
                            ->joinWith('pendapatan')
                            ->leftJoin(['pengeluaran'=>$querypengeluaran],'prodi.id = pengeluaran.idprodi');

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'pagination' => false,
        ]);

        //print_r($dataProvider->getModels());

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
            //'id' => $this->id,
            'fakultas.id' => $this->idfakultas,
            'prodi.id'=>$prodi,
            'pendapatan.idtahunajaran' => $tahunajaran,
        ]);

        //echo $query->sum('pendapatan.jumlah');
        //$query->distinct();
        $query->groupBy(['fakultas.nama_fakultas', 'nama_prodi']);

        return $dataProvider;
    }

    public function searchSubAkun($namasubakun){
        $hasil = 0;
        $data = SubAkun::find()->where(['nama_sub_akun'=>$namasubakun])->one();
        //print_r($data);
        if(empty($data['id'])){
            $hasil = '0';
        }
        else{
            $hasil = $data['id'];
        }
        return $hasil;
    }
}
