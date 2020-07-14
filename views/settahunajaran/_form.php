<?php
use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\helpers\ArrayHelper;
use app\models\TahunAjaran;
use kartik\select2\Select2;
/* @var $this yii\web\View */
/* @var $model app\models\SetTahunAjaran */
/* @var $form yii\widgets\ActiveForm */

$datatahunajaran=ArrayHelper::map(TahunAjaran::find()->all(),'id','tahun_ajaran');
?>

<div class="set-tahun-ajaran-form">

    <?php $form = ActiveForm::begin(); ?>

	<?= $form->field($model, 'idtahunajaran')->widget(Select2::classname(), [
	        'data' => $datatahunajaran,
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
