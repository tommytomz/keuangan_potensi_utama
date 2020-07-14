<?php

use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model app\models\Mahasiswa */
?>
<div class="mahasiswa-view">
 
    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            'id',
            'nim',
            'nama',
            'idprodi',
            'angkatan',
            'semester',
            'tagihan',
            'biaya_pendidikan',
            'total_bayar',
            'tanggal_bayar',
            'keterangan:ntext',
        ],
    ]) ?>

</div>
