<?php
use yii\helpers\Html;
use yii\widgets\ActiveForm;
use kartik\select2\Select2;
use yii\helpers\ArrayHelper;
use app\models\JenisPendapatan;
use app\models\TahunAjaran;
use kartik\number\NumberControl;

/* @var $this yii\web\View */
/* @var $model app\models\Pengeluaran */
/* @var $form yii\widgets\ActiveForm */

$datajenispendapatan=ArrayHelper::map(JenisPendapatan::find()->all(),'id','jenis_pendapatan');
$datatahunajaran=ArrayHelper::map(TahunAjaran::find()->all(),'id','tahun_ajaran');

?>

<div class="pendapatan-form">

    <?php $form = ActiveForm::begin(); ?>

    <!-- <?= $form->field($model, 'idjenispendapatan')->widget(Select2::classname(), [
        'data' => $datajenispendapatan,
        'options' => ['placeholder' => 'Pilih'],
        'pluginOptions' => [
            'allowClear' => false,
        ],
    ]); ?> -->

    <?= $form->field($model, 'jumlah')->widget(NumberControl::classname(), []) ?>

	<?php if (!Yii::$app->request->isAjax){ ?>
	  	<div class="form-group">
	        <?= Html::submitButton($model->isNewRecord ? 'Tambah' : 'Ubah', ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
	    </div>
	<?php } ?>

    <?php ActiveForm::end(); ?>
    
</div>
