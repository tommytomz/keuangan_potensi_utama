<?php
use yii\helpers\Html;
use yii\widgets\ActiveForm;
use kartik\select2\Select2;

/* @var $this yii\web\View */
/* @var $model app\models\JenisPendapatan */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="jenis-pendapatan-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'jenis_pendapatan')->textInput(['maxlength' => true]) ?>

  	<?= $form->field($model, 'kategori')->widget(Select2::classname(), [
        'data' => ['Mahasiswa'=>'Mahasiswa', 'Dosen'=>'Dosen', 'Lain-lain'=>'Lain-lain'],
        'options' => ['placeholder' => 'Pilih'],
        'pluginOptions' => [
            'allowClear' => false,
        ],
    ]); ?>

	<?php if (!Yii::$app->request->isAjax){ ?>
	  	<div class="form-group">
	        <?= Html::submitButton($model->isNewRecord ? 'Tambah' : 'Ubah', ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
	    </div>
	<?php } ?>

    <?php ActiveForm::end(); ?>
    
</div>
