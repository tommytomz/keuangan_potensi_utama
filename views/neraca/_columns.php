<?php
use yii\helpers\Url;
use yii\helpers\ArrayHelper;
use app\models\SubAkun;
use kartik\grid\GridView;
use app\models\Transaksi;
$totalkredit =0;
return [
    // [
    //     'class' => 'kartik\grid\CheckboxColumn',
    //     'width' => '20px',
    //     'footer' => 'Total:',
    // ],
    [
        'class' => 'kartik\grid\SerialColumn',
        'width' => '30px',
        //'pageSummary' => 'Laba : ('.number_format(Transaksi::getTotal($dataProvider->models, 'kredit')).' - '.number_format(Transaksi::getTotal($dataProvider->models, 'debet')).''.')',
        // 'pageSummary' => 'Laba = Pendapatan - Beban/Biaya',
        // 'pageSummaryOptions' => ['colspan' => 2]
    ],
        // [
        // 'class'=>'\kartik\grid\DataColumn',
        // 'attribute'=>'id',
    // ],
    // [
    //     'class'=>'\kartik\grid\DataColumn',
    //     'attribute'=>'tanggal',
    // ],
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
        'attribute' => 'kategori',
        'group' => true,
        'groupedRow' => true,                    // move grouped column to a single grouped row
        'groupOddCssClass' => 'kv-grouped-row',  // configure odd group cell css class
        'groupEvenCssClass' => 'kv-grouped-row',
        'groupFooter' => function ($model, $key, $index, $widget) { 
            //$p = compact('model', 'key', 'index');
            return [
                'mergeColumns' => [[0,3]], // columns to merge in summary
                'content' => [             // content to show in each summary cell
                    2 => 'Total : ',
                    //5 => GridView::F_AVG,
                    4 => GridView::F_SUM,
                    5 => GridView::F_SUM,
                    //5 => Pembayaran::getTotal($model, 'total'),
                ],
                'contentFormats' => [      // content reformatting for each summary cell
                    //6 => ['format' => 'number', 'decimals' => 2],
                    4 => ['format' => 'number', 'decimals' => 0],
                    5 => ['format' => 'number', 'decimals' => 0],
                    // 6 => ['format' => 'number', 'decimals' => 2],
                ],
                'contentOptions' => [      // content html attributes for each summary cell
                    // 1 => ['style' => 'font-variant:small-caps'],
                    0 => ['style' => 'text-align:left'],
                    4 => ['style' => 'text-align:right'],
                    5 => ['style' => 'text-align:right'],
                    //6 => ['style' => 'text-align:right'],
                ],
                // html attributes for group summary row
                'options' => ['class' => 'info table-info','style' => 'font-weight:bold;']
            ];


        },
        
        // 'footerOptions' => ['class' => 'grid-footer', 'colspan' => 2],
    ],
    [
        'class'=>'\kartik\grid\DataColumn',
        'attribute'=>'nama_kategori',
        'group' => true,
        'label' => '',
        'contentOptions'=>['style'=>'vertical-align:middle;']
        
        //'footerOptions' => ['class' => 'grid-footer', 'colspan' => 2],
    ],

    [
        'class'=>'\kartik\grid\DataColumn',
        'attribute' => 'nama_akun',
    ],
    [
        'class'=>'\kartik\grid\DataColumn',
        'label'=>'',
        'attribute'=>'debet',
        // 'value' => function ($model) {
        //     // print_r($model['debet']);
        //     if($model['debet']=="0"){
        //         return "";
        //     }else{
        //         return $model['debet'];
        //     }
           
        // },
        'format' => ['decimal', 0],
        'contentOptions'=>['style'=>'text-align:right; font-weight:bold; color:green;'],
        'hAlign' => 'right',
        'pageSummary' => true,
        'pageSummaryOptions' => ['style' => 'font-weight:bold; color:green;']

        // 'pageSummary' => number_format(Transaksi::getTotal($dataProvider->models, 'debet')),
        // 'hAlign' => 'right', 
        //'footer' => ,

    ],
    [
        'class'=>'\kartik\grid\DataColumn',
        'label'=>'',
        'attribute'=>'kredit',
        'format' => ['decimal', 0],
        'contentOptions'=>['style'=>'text-align:right; font-weight:bold; color:red;'],
        'hAlign' => 'right',
        'pageSummary' => true,
        'pageSummaryOptions' => ['style' => 'font-weight:bold; color:red;']
        //'pageSummary' => number_format(Transaksi::getTotal($dataProvider->models, 'kredit') - Transaksi::getTotal($dataProvider->models, 'debet')),
    ],
    // [
    //     'class'=>'\kartik\grid\DataColumn',
    //     'attribute'=>'total',
    //    // 'format' => ['decimal', 0],
    //     'hAlign' => 'right',
    //     'pageSummary' => number_format(Transaksi::getTotal($dataProvider->models, 'debet') - Transaksi::getTotal($dataProvider->models, 'kredit')),
    // ],

];   