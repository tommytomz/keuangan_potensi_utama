<?php
use yii\helpers\Html;
use yii\widgets\ActiveForm;
use kartik\select2\Select2;
use yii\helpers\ArrayHelper;
use app\models\SubAkun;
use kartik\number\NumberControl;
use kartik\date\DatePicker;

/* @var $this yii\web\View */
/* @var $model app\models\SaldoAwal */
/* @var $form yii\widgets\ActiveForm */

$datasubakun = ArrayHelper::map(SubAkun::find()->all(),'id','nama_sub_akun');
?>

<div class="saldo-awal-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'idsubakun')->widget(Select2::classname(), [
        'data' => $datasubakun,
        'options' => ['placeholder' => 'Pilih'],
        'pluginOptions' => [
            'allowClear' => false,
        ],
    ]); ?>

    <?= $form->field($model, 'debet')->widget(NumberControl::classname(), []) ?>

    <?= $form->field($model, 'kredit')->widget(NumberControl::classname(), []) ?>

    <?=DatePicker::widget([
        'model' => $model,
        'attribute' => 'tanggal',
        'value' => date('d-m-Y'),
        'options' => ['placeholder' => 'Pilih Tanggal'],
        'pluginOptions' => [
            'format' => 'yyyy-mm-dd',
            'autoclose' => true,
            'todayHighlight' => true
        ]
    ])?>
  
	<?php if (!Yii::$app->request->isAjax){ ?>
	  	<div class="form-group">
	        <?= Html::submitButton($model->isNewRecord ? 'Tambah' : 'Ubah', ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
	    </div>
	<?php } ?>

    <?php ActiveForm::end(); ?>
    
</div>
