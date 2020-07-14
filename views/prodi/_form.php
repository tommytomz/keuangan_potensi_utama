<?php
use yii\helpers\Html;
use yii\widgets\ActiveForm;
use kartik\select2\Select2;
use yii\helpers\ArrayHelper;
use app\models\Fakultas;
/* @var $this yii\web\View */
/* @var $model app\models\Prodi */
/* @var $form yii\widgets\ActiveForm */

$fakultas=Fakultas::find()->all();
$datafakultas=ArrayHelper::map($fakultas,'id','nama_fakultas');
?>

<div class="prodi-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'idfakultas')->widget(Select2::classname(), [
		'data' => $datafakultas,
		'options' => ['placeholder' => 'Pilih'],
		'pluginOptions' => [
			'allowClear' => false,
		],
	]); ?>

    <?= $form->field($model, 'nama_prodi')->textInput(['maxlength' => true]) ?>

  
	<?php if (!Yii::$app->request->isAjax){ ?>
	  	<div class="form-group">
	        <?= Html::submitButton($model->isNewRecord ? 'Tambah' : 'Ubah', ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
	    </div>
	<?php } ?>

    <?php ActiveForm::end(); ?>
    
</div>
