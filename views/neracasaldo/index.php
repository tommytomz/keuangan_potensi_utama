<?php
use yii\helpers\Url;
use yii\helpers\Html;
use yii\bootstrap\Modal;
use kartik\grid\GridView;
use johnitvn\ajaxcrud\CrudAsset; 
use johnitvn\ajaxcrud\BulkButtonWidget;
use kartik\date\DatePicker;
use yii\web\View;

/* @var $this yii\web\View */
/* @var $searchModel app\models\TransaksiSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Neraca Saldo';
$this->params['breadcrumbs'][] = $this->title;

CrudAsset::register($this);

?>
<div class="row">
    <div class="col-md-6">
        <div class="row" style="margin-bottom:5px;">
            <div class="col-md-3" style="padding-top:6px;">
                <b>Dari</b>
            </div>
            <div class="col-md-8">
                <?=DatePicker::widget([
                    'name' => 'tanggal_dari',
                    'id' => 'tanggal_dari',
                    'value' => isset($_GET['Transaksi']['tanggal_dari']) ? $_GET['Transaksi']['tanggal_dari'] : date('d-m-Y'),
                    'options' => ['placeholder' => 'Pilih Tanggal'],
                    'pluginOptions' => [
                        'format' => 'dd-mm-yyyy',
                        'autoclose' => true,
                        'todayHighlight' => true
                    ]
                ])?>
            </div>
        </div>
        <div class="row" style="margin-bottom:5px;">
            <div class="col-md-3" style="padding-top:6px;">
                <b>Sampai</b>
            </div>
            <div class="col-md-8">
                <?=DatePicker::widget([
                    'name' => 'tanggal_sampai',
                    'id' => 'tanggal_sampai',
                    'value' => isset($_GET['Transaksi']['tanggal_sampai']) ? $_GET['Transaksi']['tanggal_sampai'] : date('d-m-Y'),
                    'options' => ['placeholder' => 'Pilih Tanggal'],
                    'pluginOptions' => [
                        'format' => 'dd-mm-yyyy',
                        'autoclose' => true,
                        'todayHighlight' => true
                    ]
                ])?>
            </div>
        </div>
        <div class="row">
            <div class="col-md-offset-3 col-md-5">
                <?=Html::button('<span class="fa fa-search"></span> Cari', ['class' => 'btn btn-primary', 'id' => 'caridata'])?>
            </div>
        </div>
    </div>
</div>
<br>

<div class="transaksi-index">
    <div id="ajaxCrudDatatable">
        <?=GridView::widget([
            'id'=>'crud-datatable',
            'dataProvider' => $dataProvider,
            //'filterModel' => $searchModel,
            'formatter' => ['class' => 'yii\i18n\Formatter','nullDisplay' => ''],
            'pjax'=>true,
            'showPageSummary' => true,
            //'showFooter' => true,
            //'footerRowOptions' => ['style' => 'text-align:right; font-weight:bold;'],
            'columns' => require(__DIR__.'/_columns.php'),
            'toolbar'=> [
                ['content'=>
                    Html::a('<i class="glyphicon glyphicon-repeat"></i>', [''],
                    ['data-pjax'=>1, 'class'=>'btn btn-default', 'title'=>'Reset Grid']).
                    '{toggleData}'.
                    '{export}'
                ],
            ],          
            'striped' => true,
            'condensed' => true,
            'responsive' => true,          
            'panel' => [
                'type' => 'primary', 
                'heading' => '<i class="glyphicon glyphicon-list"></i> Daftar Neraca Saldo',
                //'before'=>$before,
                // 'after'=>BulkButtonWidget::widget([
                //             'buttons'=>Html::a('<i class="glyphicon glyphicon-trash"></i>&nbsp; Hapus Semua',
                //                 ["bulk-delete"] ,
                //                 [
                //                     "class"=>"btn btn-danger btn-xs",
                //                     'role'=>'modal-remote-bulk',
                //                     'data-confirm'=>false, 'data-method'=>false,// for overide yii data api
                //                     'data-request-method'=>'post',
                //                     'data-confirm-title'=>'Anda Yakin?',
                //                     'data-confirm-message'=>'Apakah ingin menghapus data ini'
                //                 ]),
                //         ]).                        
                //         '<div class="clearfix"></div>',
            ]
        ])?>
    </div>
</div>
<?php Modal::begin([
    "id"=>"ajaxCrudModal",
    "footer"=>"",// always need it for jquery plugin
])?>
<?php Modal::end(); ?>

<?php
$js = "

    $('#caridata').click(function() {
        $.pjax.reload({'push':true, 'replace':false, container: '#crud-datatable-pjax', url:'".Yii::$app->request->baseUrl."/index.php?r=neracasaldo&Transaksi[tanggal_dari]='+$( 'input#tanggal_dari' ).val()+'&Transaksi[tanggal_sampai]='+$( 'input#tanggal_sampai' ).val(), timeout: 1000});
        
    });
    ";
    $this->registerJs($js, View::POS_READY);
?>
