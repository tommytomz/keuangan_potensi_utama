<?php

use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model app\models\Akun */
?>
<div class="akun-view">
 
    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            'id',
            'nama_akun',
            //'nilai',
        ],
    ]) ?>

</div>
