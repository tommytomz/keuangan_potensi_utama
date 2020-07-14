<?php

use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model app\models\Transaksi */
?>
<div class="transaksi-view">
 
    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            'id',
            'idsubakun',
            'idakundebet',
            'idakunkredit',
            'no_ref',
            [
                'attribute'=>'debet',
                'format' => ['decimal', 0],
            ],
            [
                'attribute'=>'kredit',
                'format' => ['decimal', 0],
            ],
            'keterangan:ntext',
            'tanggal',
        ],
    ]) ?>

</div>
