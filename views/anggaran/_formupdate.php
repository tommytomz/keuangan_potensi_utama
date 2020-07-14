<?php
use yii\helpers\Html;
use yii\widgets\ActiveForm;
use kartik\select2\Select2;
use yii\helpers\ArrayHelper;
use kartik\number\NumberControl;
use kartik\detail\DetailView;

/* @var $this yii\web\View */
/* @var $model app\models\Pengeluaran */
/* @var $form yii\widgets\ActiveForm */

?>

<div class="anggaran-form">
<?php $form = ActiveForm::begin(); ?>

<?php 
if(Yii::$app->user->identity->idprodi==0){
?>


    <?= DetailView::widget([
        'model' => $model,
        'mode'=>DetailView::MODE_EDIT,
        'attributes' => [
            [
                'attribute'=>'idprodi',
                'value'=>$model->fakultas->nama_fakultas,
                'displayOnly'=>true,
                'valueColOptions'=>['style'=>'width:50%'], 
            ],
            [
                'attribute'=>'idprodi',
                'value'=>$model->prodi->nama_prodi,
                'displayOnly'=>true,
            ],
            //'fakultas.nama_fakultas',
            //'prodi.nama_prodi',
            [
                'attribute'=>'kegiatan',
                'displayOnly'=>true,
            ],
            [
                'attribute'=>'jumlah',
                'format' => ['decimal', 0],
                'displayOnly'=>true,
            ],
            [
                'attribute'=>'idtahunajaran',
                'value'=>$model->tahunajaran->tahun_ajaran,
                'displayOnly'=>true,
            ],
            //'tahunajaran.tahun_ajaran',
            [
                'attribute'=>'tanggal',
                'displayOnly'=>true,
            ],
            [
                'attribute'=>'status',
                'type'=>DetailView::INPUT_SELECT2,
                'value'=>$model->status,
                'widgetOptions'=>[
                    'data'=>['Menunggu'=>'Menunggu', 'Tidak Disetujui'=>'Tidak Disetujui', 'Disetujui'=>'Disetujui'],
                    'initValueText' => $model->status,
                    'options' => ['placeholder' => $model->status],
                    'pluginOptions' => ['allowClear'=>true, 'width'=>'50%'],
                ],
            ]
        ],
    ]) ?>

<?php }else { ?>


    

    <?= $form->field($model, 'kegiatan')->textInput() ?>

    <?= $form->field($model, 'jumlah')->widget(NumberControl::classname(), []) ?>

	<?php if (!Yii::$app->request->isAjax){ ?>
	  	<div class="form-group">
	        <?= Html::submitButton($model->isNewRecord ? 'Tambah' : 'Ubah', ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
	    </div>
	<?php } ?>

<?php } ?>

<?php ActiveForm::end(); ?>
</div>
