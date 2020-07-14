<?php
use yii\helpers\Url;
use yii\helpers\ArrayHelper;
use app\models\SubAkun;
use kartik\grid\GridView;
use app\models\Transaksi;

return [
    // [
    //     'class' => 'kartik\grid\CheckboxColumn',
    //     'width' => '20px',
    //     'footer' => 'Total:',
    // ],
    // [
    //     'class' => 'kartik\grid\SerialColumn',
    //     'width' => '30px',
    // ],
        // [
        // 'class'=>'\kartik\grid\DataColumn',
        // 'attribute'=>'id',
    // ],
    [
        'class'=>'\kartik\grid\DataColumn',
        'attribute'=>'tanggal',
        'format' => ['date', 'php:d / m / Y'],
        'group' => true,  // enable grouping,
        'groupedRow' => true,                    // move grouped column to a single grouped row
        'groupOddCssClass' => 'kv-grouped-row',  // configure odd group cell css class
        'groupEvenCssClass' => 'kv-grouped-row',
    ],
    // [
    //     'class'=>'\kartik\grid\DataColumn',
    //     'attribute' => 'nama_sub_akun',
    //     'filter'=>ArrayHelper::map(SubAkun::find()->all(),'id','nama_sub_akun'),
    //     'filterType' => GridView::FILTER_SELECT2,
    //     'filterWidgetOptions' => [
    //         'options' => ['placeholder' => 'Pilih'],
    //         'pluginOptions' => [
    //             'allowClear' => true,
    //         ],
    //     ],
    //     'group' => true,  // enable grouping,
    //         'groupedRow' => true,                    // move grouped column to a single grouped row
    //         'groupOddCssClass' => 'kv-grouped-row',  // configure odd group cell css class
    //         'groupEvenCssClass' => 'kv-grouped-row',
    // ],
    [
        'class'=>'\kartik\grid\DataColumn',
        'attribute' => 'nama_akun',
        'pageSummary' => 'Total:',
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
        //'hAlign' => 'right',
        
    ],
    [
        'class'=>'\kartik\grid\DataColumn',
        'attribute'=>'debet',
        'format' => ['decimal', 0],
        'hAlign' => 'right',
        'contentOptions'=>['style'=>'text-align:right; font-weight:bold; color:green;'],
        'pageSummary' => number_format(Transaksi::getTotal($dataProvider->models, 'debet')),
    ],
    [
        'class'=>'\kartik\grid\DataColumn',
        'attribute'=>'kredit',
        'format' => ['decimal', 0],
        'hAlign' => 'right',
        'contentOptions'=>['style'=>'text-align:right; font-weight:bold; color:red;'],
        'pageSummary' => number_format(Transaksi::getTotal($dataProvider->models, 'kredit')),
    ],

];   