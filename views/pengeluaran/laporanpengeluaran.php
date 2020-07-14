<?php
use yii\helpers\Url;
use yii\helpers\Html;
use yii\bootstrap\Modal;
use kartik\grid\GridView;
use johnitvn\ajaxcrud\CrudAsset; 
use johnitvn\ajaxcrud\BulkButtonWidget;

/* @var $this yii\web\View */
/* @var $searchModel app\models\PengeluaranSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Laporan Pertanggung Jawaban';
$this->params['breadcrumbs'][] = $this->title;

CrudAsset::register($this);

$content = ['content'=>
                    // Html::a('<i class="glyphicon glyphicon-plus"></i>', ['create'],
                    // ['role'=>'modal-remote','title'=> 'Tambah Laporan Pertanggung Jawaban','class'=>'btn btn-success']).
                    // Html::a('<i class="glyphicon glyphicon-repeat"></i>', [''],
                    // ['data-pjax'=>1, 'class'=>'btn btn-primary', 'title'=>'Reset Grid']).
                    Html::a('<i class="glyphicon glyphicon-file"></i>', ['laporanlpj'],
                    ['role'=>'modal-remote','title'=> 'Laporan','class'=>'btn btn-warning']).
                    '{toggleData}'
                ];

?>
<div class="pengeluaran-index">
    <div id="ajaxCrudDatatable">
        <?=GridView::widget([
            'id'=>'crud-datatable',
            'dataProvider' => $dataProvider,
            'filterModel' => $searchModel,
            'pjax'=>true,
            'showFooter' => true,
            'columns' => require(__DIR__.'/columnpengeluaran.php'),
            'toolbar'=> [
                $content
            ],          
            'striped' => true,
            'condensed' => true,
            'responsive' => true,          
            'panel' => [
                'type' => 'primary', 
                'heading' => '<i class="glyphicon glyphicon-list"></i> Daftar Laporan Pertanggung Jawaban',
                //'before'=>'<em>* Resize table columns just like a spreadsheet by dragging the column edges.</em>',
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
            ],
            
        ])?>
    </div>
</div>
<?php Modal::begin([
    "id"=>"ajaxCrudModal",
    "footer"=>"",// always need it for jquery plugin
    'size' => Modal::SIZE_LARGE,
    'options' => ['tabindex' => false],
])?>
<?php Modal::end(); ?>
