<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "prodi".
 *
 * @property int $id
 * @property int $idfakultas
 * @property string $nama_prodi
 *
 * @property Jumlah[] $jumlahs
 * @property Fakultas $fakultas
 */
class Prodi extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public $jumlah;
    public $nama_fakultas;
    public $pendapatan;
    public $pengeluaran;

    public static function tableName()
    {
        return 'prodi';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['idfakultas'], 'integer'],
            [['nama_prodi'], 'string', 'max' => 100],
            [['idfakultas'], 'exist', 'skipOnError' => true, 'targetClass' => Fakultas::className(), 'targetAttribute' => ['idfakultas' => 'id']],
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
            'nama_prodi' => 'Nama Prodi',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getJumlahs()
    {
        return $this->hasMany(Jumlah::className(), ['idprodi' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getFakultas()
    {
        return $this->hasMany(Fakultas::className(), ['id' => 'idfakultas']);
    }

    public function getPendapatan()
    {
        return $this->hasMany(Pendapatan::className(), ['idprodi' => 'id']);
    }

    public function getPengeluaran()
    {
        return $this->hasMany(Pengeluaran::className(), ['idprodi' => 'id']);
    }
}
