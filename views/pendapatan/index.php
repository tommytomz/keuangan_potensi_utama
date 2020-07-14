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
/* @var $searchModel app\models\PendapatanSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Pendapatan';
$this->params['breadcrumbs'][] = $this->title;

CrudAsset::register($this);

$content = ['content'=>
                    Html::a('<i class="glyphicon glyphicon-plus"></i>', ['create'],
                    ['role'=>'modal-remote','title'=> 'Tambah Anggaran','class'=>'btn btn-success']).
                    Html::a('<i class="glyphicon glyphicon-repeat"></i>', [''],
                    ['data-pjax'=>1, 'class'=>'btn btn-primary', 'title'=>'Reset Grid']).
                    // Html::a('<i class="glyphicon glyphicon-file"></i>', ['laporan'],
                    // ['role'=>'modal-remote','title'=> 'Laporan','class'=>'btn btn-warning']).
                    '{toggleData}'.
                    '{export}'
                ];

if(Yii::$app->user->identity->idprodi==0){
    $content = ['content'=>
                    Html::a('<i class="glyphicon glyphicon-plus"></i>', ['create'],
                    ['role'=>'modal-remote','title'=> 'Tambah Anggaran','class'=>'btn btn-success']).
                    Html::a('<i class="glyphicon glyphicon-repeat"></i>', [''],
                    ['data-pjax'=>1, 'class'=>'btn btn-primary', 'title'=>'Reset Grid']).
                    // Html::a('<i class="glyphicon glyphicon-file"></i>', ['laporan'],
                    // ['role'=>'modal-remote','title'=> 'Laporan','class'=>'btn btn-warning']).
                    '{toggleData}'.
                    '{export}'
                ];
}

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

<div class="pendapatan-index">
    <div id="ajaxCrudDatatable">
        <?=GridView::widget([
            'headertitle' => 'LAPORAN PENDAPATAN',
            'id'=>'crud-datatable',
            'dataProvider' => $dataProvider,
            'filterModel' => $searchModel,
            'pjax'=>true,
            //'showFooter' => true,
            'showPageSummary' => true,
            'columns' => require(__DIR__.'/_columns.php'),
            'toolbar'=> [
                $content
            ],          
            'striped' => true,
            'condensed' => true,
            'responsive' => true, 
            'afterFooter'=>[
                'content'=>'fafadf',
            ],         
            'panel' => [
                'type' => 'primary', 
                'heading' => '<i class="glyphicon glyphicon-list"></i> Daftar Pendapatan',
                //'before'=>'<em>* Resize table columns just like a spreadsheet by dragging the column edges.</em>',
                'after'=>
                // '<table width=100%>
                //     <tr height=30>
                //         <th></th>
                //         <th>Pendapatan</th>
                //         <th>Nilai Akhir</th>
                //     </tr>
                //     <tr height=30 style="background:#dbeffb; color:blue; font-weight:bold;">
                //         <td><b>Mahasiswa</b></td>
                //         <td>'.number_format($pendapatanmahasiswa).'</td>
                //         <td>'.number_format($nilaiakhirmahasiswa).'</td>
                //     </tr>
                //     <tr height=30 style="background:#defbdb; color:green; font-weight:bold;">
                //         <td><b>Dosen</b></td>
                //         <td>'.number_format($pendapatandosen).'</td>
                //         <td>'.number_format($nilaiakhirdosen).'</td>
                //     </tr>
                //     <tr height=30 style="background:#fbfbdb; color:#fd7c27; font-weight:bold;">
                //         <td><b>Lain-lain</b></td>
                //         <td>'.number_format($pendapatanlain).'</td>
                //         <td>'.number_format($nilaiakhirlain).'</td>
                //     </tr>
                // </table><br>'.
                BulkButtonWidget::widget([
                            'buttons'=>Html::a('<i class="glyphicon glyphicon-trash"></i>&nbsp; Hapus Semua',
                                ["bulk-delete"] ,
                                [
                                    "class"=>"btn btn-danger btn-xs",
                                    'role'=>'modal-remote-bulk',
                                    'data-confirm'=>false, 'data-method'=>false,// for overide yii data api
                                    'data-request-method'=>'post',
                                    'data-confirm-title'=>'Anda Yakin?',
                                    'data-confirm-message'=>'Apakah ingin menghapus data ini'
                                ]),
                        ]).                        
                        '<div class="clearfix"></div>',
            ]
        ])?>
    </div>
</div>
<?php Modal::begin([
    "id"=>"ajaxCrudModal",
    // 'size' => 'modal-lg',
    'size' => Modal::SIZE_LARGE,
    "footer"=>"",// always need it for jquery plugin
    'options' => ['tabindex' => false],
])?>


<?php Modal::end(); ?>

<?php
$js = "

    $('#caridata').click(function() {
        $.pjax.reload({'push':true, 'replace':false, container: '#crud-datatable-pjax', url:'".Yii::$app->request->baseUrl."/index.php?r=pendapatan&Pendapatan[tanggal_dari]='+$( 'input#tanggal_dari' ).val()+'&Pendapatan[tanggal_sampai]='+$( 'input#tanggal_sampai' ).val(), timeout: 1000});
        
    });
    ";
    $this->registerJs($js, View::POS_READY);
?>
