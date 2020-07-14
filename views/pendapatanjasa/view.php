<?php

use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model app\models\Pendapatan */
?>
<div class="pendapatan-view">
 
    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
        	// 'fakultas.nama_fakultas',
         //    'prodi.nama_prodi',
            'jenispendapatan.jenis_pendapatan',
             [
                'attribute'=>'jumlah',
                'format' => ['decimal', 0],
            ],
            'tahunajaran.tahun_ajaran',
            'tanggal',
        ],
    ]) ?>

</div>
