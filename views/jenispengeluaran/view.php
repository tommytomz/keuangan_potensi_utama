<?php

use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model app\models\JenisPengeluaran */
?>
<div class="jenis-pengeluaran-view">
 
    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            'id',
            'jenis_pengeluaran',
        ],
    ]) ?>

</div>
