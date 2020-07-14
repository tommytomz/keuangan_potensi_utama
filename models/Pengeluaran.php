<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "pengeluaran".
 *
 * @property int $id
 * @property int $idjenispengeluaran
 * @property int $jumlah
 * @property int $idtahunajaran
 * @property string $tanggal
 *
 * @property JenisPengeluaran $jenispengeluaran
 * @property TahunAjaran $tahunajaran
 */
class Pengeluaran extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */

    public $schedule;
    public $importfile;
    public $idfakultas;
    public $kredit;
    public $idharta;

    public static function tableName()
    {
        return 'pengeluaran';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['schedule'] , 'safe' ],
            [['jumlah', 'idtahunajaran'], 'required'],
            [['idprodi', 'jumlah', 'idtahunajaran', 'idsubakun'], 'integer'],
            [['tanggal', 'no_ref'], 'safe'],
            [['idtahunajaran'], 'exist', 'skipOnError' => true, 'targetClass' => TahunAjaran::className(), 'targetAttribute' => ['idtahunajaran' => 'id']],
        ];
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
            'kredit' => 'Kredit'
        ];
    }

    public static function getTotal($provider, $columnName)
    {
        $total = 0;
        foreach ($provider as $item) {
          $total += $item[$columnName];
      }
      return $total;  
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getTahunajaran()
    {
        return $this->hasOne(TahunAjaran::className(), ['id' => 'idtahunajaran']);
    }

    public function getProdi()
    {
        return $this->hasOne(Prodi::className(), ['id' => 'idprodi']);
    }

    public function getFakultas()
    {
        return $this->hasOne(Fakultas::className(), ['id' => 'idfakultas'])->via('prodi');
    }

    public function getPendapatan()
    {
        return $this->hasOne(Pendapatan::className(), ['idprodi' => 'id'])->via('prodi');
    }

    public function getSubakun()
    {
        return $this->hasOne(SubAkun::className(), ['id' => 'idsubakun']);
    }
}
