<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "set_tahun_ajaran".
 *
 * @property int $id
 * @property int $idtahunajaran
 *
 * @property TahunAjaran $tahunajaran
 */
class SetTahunAjaran extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'set_tahun_ajaran';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['idtahunajaran'], 'integer'],
            [['idtahunajaran'], 'exist', 'skipOnError' => true, 'targetClass' => TahunAjaran::className(), 'targetAttribute' => ['idtahunajaran' => 'id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'idtahunajaran' => 'Tahun Ajaran',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getTahunajaran()
    {
        return $this->hasOne(TahunAjaran::className(), ['id' => 'idtahunajaran']);
    }
}
