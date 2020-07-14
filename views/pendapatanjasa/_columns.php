<?php
use yii\helpers\Url;
use yii\helpers\ArrayHelper;
use app\models\Prodi;
use app\models\Fakultas;
use app\models\JenisPendapatan;
use app\models\Pendapatan;
use app\models\TahunAjaran;
use kartik\grid\GridView;
use kartik\number\NumberControl;

$colprodi       =['visible' => false];
$colfakultas    =['visible' => false];
$tamplate = "{view} {update} {delete}";

return [
    [
        'class' => 'kartik\grid\CheckboxColumn',
        'width' => '20px',
        'footer' => 'Total:',
    ],
   
    [
        'class' => 'kartik\grid\SerialColumn',
        'width' => '30px',
    ],
        // [
        // 'class'=>'\kartik\grid\DataColumn',
        // 'attribute'=>'id',
    // ],
    // $colfakultas,
    // $colprodi,
    [
        'class'=>'\kartik\grid\DataColumn',
        'attribute' => 'idjenispendapatan',
        'filter'=>ArrayHelper::map(JenisPendapatan::find()->all(),'id','jenis_pendapatan'),
        'filterType' => GridView::FILTER_SELECT2,
        'filterWidgetOptions' => [
            'options' => ['placeholder' => 'Pilih'],
            'pluginOptions' => [
                'allowClear' => true,
            ],
        ],
        'value'=>'jenispendapatan.jenis_pendapatan',
    ],
    [
        'class'=>'\kartik\grid\DataColumn',
        'attribute'=>'jumlah',
        'filterType'=>NumberControl::classname(),
        'format' => ['decimal', 0],
        'footer' => number_format(Pendapatan::getTotal($dataProvider->models, 'jumlah')),
    ],
    [
        'class'=>'\kartik\grid\DataColumn',
        'attribute' => 'idtahunajaran',
        'filter'=>ArrayHelper::map(TahunAjaran::find()->all(),'id','tahun_ajaran'),
        'filterType' => GridView::FILTER_SELECT2,
        'filterWidgetOptions' => [
            'options' => ['placeholder' => 'Pilih'],
            'pluginOptions' => [
                'allowClear' => true,
            ],
        ],
        'value'=>'tahunajaran.tahun_ajaran',
    ],
    [
        'class'=>'\kartik\grid\DataColumn',
        'attribute'=>'tanggal',
    ],
    [
        'class' => 'kartik\grid\ActionColumn',
        'dropdown' => false,
        'vAlign'=>'middle',
        'urlCreator' => function($action, $model, $key, $index) { 
                return Url::to([$action,'id'=>$key]);
        },
        'template' => $tamplate,
        'viewOptions'=>['role'=>'modal-remote','title'=>'View','data-toggle'=>'tooltip'],
        'updateOptions'=>['role'=>'modal-remote','title'=>'Update', 'data-toggle'=>'tooltip'],
        'deleteOptions'=>['role'=>'modal-remote','title'=>'Delete', 
                          'data-confirm'=>false, 'data-method'=>false,// for overide yii data api
                          'data-request-method'=>'post',
                          'data-toggle'=>'tooltip',
                          'data-confirm-title'=>'Anda Yakin?',
                          'data-confirm-message'=>'Apakah ingin menghapus data ini'], 
    ],

];   