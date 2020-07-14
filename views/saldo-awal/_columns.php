<?php
use yii\helpers\Url;
use yii\helpers\ArrayHelper;
use kartik\grid\GridView;
use app\models\SubAkun;

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
        'attribute' => 'idsubakun',
        'filter'=>ArrayHelper::map(SubAkun::find()->all(),'id','nama_sub_akun'),
        'filterType' => GridView::FILTER_SELECT2,
        'filterWidgetOptions' => [
            'options' => ['placeholder' => 'Pilih'],
            'pluginOptions' => [
                'allowClear' => true,
            ],
        ],
        //'label'=>'Sub Akun',
        'value'=>'subakun.nama_sub_akun',
        'visible' => true,
    ],
    [
        'class'=>'\kartik\grid\DataColumn',
        'attribute'=>'debet',
        'format' => ['decimal', 0],
        'hAlign' => 'right',
    ],
    [
        'class'=>'\kartik\grid\DataColumn',
        'attribute'=>'kredit',
        'format' => ['decimal', 0],
        'hAlign' => 'right',
    ],
    [
        'class'=>'\kartik\grid\DataColumn',
        'filterType' => GridView::FILTER_DATE,
        'filterWidgetOptions' => [
            'pluginOptions' => [
                'autoclose' => true,
                'format' => 'yyyy-mm-dd',
                'locale'=>[
                    'format'=>'yyyy-mm-dd',
                ]
            ],
        ],
        'attribute'=>'tanggal',
        'format' => ['date', 'php:d-m-Y'],
    ],
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