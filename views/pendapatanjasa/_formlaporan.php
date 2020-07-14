<?php
use yii\helpers\Html;
use yii\widgets\ActiveForm;
use kartik\select2\Select2;
use yii\helpers\ArrayHelper;
use app\models\JenisPendapatan;
use app\models\TahunAjaran;
use kartik\number\NumberControl;
use app\models\Fakultas;
use app\models\Prodi;

/* @var $this yii\web\View */
/* @var $model app\models\Pengeluaran */
/* @var $form yii\widgets\ActiveForm */

$datajenispendapatan=ArrayHelper::map(JenisPendapatan::find()->all(),'id','jenis_pendapatan');
$datatahunajaran	=ArrayHelper::map(TahunAjaran::find()->all(),'id','tahun_ajaran');
$datafakultas		= ArrayHelper::map(Fakultas::find()->all(),'id','nama_fakultas');
$dataprodi 			= ArrayHelper::map(Prodi::find()->all(),'id','nama_prodi');
?>

<div class="pendapatan-form">

    <?php $form = ActiveForm::begin(); ?>
    <?php if(Yii::$app->user->identity->idprodi==0){ ?>

        <?= $form->field($model, 'idfakultas')->widget(Select2::classname(), [
            'data' => $datafakultas,
            'options' => ['placeholder' => 'Pilih', 'onchange' => '
            	$.post( "'.Yii::$app->urlManager->createUrl('anggaran/pilihprodi').'&idfakultas="+$(this).val(), function( data ) {
            		$("#pendapatan-idprodi").html( data );
    				
    			});
            	
            '],
            'pluginOptions' => [
                'allowClear' => false,
            ],
        ]); ?>

    	<?= $form->field($model, 'idprodi')->widget(Select2::classname(), [
            'data' => $dataprodi,
            'options' => ['placeholder' => 'Pilih'],
            'pluginOptions' => [
                'allowClear' => false,
            ],
        ]); ?>
        
    <?php } ?>

    <?= $form->field($model, 'idtahunajaran')->widget(Select2::classname(), [
        'data' => $datatahunajaran,
        'options' => ['placeholder' => 'Pilih'],
        'pluginOptions' => [
            'allowClear' => false,
        ],
    ]); ?>

    <?php ActiveForm::end(); ?>
    
</div>
