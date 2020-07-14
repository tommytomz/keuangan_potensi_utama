<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "fakultas".
 *
 * @property int $id
 * @property string $nama_fakultas
 *
 * @property Prodi[] $prodis
 */
class Fakultas extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'fakultas';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['nama_fakultas'], 'required'],
            [['nama_fakultas'], 'string', 'max' => 100],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'nama_fakultas' => 'Nama Fakultas',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getProdi()
    {
        return $this->hasOne(Prodi::className(), ['idfakultas' => 'id']);
    }

    public function getPendapatan()
    {
        return $this->hasOne(Pendapatan::className(), ['idprodi' => 'id'])->via('prodi');
    }

    public function getPengeluaran()
    {
        return $this->hasOne(Pengeluaran::className(), ['idprodi' => 'id'])->via('prodi');
    }
}
