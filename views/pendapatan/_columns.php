<?php
use yii\helpers\Url;
use yii\helpers\Html;
use yii\helpers\ArrayHelper;
use app\models\Prodi;
use app\models\Fakultas;
use app\models\JenisPendapatan;
use app\models\Pendapatan;
use app\models\TahunAjaran;
use kartik\grid\GridView;
use kartik\number\NumberControl;
use app\models\SubAkun;

$colprodi       =['visible' => false];
$colfakultas    =['visible' => false];
$tamplate = "{view} {update} {delete}";

if(Yii::$app->user->identity->idprodi==0){
    $tamplate = "{view} {update} {delete}";
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
        'value'=>function($data){
            return Html::a($data->prodi->nama_prodi,'?r=mahasiswa/index&MahasiswaSearch[idprodi]='.$data->prodi->id);
        },
        //'value'=>'prodi.nama_prodi',
        'visible' => true,
        'format' => 'raw'
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
        'attribute' => 'idsubakun',
        'filter'=>ArrayHelper::map(SubAkun::find()->where(['idakun'=>'4'])->all(),'id','nama_sub_akun'),
        'filterType' => GridView::FILTER_SELECT2,
        'filterWidgetOptions' => [
            'options' => ['placeholder' => 'Pilih'],
            'pluginOptions' => [
                'allowClear' => true,
            ],
        ],
        'value'=>'subakun.nama_sub_akun',
    ],
    [
        'class'=>'\kartik\grid\DataColumn',
        'attribute'=>'jumlah',
        'filterType'=>NumberControl::classname(),
        'format' => ['decimal', 0],
        'hAlign' => 'right',
        'contentOptions'=>['style'=>'text-align:right; font-weight:bold; color:green;'],
        'pageSummary' => number_format(Pendapatan::getTotal($dataProvider->models, 'jumlah')),
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