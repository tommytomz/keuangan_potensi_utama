<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "akun".
 *
 * @property int $id
 * @property string $nama_akun
 * @property int $nilai
 *
 * @property SubAkun[] $subAkuns
 */
class Akun extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'akun';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['nama_akun'], 'required'],
            [['nilai'], 'integer'],
            [['nama_akun'], 'string', 'max' => 200],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'nama_akun' => 'Nama Akun',
            'nilai' => 'Nilai',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getSubAkuns()
    {
        return $this->hasMany(SubAkun::className(), ['idakun' => 'id']);
    }
}
