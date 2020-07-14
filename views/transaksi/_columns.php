<?php
use yii\helpers\Url;
use yii\helpers\ArrayHelper;
use app\models\SubAkun;
use kartik\grid\GridView;
use app\models\Transaksi;

return [
    [
        'class' => 'kartik\grid\CheckboxColumn',
        'width' => '20px',
        'pageSummary' => 'Total:',
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
        'attribute' => 'idsubakun',
        'filter'=>ArrayHelper::map(SubAkun::find()->all(),'id','nama_sub_akun'),
        'filterType' => GridView::FILTER_SELECT2,
        'filterWidgetOptions' => [
            'options' => ['placeholder' => 'Pilih'],
            'pluginOptions' => [
                'allowClear' => true,
            ],
        ],
        'value'=>'subakun.nama_sub_akun',
    ],
    
    // [
    //     'class'=>'\kartik\grid\DataColumn',
    //     'attribute'=>'idakundebet',
    // ],
    // [
    //     'class'=>'\kartik\grid\DataColumn',
    //     'attribute'=>'idakunkredit',
    // ],
    [
        'class'=>'\kartik\grid\DataColumn',
        'attribute'=>'no_ref',
    ],
    [
        'class'=>'\kartik\grid\DataColumn',
        'attribute'=>'debet',
        'format' => ['decimal', 0],
        'hAlign' => 'right',
        'contentOptions'=>['style'=>'text-align:right; font-weight:bold; color:green;'],
        'pageSummary' => number_format(Transaksi::getTotal($dataProvider->models, 'debet')),
        //'contentOptions'=>['style'=>'text-align:right; font-weight:bold; color:red;'],
        'pageSummaryOptions' => ['style' => 'font-weight:bold; text-align:right;']
    ],
    [
        'class'=>'\kartik\grid\DataColumn',
        'attribute'=>'kredit',
        'format' => ['decimal', 0],
        'hAlign' => 'right',
        'contentOptions'=>['style'=>'text-align:right; font-weight:bold; color:red;'],
        'pageSummary' => number_format(Transaksi::getTotal($dataProvider->models, 'kredit')),
        'pageSummaryOptions' => ['style' => 'font-weight:bold; text-align:right;']
    ],
    [
        'class'=>'\kartik\grid\DataColumn',
        'attribute'=>'keterangan',
        'group' => true, 
        'subGroupOf' => 1,
        'contentOptions'=>['style'=>'vertical-align:middle;']
    ],
    [
        'class'=>'\kartik\grid\DataColumn',
        'attribute'=>'tanggal',
        'format' => ['date', 'php:d / m / Y'],
        'group' => true,  // enable grouping,
            'groupedRow' => true,                    // move grouped column to a single grouped row
            'groupOddCssClass' => 'kv-grouped-row',  // configure odd group cell css class
            'groupEvenCssClass' => 'kv-grouped-row',
    ],
    [
        'class' => 'kartik\grid\ActionColumn',
        'dropdown' => false,
        'vAlign'=>'middle',
        'urlCreator' => function($action, $model, $key, $index) { 
                return Url::to([$action,'id'=>$model->no_ref]);
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