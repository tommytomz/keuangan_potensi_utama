<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "jenis_pendapatan".
 *
 * @property int $id
 * @property string $jenis_pendapatan
 * @property string $kategori
 *
 * @property Pendapatan[] $pendapatans
 */
class JenisPendapatan extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'jenis_pendapatan';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['jenis_pendapatan', 'kategori'], 'required'],
            [['jenis_pendapatan'], 'string', 'max' => 100],
            [['kategori'], 'string', 'max' => 50],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'jenis_pendapatan' => 'Jenis Pendapatan',
            'kategori' => 'Kategori',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getPendapatans()
    {
        return $this->hasMany(Pendapatan::className(), ['idjenispendapatan' => 'id']);
    }
}
