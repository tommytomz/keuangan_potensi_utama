<?php
use yii\helpers\Html;
use yii\widgets\ActiveForm;
use kartik\select2\Select2;
use yii\helpers\ArrayHelper;
use app\models\Akun;
/* @var $this yii\web\View */
/* @var $model app\models\KategoriAkun */
/* @var $form yii\widgets\ActiveForm */
$dataakun = ArrayHelper::map(Akun::find()->all(),'id','nama_akun');
?>

<div class="kategori-akun-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'idakun')->widget(Select2::classname(), [
		'data' => $dataakun,
		'options' => ['placeholder' => 'Pilih'],
		'pluginOptions' => [
			'allowClear' => false,
		],
	]); ?>

    <?= $form->field($model, 'nama_kategori')->textInput(['maxlength' => true]) ?>

  
	<?php if (!Yii::$app->request->isAjax){ ?>
	  	<div class="form-group">
	        <?= Html::submitButton($model->isNewRecord ? 'Tambah' : 'Ubah', ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
	    </div>
	<?php } ?>

    <?php ActiveForm::end(); ?>
    
</div>
