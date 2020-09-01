<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "sub_akun".
 *
 * @property int $id
 * @property int $idakun
 * @property int $idkategoriakun
 * @property int $kode_akun
 * @property string $nama_sub_akun
 * @property double $debet
 * @property double $kredit
 *
 * @property Pendapatan[] $pendapatans
 * @property Pengeluaran[] $pengeluarans
 * @property SaldoAwal[] $saldoAwals
 * @property Akun $akun
 * @property KategoriAkun $kategoriakun
 * @property Transaksi[] $transaksis
 * @property Transaksi[] $transaksis0
 * @property Transaksi[] $transaksis1
 */
class SubAkun extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'sub_akun';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['idakun', 'kode_akun', 'nama_sub_akun'], 'required'],
            [['idakun', 'idkategoriakun', 'kode_akun'], 'integer'],
            [['debet', 'kredit'], 'number'],
            [['nama_sub_akun'], 'string', 'max' => 200],
            [['idakun'], 'exist', 'skipOnError' => true, 'targetClass' => Akun::className(), 'targetAttribute' => ['idakun' => 'id']],
            [['idkategoriakun'], 'exist', 'skipOnError' => true, 'targetClass' => KategoriAkun::className(), 'targetAttribute' => ['idkategoriakun' => 'id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'idakun' => 'Nama Akun',
            'idkategoriakun' => 'Kategori Akun',
            'kode_akun' => 'Kode Akun',
            'nama_sub_akun' => 'Nama Sub Akun',
            'debet' => 'Debet',
            'kredit' => 'Kredit',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getPendapatans()
    {
        return $this->hasMany(Pendapatan::className(), ['idsubakun' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getPengeluarans()
    {
        return $this->hasMany(Pengeluaran::className(), ['idsubakun' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getSaldoAwals()
    {
        return $this->hasMany(SaldoAwal::className(), ['idsubakun' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getAkun()
    {
        return $this->hasOne(Akun::className(), ['id' => 'idakun']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getKategoriakun()
    {
        return $this->hasOne(KategoriAkun::className(), ['id' => 'idkategoriakun']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getTransaksis()
    {
        return $this->hasMany(Transaksi::className(), ['idakundebet' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getTransaksis0()
    {
        return $this->hasMany(Transaksi::className(), ['idakunkredit' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getTransaksis1()
    {
        return $this->hasMany(Transaksi::className(), ['idsubakun' => 'id']);
    }
}
