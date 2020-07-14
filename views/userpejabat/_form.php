<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use kartik\select2\Select2;
use yii\helpers\ArrayHelper;
use app\models\Pejabat;
use app\models\Fakultas;
use app\models\Prodi;
use mdm\admin\models\searchs\AuthItem as AuthItemSearch;

/* @var $this yii\web\View */
/* @var $model app\models\UserPejabat */
/* @var $form yii\widgets\ActiveForm */

$datapejabat        = ArrayHelper::map(Pejabat::find()->all(),'id','nama_pejabat');
$datafakultas       = ArrayHelper::map(Fakultas::find()->all(),'id','nama_fakultas');
$dataprodi          = ArrayHelper::map(Prodi::find()->all(),'id','nama_prodi');
$vdatahakakses = Yii::$app->db->createCommand('
            select 
                name
            from auth_item
            where type=1
        ')->queryAll();
$datahakakses = ArrayHelper::map($vdatahakakses,'name','name');
//print_r($datahakakses);
?>

<div class="user-pejabat-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'idpejabat')->widget(Select2::classname(), [
        'data' => $datapejabat,
        'options' => ['placeholder' => 'Pilih', 'onchange' => '
            //alert($("#userpejabat-idpejabat option:selected").text().toLowerCase());
            if($("#userpejabat-idpejabat option:selected").text().toLowerCase()=="kaprodi" || $("#userpejabat-idpejabat option:selected").text().toLowerCase()=="admin prodi"){
                $(".field-userpejabat-idprodi").show();
                $(".field-userpejabat-idfakultas").show();
            }else{
                $(".field-userpejabat-idprodi").hide();
                $(".field-userpejabat-idfakultas").hide();
            }
        '],
        'pluginOptions' => [
            'allowClear' => false,
        ],
    ]); ?>

    <?= $form->field($model, 'idfakultas')->widget(Select2::classname(), [
        'data' => $datafakultas,
        'options' => ['placeholder' => 'Pilih', 'onchange' => '
            $.post( "'.Yii::$app->urlManager->createUrl('anggaran/pilihprodi').'&idfakultas="+$(this).val(), function( data ) {
                $("#userpejabat-idprodi").html( data );
                
            });
            
        '],
        'pluginOptions' => [
            'allowClear' => false,
        ],
    ]); ?>

    <?= $form->field($model, 'idprodi')->widget(Select2::classname(), [
        'data' => [],
        'options' => ['placeholder' => 'Pilih'],
        'pluginOptions' => [
            'allowClear' => false,
        ],
    ]); ?>

    <?= $form->field($model, 'nama')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'username')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'password_hash')->passwordInput() ?>

    <?= $form->field($model, 'ulangi_password')->passwordInput() ?>

    <?= $form->field($model, 'email')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'hakakses')->widget(Select2::classname(), [
        'data' => $datahakakses,
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
