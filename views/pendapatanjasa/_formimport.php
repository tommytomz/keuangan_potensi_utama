<?php
use yii\helpers\Html;
use yii\widgets\ActiveForm;
use kartik\select2\Select2;
use yii\helpers\ArrayHelper;
use app\models\JenisPendapatan;

/* @var $this yii\web\View */
/* @var $model app\models\Pengeluaran */
/* @var $form yii\widgets\ActiveForm */

$datajenispendapatan= ArrayHelper::map(JenisPendapatan::find()->all(),'id','jenis_pendapatan');
?>

<div class="pendapatan-form">

    <?php $form = ActiveForm::begin(['options' => ['enctype' => 'multipart/form-data']]); ?>

    <?= $form->field($model, 'idjenispendapatan')->widget(Select2::classname(), [
		'data' => $datajenispendapatan,
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

	<i class="fa fa-file-excel-o" style="color: green; font-size: 15pt;"> <a href="files/Format_Laporan_Pendapatan.xlsx"> Format Laporan Pendapatan.xlsx</a></i>

    <?php ActiveForm::end(); ?>
    
</div>
