<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "saldo_awal".
 *
 * @property int $id
 * @property int $idsubakun
 * @property double $debet
 * @property double $kredit
 * @property string $tanggal
 *
 * @property SubAkun $subakun
 */
class SaldoAwal extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'saldo_awal';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['idsubakun'], 'required'],
            [['idsubakun'], 'integer'],
            [['debet', 'kredit'], 'number'],
            [['tanggal'], 'safe'],
            [['idsubakun'], 'exist', 'skipOnError' => true, 'targetClass' => SubAkun::className(), 'targetAttribute' => ['idsubakun' => 'id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'idsubakun' => 'Sub Akun',
            'debet' => 'Debet',
            'kredit' => 'Kredit',
            'tanggal' => 'Tanggal',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getSubakun()
    {
        return $this->hasOne(SubAkun::className(), ['id' => 'idsubakun']);
    }
}
