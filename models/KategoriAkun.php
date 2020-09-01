<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "kategori_akun".
 *
 * @property int $id
 * @property int $idakun
 * @property string $nama_kategori
 *
 * @property Akun $akun
 */
class KategoriAkun extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'kategori_akun';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['idakun', 'nama_kategori'], 'required'],
            [['idakun'], 'integer'],
            [['nama_kategori'], 'string', 'max' => 100],
            [['idakun'], 'exist', 'skipOnError' => true, 'targetClass' => Akun::className(), 'targetAttribute' => ['idakun' => 'id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'idakun' => 'Akun',
            'nama_kategori' => 'Nama Kategori',
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
