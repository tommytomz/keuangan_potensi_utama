<?php

use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model app\models\Fakultas */
?>
<div class="fakultas-view">
 
    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            'id',
            'nama_fakultas',
        ],
    ]) ?>

</div>
