<?php
use yii\helpers\Url;
use yii\helpers\ArrayHelper;
use kartik\grid\GridView;
use app\models\Akun;

return [
    [
        'class' => 'kartik\grid\CheckboxColumn',
        'width' => '20px',
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
        'attribute' => 'idakun',
        'filter'=>ArrayHelper::map(Akun::find()->all(),'id','nama_akun'),
        'filterType' => GridView::FILTER_SELECT2,
        'filterWidgetOptions' => [
            'options' => ['placeholder' => 'Pilih'],
            'pluginOptions' => [
                'allowClear' => true,
            ],
        ],
        'label'=>'Nama Akun',
        'value'=>'akun.nama_akun',
        'visible' => true,
    ],
    [
        'class'=>'\kartik\grid\DataColumn',
        'attribute'=>'kode_akun',
    ],
    [
        'class'=>'\kartik\grid\DataColumn',
        'attribute'=>'nama_sub_akun',
    ],
    // [
    //     'class'=>'\kartik\grid\DataColumn',
    //     'attribute'=>'debet',
    //     'format' => ['decimal', 0],
    //     'contentOptions'=>['style'=>'text-align:right; font-weight:bold; color:green;'],
    //     'hAlign' => 'right',
    // ],
    // [
    //     'class'=>'\kartik\grid\DataColumn',
    //     'attribute'=>'kredit',
    //     'format' => ['decimal', 0],
    //     'contentOptions'=>['style'=>'text-align:right; font-weight:bold; color:red;'],
    //     'hAlign' => 'right',
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