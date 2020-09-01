<?php
use yii\helpers\Html;
use yii\widgets\ActiveForm;
use kartik\select2\Select2;
use yii\helpers\ArrayHelper;
use app\models\Akun;
use app\models\KategoriAkun;
use kartik\number\NumberControl;

/* @var $this yii\web\View */
/* @var $model app\models\SubAkun */
/* @var $form yii\widgets\ActiveForm */

$dataakun = ArrayHelper::map(Akun::find()->all(),'id','nama_akun');
$datakategoriakun = ArrayHelper::map(KategoriAkun::find()->all(),'id','nama_kategori');
?>

<div class="sub-akun-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'idakun')->widget(Select2::classname(), [
		'data' => $dataakun,
		'options' => ['placeholder' => 'Pilih', 'onchange' => '
            $.post( "'.Yii::$app->urlManager->createUrl('subakun/pilihkategoriakun').'&idakun="+$(this).val(), function( data ) {
                $("#subakun-idkategoriakun").html( data );
                
            });
            
        '],
		'pluginOptions' => [
			'allowClear' => false,
		],
	]); ?>

	<?= $form->field($model, 'idkategoriakun')->widget(Select2::classname(), [
		'data' => $datakategoriakun,
		'options' => ['placeholder' => 'Pilih'],
		'pluginOptions' => [
			'allowClear' => false,
		],
	]); ?>

    <?= $form->field($model, 'nama_sub_akun')->textInput(['maxlength' => true]) ?>

    <!-- <?= $form->field($model, 'debet')->widget(NumberControl::classname(), []) ?>

    <?= $form->field($model, 'kredit')->widget(NumberControl::classname(), []) ?> -->

	<?php if (!Yii::$app->request->isAjax){ ?>
	  	<div class="form-group">
	        <?= Html::submitButton($model->isNewRecord ? 'Tambah' : 'Ubah', ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
	    </div>
	<?php } ?>

    <?php ActiveForm::end(); ?>
    
</div>
