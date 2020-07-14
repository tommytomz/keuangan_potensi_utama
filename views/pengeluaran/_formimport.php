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

/* @var $this yii\web\View */
/* @var $model app\models\Pengeluaran */
/* @var $form yii\widgets\ActiveForm */

$datasubakun= ArrayHelper::map(SubAkun::find()->where(['idakun'=>'5'])->all(),'id','nama_sub_akun');
$dataharta= ArrayHelper::map(SubAkun::find()->where(['idakun'=>'1'])->all(),'id','nama_sub_akun');
$datatahunajaran    = ArrayHelper::map(TahunAjaran::find()->all(),'id','tahun_ajaran');
$datafakultas       = ArrayHelper::map(Fakultas::find()->all(),'id','nama_fakultas');
$dataprodi          = ArrayHelper::map(Prodi::find()->all(),'id','nama_prodi');
?>

<div class="pengeluaran-form">

    <?php $form = ActiveForm::begin(['options' => ['enctype' => 'multipart/form-data']]); ?>
    
    <?= $form->field($model, 'idfakultas')->widget(Select2::classname(), [
        'data' => $datafakultas,
        'options' => ['placeholder' => 'Pilih', 'onchange' => '
            $.post( "'.Yii::$app->urlManager->createUrl('anggaran/pilihprodi').'&idfakultas="+$(this).val(), function( data ) {
                $("#pengeluaran-idprodi").html( data );
                
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

	<?= $form->field($model, 'kredit')->widget(Select2::classname(), [
        'data' => $dataharta,
        'options' => ['placeholder' => 'Pilih'],
        'pluginOptions' => [
            'allowClear' => false,
        ],
    ]); ?>

    <?= $form->field($model, 'importfile')->fileInput() ?>

	<?php if (!Yii::$app->request->isAjax){ ?>
	  	<div class="form-group">
	        <?= Html::submitButton($model->isNewRecord ? 'Tambah' : 'Ubah', ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
	    </div>
	<?php } ?>

	<!-- <i class="fa fa-file-excel-o" style="color: green; font-size: 15pt;"> <a href="files/Format_Laporan_Pertanggungjawaban.xlsx"> Format Laporan Pertanggungjawaban.xlsx</a></i> -->

    <?php ActiveForm::end(); ?>
    
</div>
