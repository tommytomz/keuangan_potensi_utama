<?php
use yii\helpers\Url;
use app\models\Prodi;
use yii\helpers\ArrayHelper;
use kartik\grid\GridView;
use app\models\Mahasiswa;

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
        'attribute'=>'nim',
    ],
    [
        'class'=>'\kartik\grid\DataColumn',
        'attribute'=>'nama',
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
        'value'=>'prodi.nama_prodi',
        'visible' => true,
    ],
    [
        'class'=>'\kartik\grid\DataColumn',
        'attribute'=>'angkatan',
    ],
    [
        'class'=>'\kartik\grid\DataColumn',
        'attribute'=>'semester',
    ],
    [
        'class'=>'\kartik\grid\DataColumn',
        'attribute'=>'tagihan',
        'format' => ['decimal', 0],
        'footer' => number_format(Mahasiswa::getTotal($dataProvider->models, 'tagihan')),
    ],
    [
        'class'=>'\kartik\grid\DataColumn',
        'attribute'=>'biaya_pendidikan',
        'format' => ['decimal', 0],
        'footer' => number_format(Mahasiswa::getTotal($dataProvider->models, 'biaya_pendidikan')),
    ],
    [
        'class'=>'\kartik\grid\DataColumn',
        'attribute'=>'total_bayar',
        'format' => ['decimal', 0],
        'footer' => number_format(Mahasiswa::getTotal($dataProvider->models, 'total_bayar')),
    ],
    [
        'class'=>'\kartik\grid\DataColumn',
        'attribute'=>'tanggal_bayar',
    ],
    // [
        // 'class'=>'\kartik\grid\DataColumn',
        // 'attribute'=>'keterangan',
    // ],
    [
        'class' => 'kartik\grid\ActionColumn',
        'dropdown' => false,
        'vAlign'=>'middle',
        'urlCreator' => function($action, $model, $key, $index) { 
                return Url::to([$action,'id'=>$key]);
        },
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