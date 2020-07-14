<?php

use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model app\models\SetTahunAjaran */
?>
<div class="set-tahun-ajaran-view">
 
    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            'tahunajaran.tahun_ajaran',
        ],
    ]) ?>

</div>
