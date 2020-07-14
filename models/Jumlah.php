<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "jumlah".
 *
 * @property int $id
 * @property int $idprodi
 * @property int $idtahunajaran
 * @property int $jumlah_mahasiswa
 * @property int $jumlah_dosen
 *
 * @property Prodi $prodi
 * @property TahunAjaran $tahunajaran
 */
class Jumlah extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public $idfakultas;

    public static function tableName()
    {
        return 'jumlah';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['idprodi', 'idtahunajaran', 'jumlah_mahasiswa', 'jumlah_dosen'], 'required'],
            [['idprodi', 'idtahunajaran', 'jumlah_mahasiswa', 'jumlah_dosen'], 'integer'],
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
            'idtahunajaran' => 'Tahun Ajaran',
            'jumlah_mahasiswa' => 'Jumlah Mahasiswa',
            'jumlah_dosen' => 'Jumlah Dosen',
        ];
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
