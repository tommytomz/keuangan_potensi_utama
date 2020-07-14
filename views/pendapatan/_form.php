<?php
use yii\helpers\Html;
use yii\widgets\ActiveForm;
use kartik\select2\Select2;
use yii\helpers\ArrayHelper;
use app\models\JenisPendapatan;
use app\models\TahunAjaran;
use unclead\multipleinput\MultipleInput;
use unclead\multipleinput\TabularInput;
use kartik\number\NumberControl;
use app\models\Fakultas;
use app\models\Prodi;
use app\models\SubAkun;
use kartik\date\DatePicker;

/* @var $this yii\web\View */
/* @var $model app\models\Pendapatan */
/* @var $form yii\widgets\ActiveForm */

//$datajenispendapatan= ArrayHelper::map(JenisPendapatan::find()->all(),'id','jenis_pendapatan');
$datasubakun= ArrayHelper::map(SubAkun::find()->where(['idakun'=>'4'])->all(),'id','nama_sub_akun');
$dataharta= ArrayHelper::map(SubAkun::find()->where(['idakun'=>'1'])->all(),'id','nama_sub_akun');
$datatahunajaran    = ArrayHelper::map(TahunAjaran::find()->all(),'id','tahun_ajaran');
$datafakultas       = ArrayHelper::map(Fakultas::find()->all(),'id','nama_fakultas');
$dataprodi          = ArrayHelper::map(Prodi::find()->all(),'id','nama_prodi');
?>

<div class="pendapatan-form">
<?php $form = ActiveForm::begin(); ?>
    
    <?= $form->field($model, 'idfakultas')->widget(Select2::classname(), [
        'data' => $datafakultas,
        'options' => ['placeholder' => 'Pilih', 'onchange' => '
            $.post( "'.Yii::$app->urlManager->createUrl('anggaran/pilihprodi').'&idfakultas="+$(this).val(), function( data ) {
                $("#pendapatan-idprodi").html( data );
                
            });
            
        '],
        'pluginOptions' => [
            'allowClear' => false,
        ],
    ]); ?>

    <?= $form->field($model, 'idprodi')->widget(Select2::classname(), [
        'data' => $dataprodi,
        'options' => ['placeholder' => 'Pilih'],
        'pluginOptions' => [
            'allowClear' => false,
        ],
    ]); ?>

    <?= $form->field($model, 'debet')->widget(Select2::classname(), [
        'data' => $dataharta,
        'options' => ['placeholder' => 'Pilih'],
        'pluginOptions' => [
            'allowClear' => false,
        ],
    ]); ?>

    <label class="control-label">Tanggal</label>
    <?=DatePicker::widget([
        'model' => $model,
        // 'name' => 'tanggal',
        // 'id' => 'tanggal',
        'attribute' => 'tanggal',
        'value' => date('Y-m-d'),
        //'options' => ['placeholder' => 'Pilih Tanggal'],
        'pluginOptions' => [
            'format' => 'yyyy-mm-dd',
            'autoclose' => true,
            'todayHighlight' => true
            ]
        ])?>

    <label class="control-label"></label>
    <?= $form->field($model, 'schedule')->widget(MultipleInput::className(), [
    //'max' => 4,
    'id' => 'schedule',

    'columns' => [
            [
                'name'  => 'idsubakun',
                'type'  => Select2::classname(),
                'title' => 'Akun',
                'enableError' => true,
                'options' => [
                    'data' => $datasubakun,
                    'options' => ['placeholder' => 'Pilih'],
                ],
            ],
            [
                'name'  => 'jumlah',
                'type' => NumberControl::classname(),
                'title' => 'Jumlah',
                'enableError' => true,
            ],
            [
                'name'  => 'keterangan',
                'title' => 'Keterangan',
                'enableError' => true,
            ],
        ]
     ])->label(false);
    ?>
    <?php if (!Yii::$app->request->isAjax){ ?>
        <div class="form-group">
            <?= Html::submitButton($model->isNewRecord ? 'Tambah' : 'Ubah', ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
        </div>
    <?php } ?>

    <?php ActiveForm::end(); ?>
</div>
