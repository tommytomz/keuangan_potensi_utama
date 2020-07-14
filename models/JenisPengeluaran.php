<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "jenis_pengeluaran".
 *
 * @property int $id
 * @property string $jenis_pengeluaran
 *
 * @property Pengeluaran[] $pengeluarans
 */
class JenisPengeluaran extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'jenis_pengeluaran';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['jenis_pengeluaran'], 'required'],
            [['jenis_pengeluaran'], 'string', 'max' => 100],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'jenis_pengeluaran' => 'Jenis Pengeluaran',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getPengeluarans()
    {
        return $this->hasMany(Pengeluaran::className(), ['idjenispengeluaran' => 'id']);
    }
}
