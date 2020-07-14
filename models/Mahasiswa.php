<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "mahasiswa".
 *
 * @property int $id
 * @property string $nim
 * @property string $nama
 * @property int $idprodi
 * @property int $angkatan
 * @property int $semester
 * @property int $tagihan
 * @property int $biaya_pendidikan
 * @property int $total_bayar
 * @property string $tanggal_bayar
 * @property string $keterangan
 */
class Mahasiswa extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'mahasiswa';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['idprodi', 'angkatan', 'semester', 'tagihan', 'biaya_pendidikan', 'total_bayar'], 'integer'],
            [['keterangan'], 'string'],
            [['nim'], 'string', 'max' => 20],
            [['nama', 'tanggal_bayar'], 'string', 'max' => 100],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'nim' => 'Nim',
            'nama' => 'Nama',
            'idprodi' => 'Idprodi',
            'angkatan' => 'Angkatan',
            'semester' => 'Semester',
            'tagihan' => 'Tagihan',
            'biaya_pendidikan' => 'Biaya Pendidikan',
            'total_bayar' => 'Total Bayar',
            'tanggal_bayar' => 'Tanggal Bayar',
            'keterangan' => 'Keterangan',
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

    public function getProdi()
    {
        return $this->hasOne(Prodi::className(), ['id' => 'idprodi']);
    }


}
