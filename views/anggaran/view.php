<?php

use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model app\models\Anggaran */
?>
<div class="anggaran-view">
 
    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            'fakultas.nama_fakultas',
            'prodi.nama_prodi',
            'kegiatan',
            [
                'attribute'=>'jumlah',
                'format' => ['decimal', 0],
            ],
            'tahunajaran.tahun_ajaran',
            'tanggal',
            'status'
        ],
    ]) ?>

</div>
