<?php
use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\models\Pengeluaran */
/* @var $form yii\widgets\ActiveForm */

?>

<div class="anggaran-form">

    <?php $form = ActiveForm::begin(['options' => ['enctype' => 'multipart/form-data']]); ?>

    <?= $form->field($model, 'importfile')->fileInput() ?>

	<?php if (!Yii::$app->request->isAjax){ ?>
	  	<div class="form-group">
	        <?= Html::submitButton($model->isNewRecord ? 'Tambah' : 'Ubah', ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
	    </div>
	<?php } ?>

	<i class="fa fa-file-excel-o" style="color: green; font-size: 15pt;"> <a href="files/Format_Laporan_Anggaran.xlsx"> Format Laporan Anggaran.xlsx</a></i>

    <?php ActiveForm::end(); ?>
    
</div>
