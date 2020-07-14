<?php
use yii\helpers\Html;
use yii\widgets\ActiveForm;
use kartik\select2\Select2;
use yii\helpers\ArrayHelper;
use app\models\Prodi;
use app\models\TahunAjaran;
/* @var $this yii\web\View */
/* @var $model app\models\Jumlah */
/* @var $form yii\widgets\ActiveForm */

$dataprodi       =ArrayHelper::map(Prodi::find()->all(),'id','nama_prodi');
$datatahunajaran =ArrayHelper::map(TahunAjaran::find()->all(),'id','tahun_ajaran');
?>

<div class="jumlah-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'jumlah_mahasiswa')->textInput() ?>

    <?= $form->field($model, 'jumlah_dosen')->textInput() ?>

  
	<?php if (!Yii::$app->request->isAjax){ ?>
	  	<div class="form-group">
	        <?= Html::submitButton($model->isNewRecord ? 'Tambah' : 'Ubah', ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
	    </div>
	<?php } ?>

    <?php ActiveForm::end(); ?>
    
</div>
