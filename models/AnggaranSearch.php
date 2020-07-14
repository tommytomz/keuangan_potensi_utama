<?php

namespace app\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\Anggaran;

/**
 * AnggaranSearch represents the model behind the search form about `app\models\Anggaran`.
 */
class AnggaranSearch extends Anggaran
{
    /**
     * @inheritdoc
     */
    public $idfakultas;

    public function rules()
    {
        return [
            [['id', 'idfakultas', 'idprodi', 'jumlah', 'idtahunajaran'], 'integer'],
            [['kegiatan', 'status', 'tanggal'], 'safe'],
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
        $query = Anggaran::find()->joinWith('fakultas');

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        $this->load($params);

        if (!$this->validate()) {
            // uncomment the following line if you do not want to return any records when validation fails
            //$query->where(['idtahunajaran'=>$_SESSION['idtahunajaran']]);
            
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
            'jumlah' => $this->jumlah,
            'idtahunajaran' => $tahunajaran,
            'status' => $this->status,
            'tanggal' => $this->tanggal,
        ]);

        $query->andFilterWhere(['like', 'kegiatan', $this->kegiatan]);

        return $dataProvider;
    }
}
