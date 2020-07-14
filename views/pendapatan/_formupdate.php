<?php
use yii\helpers\Html;
use yii\widgets\ActiveForm;
use kartik\select2\Select2;
use yii\helpers\ArrayHelper;
use app\models\JenisPendapatan;
use app\models\TahunAjaran;
use kartik\number\NumberControl;
use app\models\SubAkun;
use app\models\Fakultas;
use app\models\Prodi;
use kartik\date\DatePicker;

/* @var $this yii\web\View */
/* @var $model app\models\Pengeluaran */
/* @var $form yii\widgets\ActiveForm */

//$datajenispendapatan=ArrayHelper::map(JenisPendapatan::find()->all(),'id','jenis_pendapatan');
$datasubakun        = ArrayHelper::map(SubAkun::find()->where(['idakun'=>'4'])->all(),'id','nama_sub_akun');
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

    <?= $form->field($model, 'idsubakun')->widget(Select2::classname(), [
        'data' => $datasubakun,
        'options' => ['placeholder' => 'Pilih'],
        'pluginOptions' => [
            'allowClear' => false,
        ],
    ]); ?>

    <?= $form->field($model, 'jumlah')->widget(NumberControl::classname(), []) ?>

    <label class="control-label">Tanggal</label>
    <?=DatePicker::widget([
        'model' => $model,
        'attribute' => 'tanggal',
        'value' => date('d-m-Y'),
        'options' => ['placeholder' => 'Pilih Tanggal'],
        'pluginOptions' => [
            'format' => 'yyyy-mm-dd',
            'autoclose' => true,
            'todayHighlight' => true
            ]
        ])?>

	<?php if (!Yii::$app->request->isAjax){ ?>
	  	<div class="form-group">
	        <?= Html::submitButton($model->isNewRecord ? 'Tambah' : 'Ubah', ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
	    </div>
	<?php } ?>

    <?php ActiveForm::end(); ?>
    
</div>
