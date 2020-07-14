<?php

use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model app\models\Prodi */
?>
<div class="prodi-view">
 
    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            'fakultas.nama_fakultas',
            'nama_prodi',
        ],
    ]) ?>

</div>
