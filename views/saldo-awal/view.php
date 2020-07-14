<?php

use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model app\models\SaldoAwal */
?>
<div class="saldo-awal-view">
 
    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            'id',
            'idsubakun',
            [
                'attribute'=>'debet',
                'format' => ['decimal', 0],
            ],
            [
                'attribute'=>'kredit',
                'format' => ['decimal', 0],
            ],
            'tanggal',
        ],
    ]) ?>

</div>
