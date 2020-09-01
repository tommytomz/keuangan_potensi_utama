<?php
use yii\helpers\Html;
use yii\bootstrap\ActiveForm;

/* @var $this yii\web\View */
/* @var $form yii\bootstrap\ActiveForm */
/* @var $model \mdm\admin\models\form\ChangePassword */

$this->title = Yii::t('app', 'Ganti Password');
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="site-signup">
    <br>
    <div class="row">
        <div class="col-lg-6">
            <?php //$form = ActiveForm::begin(['id' => 'form-change']); ?>
                <?php $form = ActiveForm::begin(
                    [
                        'fieldConfig' => 
                        [
                            'options' => [
                                'class' => '',
                            ],
                            'labelOptions'=>[
                                'class' => 'control-label col-sm-5',
                            ],
                            'inputOptions'=>[
                                'class'=>'form-control input-sm',
                            ],
                            'enableError' => true,
                            'template' => '
                                <div class="col-sm-12">
                                    {label}
                                    <div class="col-sm-7">
                                        {input}{error}        
                                    </div>
                                </div>
                            ',
                        ] 
                    ]); 
                ?>
                <?= $form->field($model, 'oldPassword')->passwordInput() ?>
                <?= $form->field($model, 'newPassword')->passwordInput() ?>
                <?= $form->field($model, 'retypePassword')->passwordInput() ?>
                
                <div class="col-md-12">
                    <div class="col-md-5"></div>
                    <div class="col-md-7">
                        <?= Html::submitButton('Simpan', ['class' => 'btn btn-primary btn-sm']) ?>
                        <?php // Html::submitButton('Reset', ['class' => 'btn btn-default']) ?>
                    </div>
                </div>
            <?php ActiveForm::end(); ?>
            <div class="clearfix"></div>
        </div>
    </div>
</div>
