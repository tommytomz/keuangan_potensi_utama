<?php
use yii\helpers\Url;
use yii\helpers\ArrayHelper;
use app\models\Pengeluaran;
use app\models\TahunAjaran;
use app\models\Fakultas;
use app\models\Prodi;
use kartik\grid\GridView;

$colprodi       =['visible' => false];
$colfakultas    =['visible' => false];
$tamplate = "{view} {update} {delete}";

if(Yii::$app->user->identity->idprodi==0){
    //$tamplate = "{view}";
    $tamplate = "{view} {update} {delete}";
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
        'value'=>'nama_fakultas',
        'visible' => true,
    ];

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
}

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
   [
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
        'value'=>'nama_fakultas',
        'visible' => true,
    ],
    [
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
        'value'=>'nama_prodi',
        'visible' => true,
    ],
    //$colprodi,
    // [
    //     'class'=>'\kartik\grid\DataColumn',
    //     'attribute'=>'pendapatan.idjenispendapatan',
    // ],
    [
        'class'=>'\kartik\grid\DataColumn',
        'attribute'=>'pendapatan',
        'format' => ['decimal', 0],
        'footer' => number_format(Pengeluaran::getTotal($dataProvider->models, 'pendapatan')),
        
    ],
    [
        'class'=>'\kartik\grid\DataColumn',
        'attribute'=>'pengeluaran',
        'format' => ['decimal', 0],
        'footer' => number_format(Pengeluaran::getTotal($dataProvider->models, 'pengeluaran')),
        
    ],
    [
        'class'=>'\kartik\grid\DataColumn',
        'attribute'=>'jumlah',
        'filter'=>false,
        'format' => ['decimal', 0],
        'footer' => number_format(Pengeluaran::getTotal($dataProvider->models, 'jumlah')),
        
    ],
    
    // [
    //     'class'=>'\kartik\grid\DataColumn',
    //     'attribute' => 'idtahunajaran',
    //     'filter'=>ArrayHelper::map(TahunAjaran::find()->all(),'id','tahun_ajaran'),
    //     'filterType' => GridView::FILTER_SELECT2,
    //     'filterWidgetOptions' => [
    //         'options' => ['placeholder' => 'Pilih'],
    //         'pluginOptions' => [
    //             'allowClear' => true,
    //         ],
    //     ],
    //     'value'=>'tahunajaran.tahun_ajaran',
    // ],
    // [
    //     'class'=>'\kartik\grid\DataColumn',
    //     'attribute'=>'tanggal',
    // ],
    // [
    //     'class' => 'kartik\grid\ActionColumn',
    //     'dropdown' => false,
    //     'vAlign'=>'middle',
    //     'urlCreator' => function($action, $model, $key, $index) { 
    //             return Url::to([$action,'id'=>$key]);
    //     },
    //     'template' => $tamplate,
    //     'viewOptions'=>['role'=>'modal-remote','title'=>'View','data-toggle'=>'tooltip'],
    //     'updateOptions'=>['role'=>'modal-remote','title'=>'Update', 'data-toggle'=>'tooltip'],
    //     'deleteOptions'=>['role'=>'modal-remote','title'=>'Delete', 
    //                       'data-confirm'=>false, 'data-method'=>false,// for overide yii data api
    //                       'data-request-method'=>'post',
    //                       'data-toggle'=>'tooltip',
    //                       'data-confirm-title'=>'Anda Yakin?',
    //                       'data-confirm-message'=>'Apakah ingin menghapus data ini'], 
    // ],

];   