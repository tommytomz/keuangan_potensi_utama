<?php

use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model app\models\Pejabat */
?>
<div class="pejabat-view">
 
    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            'nama_pejabat',
        ],
    ]) ?>

</div>
