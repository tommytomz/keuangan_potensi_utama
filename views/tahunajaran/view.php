<?php

use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model app\models\TahunAjaran */
?>
<div class="tahun-ajaran-view">
 
    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            'tahun_ajaran',
        ],
    ]) ?>

</div>
