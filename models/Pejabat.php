<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "pejabat".
 *
 * @property int $id
 * @property string $nama_pejabat
 */
class Pejabat extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'pejabat';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['nama_pejabat'], 'string', 'max' => 100],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'nama_pejabat' => 'Nama Pejabat',
        ];
    }
}
