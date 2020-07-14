<?php
use yii\helpers\Url;
use yii\helpers\Html;
use yii\bootstrap\Modal;
use kartik\grid\GridView;
use johnitvn\ajaxcrud\CrudAsset; 
use johnitvn\ajaxcrud\BulkButtonWidget;

/* @var $this yii\web\View */
/* @var $searchModel app\models\PendapatanSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Pendapatan Jasa';
$this->params['breadcrumbs'][] = $this->title;

CrudAsset::register($this);

$content = ['content'=>
                    Html::a('<i class="glyphicon glyphicon-plus"></i>', ['create'],
                    ['role'=>'modal-remote','title'=> 'Tambah Anggaran','class'=>'btn btn-success']).
                    Html::a('<i class="glyphicon glyphicon-repeat"></i>', [''],
                    ['data-pjax'=>1, 'class'=>'btn btn-primary', 'title'=>'Reset Grid']).
                    Html::a('<i class="glyphicon glyphicon-file"></i>', ['laporan'],
                    ['role'=>'modal-remote','title'=> 'Laporan','class'=>'btn btn-warning']).
                    '{toggleData}'
                ];

if(Yii::$app->user->identity->idprodi==0){
    $content = ['content'=>
                    Html::a('<i class="glyphicon glyphicon-plus"></i>', ['create'],
                    ['role'=>'modal-remote','title'=> 'Tambah Anggaran','class'=>'btn btn-success']).
                    Html::a('<i class="glyphicon glyphicon-repeat"></i>', [''],
                    ['data-pjax'=>1, 'class'=>'btn btn-primary', 'title'=>'Reset Grid']).
                    Html::a('<i class="glyphicon glyphicon-file"></i>', ['laporan'],
                    ['role'=>'modal-remote','title'=> 'Laporan','class'=>'btn btn-warning']).
                    '{toggleData}'
                ];
}

?>
<div class="pendapatan-index">
    <div id="ajaxCrudDatatable">
        <?=GridView::widget([
            'id'=>'crud-datatable',
            'dataProvider' => $dataProvider,
            'filterModel' => $searchModel,
            'pjax'=>true,
            'showFooter' => true,
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
                'heading' => '<i class="glyphicon glyphicon-list"></i> Daftar Pendapatan Jasa',
                //'before'=>'<em>* Resize table columns just like a spreadsheet by dragging the column edges.</em>',
                'after'=>
                '<table width=100%>
                    <tr height=30>
                        <th></th>
                        <th>Pendapatan</th>
                        <th>Nilai Akhir</th>
                    </tr>
                    <tr height=30 style="background:#dbeffb; color:blue; font-weight:bold;">
                        <td><b>Mahasiswa</b></td>
                        <td>'.number_format($pendapatanmahasiswa).'</td>
                        <td>'.number_format($nilaiakhirmahasiswa).'</td>
                    </tr>
                    <tr height=30 style="background:#defbdb; color:green; font-weight:bold;">
                        <td><b>Dosen</b></td>
                        <td>'.number_format($pendapatandosen).'</td>
                        <td>'.number_format($nilaiakhirdosen).'</td>
                    </tr>
                    <tr height=30 style="background:#fbfbdb; color:#fd7c27; font-weight:bold;">
                        <td><b>Lain-lain</b></td>
                        <td>'.number_format($pendapatanlain).'</td>
                        <td>'.number_format($nilaiakhirlain).'</td>
                    </tr>
                </table><br>'.
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
