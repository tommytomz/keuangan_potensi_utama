<?php
use yii\helpers\Html;
use yii\widgets\ActiveForm;
use kartik\select2\Select2;
use yii\helpers\ArrayHelper;
use app\models\TahunAjaran;
use unclead\multipleinput\MultipleInput;
use unclead\multipleinput\examples\models\ExampleModel;
use kartik\number\NumberControl;

/* @var $this yii\web\View */
/* @var $model app\models\Anggaran */
/* @var $form yii\widgets\ActiveForm */
$datatahunajaran=ArrayHelper::map(TahunAjaran::find()->all(),'id','tahun_ajaran');
?>

<div class="anggaran-form">

    <?php $form = ActiveForm::begin(); ?>
    
    <?= $form->field($model, 'schedule')->widget(MultipleInput::className(), [
    //'max' => 4,
    'id' => 'schedule',

    'columns' => [
            [
                'name'  => 'kegiatan',
                'title' => 'Kegiatan',
                'enableError' => true,
                
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
