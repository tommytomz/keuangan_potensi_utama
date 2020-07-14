<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "tahun_ajaran".
 *
 * @property int $id
 * @property string $tahun_ajaran
 *
 * @property Jumlah[] $jumlahs
 */
class TahunAjaran extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'tahun_ajaran';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['tahun_ajaran'], 'string', 'max' => 50],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'tahun_ajaran' => 'Tahun Ajaran',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getJumlahs()
    {
        return $this->hasMany(Jumlah::className(), ['idtahunajaran' => 'id']);
    }
}
