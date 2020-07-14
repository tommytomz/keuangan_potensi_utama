<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "anggaran".
 *
 * @property int $id
 * @property int $idprodi
 * @property string $kegiatan
 * @property int $jumlah
 * @property int $idtahunajaran
 * @property string $tanggal
 *
 * @property Prodi $prodi
 * @property TahunAjaran $tahunajaran
 */
class Anggaran extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public $schedule;
    public $importfile;
    public $idfakultas;

    public static function tableName()
    {
        return 'anggaran';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [[ 'schedule'] , 'safe' ],
            [['idprodi', 'kegiatan', 'jumlah', 'idtahunajaran'], 'required'],
            [['idprodi', 'jumlah', 'idtahunajaran'], 'integer'],
            [['tanggal', 'status'], 'safe'],
            [['kegiatan'], 'string', 'max' => 200],
            [['idprodi'], 'exist', 'skipOnError' => true, 'targetClass' => Prodi::className(), 'targetAttribute' => ['idprodi' => 'id']],
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
            'kegiatan' => 'Kegiatan',
            'jumlah' => 'Jumlah',
            'idtahunajaran' => 'Tahun Ajaran',
            'tanggal' => 'Tanggal',
            'importfile' => 'File Excel'
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
    public function getProdi()
    {
        return $this->hasOne(Prodi::className(), ['id' => 'idprodi']);
    }

    public function getFakultas()
    {
        return $this->hasOne(Fakultas::className(), ['id' => 'idfakultas'])->via('prodi');
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getTahunajaran()
    {
        return $this->hasOne(TahunAjaran::className(), ['id' => 'idtahunajaran']);
    }
}
