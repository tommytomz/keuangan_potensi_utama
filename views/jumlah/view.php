<?php

use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model app\models\Jumlah */
?>
<div class="jumlah-view">
 
    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            'prodi.nama_prodi',
            'tahunajaran.tahun_ajaran',
            'jumlah_mahasiswa',
            'jumlah_dosen',
        ],
    ]) ?>

</div>
