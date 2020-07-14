<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "pendapatan".
 *
 * @property int $id
 * @property int $idsubakun
 * @property int $jumlah
 * @property int $idtahunajaran
 * @property string $tanggal
 *
 * @property SubAkun $subakun
 * @property TahunAjaran $tahunajaran
 */
class Pendapatan extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public $schedule;
    public $importfile;
    public $nilai_akhir_mahasiswa;
    public $pendapatan_mahasiswa;
    public $nilai_akhir_dosen;
    public $pendapatan_dosen;
    public $nilai_akhir_lain;
    public $pendapatan_lain;
    public $idfakultas;
    public $debet;
    public $idharta;

    public static function tableName()
    {
        return 'pendapatan';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [[ 'schedule'] , 'safe' ],
            [['idprodi', 'idsubakun', 'jumlah', 'idtahunajaran'], 'required'],
            [['idprodi', 'idsubakun', 'jumlah', 'idtahunajaran'], 'integer'],
            [['tanggal','no_ref'], 'safe'],
            [['idsubakun'], 'exist', 'skipOnError' => true, 'targetClass' => SubAkun::className(), 'targetAttribute' => ['idsubakun' => 'id']],
            [['idtahunajaran'], 'exist', 'skipOnError' => true, 'targetClass' => TahunAjaran::className(), 'targetAttribute' => ['idtahunajaran' => 'id']],
        ];
    }

    public function afterFind() {
        parent::afterFind();
        $this->schedule = \yii\helpers\Json::decode($this->schedule);
        //echo $this->schedule;
    }

    public static function getTotal($provider, $columnName)
    {
        $total = 0;
        foreach ($provider as $item) {
          $total += $item[$columnName];
      }
      return $total;  
    }

    // public static function getPendapatanMahasiswa()
    // {
    //     $datapendapatanmhs = Pendapatan::find('')
    //                             ->joinWith('jenispendapatan')
    //                             ->where(['jenis_pendapatan.kategori'=>'Mahasiswa'])
    //                             ->all();

    //     $datajumlah = Jumlah::find()->where(['idprodi'=>''])
    //     return $datapendapatanmhs;
    // }

    public static function getPendapatanDosen()
    {
        
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'idfakultas' => 'Fakultas',
            'idprodi' => 'Program Studi',
            'idsubakun' => 'Akun',
            'jumlah' => 'Jumlah',
            'idtahunajaran' => 'Tahun Ajaran',
            'tanggal' => 'Tanggal',
            'importfile' => 'File Excel',
            'debet' => 'Debet'
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getSubakun()
    {
        return $this->hasOne(SubAkun::className(), ['id' => 'idsubakun']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getTahunajaran()
    {
        return $this->hasOne(TahunAjaran::className(), ['id' => 'idtahunajaran']);
    }

    // public function getJumlah()
    // {
    //     return $this->hasOne(Jumlah::className(), ['idprodi' => 'idprodi']);
    // }

    public function getProdi()
    {
        return $this->hasOne(Prodi::className(), ['id' => 'idprodi']);
    }

    public function getJumlah()
    {
        return $this->hasOne(Jumlah::className(), ['idprodi' => 'idprodi']);
    }

    public function getFakultas()
    {
        return $this->hasOne(Fakultas::className(), ['id' => 'idfakultas'])->via('prodi');
    }

}
