<?php
use yii\helpers\Url;
use app\models\Anggaran;
use app\models\Prodi;
use app\models\Fakultas;
use kartik\grid\GridView;
use app\models\TahunAjaran;
use yii\helpers\ArrayHelper;
use kartik\number\NumberControl;

$colprodi       =['visible' => false];
$colfakultas    =['visible' => false];
$tamplate = "{view} {update} {delete}";

if(Yii::$app->user->identity->idprodi==0){
    $tamplate = "{view} {update}";
    $colprodi = [
        'class'=>'\kartik\grid\DataColumn',
        'attribute' => 'idprodi',
        'filter'=>ArrayHelper::map(Prodi::find()->all(),'id','nama_prodi'),
        'filterType' => GridView::FILTER_SELECT2,
        'filterWidgetOptions' => [
            'options' => ['placeholder' => 'Pilih'],
            'pluginOptions' => [
                'allowClear' => true,
            ],
        ],
        'label'=>'Program Studi',
        'value'=>'prodi.nama_prodi',
        'visible' => true,
    ];

    $colfakultas = [
        'class'=>'\kartik\grid\DataColumn',
        'attribute' => 'idfakultas',
        'filter'=>ArrayHelper::map(Fakultas::find()->all(),'id','nama_fakultas'),
        'filterType' => GridView::FILTER_SELECT2,
        'filterWidgetOptions' => [
            'options' => ['placeholder' => 'Pilih'],
            'pluginOptions' => [
                'allowClear' => true,
            ],
        ],
        'label'=>'Fakultas',
        'value'=>'fakultas.nama_fakultas',
        'visible' => true,
    ];
}

return [
    [
        'class' => 'kartik\grid\CheckboxColumn',
        'width' => '20px',
        
    ],
    [
        'class' => 'kartik\grid\SerialColumn',
        'width' => '30px',
        'pageSummary' => 'Total:',
    ],
        // [
        // 'class'=>'\kartik\grid\DataColumn',
        // 'attribute'=>'id',
    // ],
    $colfakultas,
    $colprodi,
    [
        'class'=>'\kartik\grid\DataColumn',
        'attribute'=>'kegiatan',
    ],
    [
        'class'=>'\kartik\grid\DataColumn',
        'attribute'=>'jumlah',
        'filterType'=>NumberControl::classname(),
        'format' => ['decimal', 0],
        'hAlign' => 'right',
        'contentOptions'=>['style'=>'text-align:right; font-weight:bold; color:green;'],
        'pageSummary' => number_format(Anggaran::getTotal($dataProvider->models, 'jumlah')),
        'headerOptions' => [
            // this should be on a CSS file as class instead of a inline style attribute...
            'style' => 'text-align: center'
        ]
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
        'attribute' => 'status',
        'filter'=>['Menunggu'=>'Menunggu', 'Tidak Disetujui'=>'Tidak Disetujui', 'Disetujui'=>'Disetujui'],
        'filterType' => GridView::FILTER_SELECT2,
        'filterWidgetOptions' => [
            'options' => ['placeholder' => 'Pilih'],
            'pluginOptions' => [
                'allowClear' => true,
            ],
        ],
        'contentOptions' => function ($model, $key, $index, $column) {
            return ['style' => 'color:' 
                . ($model->status=='Disetujui'? 'green' : ($model->status=='Menunggu'?'blue':'red')).'; font-weight:bold;'
            ];
        },
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