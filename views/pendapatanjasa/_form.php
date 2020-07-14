<?php
use yii\helpers\Html;
use yii\widgets\ActiveForm;
use kartik\select2\Select2;
use yii\helpers\ArrayHelper;
use app\models\JenisPendapatan;
use app\models\TahunAjaran;
use unclead\multipleinput\MultipleInput;
use unclead\multipleinput\MultipleInputColumn;
use unclead\multipleinput\TabularInput;
use kartik\number\NumberControl;
use app\models\Fakultas;
use app\models\Prodi;

/* @var $this yii\web\View */
/* @var $model app\models\Pendapatan */
/* @var $form yii\widgets\ActiveForm */
$datajenispendapatan= ArrayHelper::map(JenisPendapatan::find()->all(),'id','jenis_pendapatan');
$datatahunajaran    = ArrayHelper::map(TahunAjaran::find()->all(),'id','tahun_ajaran');
$datafakultas       = ArrayHelper::map(Fakultas::find()->all(),'id','nama_fakultas');
$dataprodi          = ArrayHelper::map(Prodi::find()->all(),'id','nama_prodi');
?>

<div class="pendapatan-form">
<?php $form = ActiveForm::begin(); ?>

     <!-- <?= $form->field($model, 'idfakultas')->widget(Select2::classname(), [
        'data' => $datafakultas,
        'options' => ['placeholder' => 'Pilih', 'onchange' => '
            $.post( "'.Yii::$app->urlManager->createUrl('anggaran/pilihprodi').'&idfakultas="+$(this).val(), function( data ) {
                $("#pendapatan-idprodi").html( data );
                
            });
            
        '],
        'pluginOptions' => [
            'allowClear' => false,
        ],
    ]); ?> -->

    <!-- <?= $form->field($model, 'idprodi')->widget(Select2::classname(), [
        'data' => $dataprodi,
        'options' => ['placeholder' => 'Pilih'],
        'pluginOptions' => [
            'allowClear' => false,
        ],
    ]); ?>
 -->
    <?= $form->field($model, 'schedule')->widget(MultipleInput::className(), [
    //'max' => 4,
    'id' => 'schedule',

    'columns' => [
            [
                'name'  => 'idjenispendapatan',
                'type'  => MultipleInputColumn::TYPE_HIDDEN_INPUT,
                'title' => 'Jenis Pendapatan',
                // 'enableError' => true,
                // 'options' => [
                //     'data' => $datajenispendapatan,
                //     'options' => ['placeholder' => 'Pilih'],
                // ],
            ],
            [
                'name'  => 'jumlah',
                'type' => NumberControl::classname(),
                'title' => 'Jumlah',
                'enableError' => true,
            ],
        ]
     ])->label(false);
    ?>
    <?php if (!Yii::$app->request->isAjax){ ?>
        <div class="form-group">
            <?= Html::submitButton($model->isNewRecord ? 'Tambah' : 'Ubah', ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
        </div>
    <?php } ?>

    <?php ActiveForm::end(); ?>
</div>
