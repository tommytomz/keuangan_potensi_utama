<?php
use yii\helpers\Html;
use yii\widgets\ActiveForm;
use kartik\select2\Select2;
use yii\helpers\ArrayHelper;
use app\models\Akun;
use app\models\SubAkun;
use kartik\number\NumberControl;
use kartik\date\DatePicker;

/* @var $this yii\web\View */
/* @var $model app\models\Transaksi */
/* @var $form yii\widgets\ActiveForm */

$dataakun       = ArrayHelper::map(Akun::find()->all(),'id','nama_akun');
$datasubakun    = ArrayHelper::map(SubAkun::find()->all(),'id','nama_sub_akun');

?>

<div class="transaksi-form">

    <?php $form = ActiveForm::begin(); ?>
    <div class="row">
        <div class="col-md-4">
            <?= $form->field($model, 'idakun')->widget(Select2::classname(), [
                'data' => $dataakun,
                'options' => ['placeholder' => 'Pilih', 'onchange' => '
                    $.post( "'.Yii::$app->urlManager->createUrl('transaksi/pilihsubakun').'&idakun="+$(this).val(), function( data ) {
                        $("#transaksi-idsubakun").html( data );
                        
                    });
                    
                '],
                'pluginOptions' => [
                    'allowClear' => false,
                ],
            ]); ?>
        </div>
        <div class="col-md-8">
            <?= $form->field($model, 'idsubakun')->widget(Select2::classname(), [
                'data' => $datasubakun,
                'options' => ['placeholder' => 'Pilih'],
                'pluginOptions' => [
                    'allowClear' => false,
                ],
            ]); ?>
            </div>
    </div>

    <?= $form->field($model, 'debetkredit')->widget(Select2::classname(), [
        'data' => ['debet'=>'Debet', 'kredit'=>'Kredit'],
        //'options' => ['placeholder' => 'Pilih'],
        'pluginOptions' => [
            'allowClear' => false,
        ],
    ]); ?>

    <div class="row">
        <div class="col-md-4">
            <?= $form->field($model, 'idakun2')->widget(Select2::classname(), [
                'data' => $dataakun,
                'options' => ['placeholder' => 'Pilih', 'onchange' => '
                    $.post( "'.Yii::$app->urlManager->createUrl('transaksi/pilihsubakun').'&idakun="+$(this).val(), function( data ) {
                        $("#transaksi-idsubakun2").html( data );
                        
                    });
                    
                '],
                'pluginOptions' => [
                    'allowClear' => false,
                ],
            ]); ?>
        </div>

        <div class="col-md-8">
            <?= $form->field($model, 'idsubakun2')->widget(Select2::classname(), [
                'data' => $datasubakun,
                'options' => ['placeholder' => 'Pilih'],
                'pluginOptions' => [
                    'allowClear' => false,
                ],
            ]); ?>
        </div>
    </div>

    <?= $form->field($model, 'nominal')->widget(NumberControl::classname(), []) ?>

    <?= $form->field($model, 'keterangan')->textarea(['rows' => 6]) ?>

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
