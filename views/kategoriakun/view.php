<?php

use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model app\models\KategoriAkun */
?>
<div class="kategori-akun-view">
 
    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            'akun.nama_akun',
            'nama_kategori',
        ],
    ]) ?>

</div>
