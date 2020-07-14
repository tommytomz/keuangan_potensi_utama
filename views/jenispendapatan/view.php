<?php

use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model app\models\JenisPendapatan */
?>
<div class="jenis-pendapatan-view">
 
    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            'jenis_pendapatan',
            'kategori',
        ],
    ]) ?>

</div>
