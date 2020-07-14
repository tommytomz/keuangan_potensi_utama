<?php

use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model app\models\Pengeluaran */
?>
<div class="pengeluaran-view">
 
    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
        	'fakultas.nama_fakultas',
            'prodi.nama_prodi',
            'subakun.nama_sub_akun',
            [
            	'attribute'=>'jumlah',
        		'format' => ['decimal', 0],
            ],
            'tahunajaran.tahun_ajaran',
            'tanggal',
        ],
    ]) ?>

</div>
