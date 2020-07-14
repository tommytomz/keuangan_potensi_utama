<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "transaksi".
 *
 * @property int $id
 * @property int $idsubakun
 * @property int $idakundebet
 * @property int $idakunkredit
 * @property string $no_ref
 * @property int $debet
 * @property int $kredit
 * @property string $keterangan
 * @property string $tanggal
 *
 * @property SubAkun $akundebet
 * @property SubAkun $akunkredit
 * @property SubAkun $subakun
 */
class Transaksi extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public $idakun;
    public $nominal;

    public $idakun2;
    public $idsubakun2;

    public $debetkredit;

    public static function tableName()
    {
        return 'transaksi';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['idsubakun'], 'required'],
            [['idsubakun', 'idakundebet', 'idakunkredit', 'ke_akun'], 'integer'],
            [['debet', 'kredit', 'nominal'], 'double'],
            [['keterangan'], 'string'],
            [['tanggal, no_ref'], 'safe'],
            [['no_ref'], 'string', 'max' => 20],
            [['idakundebet'], 'exist', 'skipOnError' => true, 'targetClass' => SubAkun::className(), 'targetAttribute' => ['idakundebet' => 'id']],
            [['idakunkredit'], 'exist', 'skipOnError' => true, 'targetClass' => SubAkun::className(), 'targetAttribute' => ['idakunkredit' => 'id']],
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
            'idakun' => 'Akun',
            'idsubakun' => 'Sub Akun',
            'idakun2' => 'Akun',
            'idsubakun2' => 'Sub Akun',
            'idakundebet' => 'Idakundebet',
            'idakunkredit' => 'Idakunkredit',
            'debetkredit' => 'Jenis Transaksi',
            'no_ref' => 'No Ref',
            'nominal' => 'Nominal',
            'debet' => 'Debet',
            'kredit' => 'Kredit',
            'keterangan' => 'Keterangan',
            'tanggal' => 'Tanggal',
        ];
    }

    public static function getTotal($provider, $columnName)
    {
        $total = 0;
        foreach ($provider as $item) {
          $total += $item[$columnName];
      }
      return $total;  
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getAkundebet()
    {
        return $this->hasOne(SubAkun::className(), ['id' => 'idakundebet']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getAkunkredit()
    {
        return $this->hasOne(SubAkun::className(), ['id' => 'idakunkredit']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getSubakun()
    {
        return $this->hasOne(SubAkun::className(), ['id' => 'idsubakun']);
    }
}
