<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "sub_akun".
 *
 * @property int $id
 * @property int $idakun
 * @property int $kode_akun
 * @property string $nama_sub_akun
 * @property string $debet
 * @property string $kredit
 *
 * @property Akun $akun
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
            [['idakun', 'nama_sub_akun'], 'required'],
            [['idakun', 'kode_akun'], 'integer'],
            [['nama_sub_akun'], 'string', 'max' => 200],
            [['idakun'], 'exist', 'skipOnError' => true, 'targetClass' => Akun::className(), 'targetAttribute' => ['idakun' => 'id']],
            [['debet', 'kredit'], 'double'],
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
            'kode_akun' => 'Kode Akun',
            'nama_sub_akun' => 'Nama Sub Akun',
            'debet' => 'Debet',
            'kredit' => 'Kredit',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getAkun()
    {
        return $this->hasOne(Akun::className(), ['id' => 'idakun']);
    }
}
