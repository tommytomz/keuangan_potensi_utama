<?php

use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model app\models\SubAkun */
?>
<div class="sub-akun-view">
 
    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            'akun.nama_akun',
            'kode_akun',
            'kategoriakun.nama_kategori',
            'nama_sub_akun',
      //       [
		    //     'attribute'=>'debet',
		    //     'format' => ['decimal', 0],
		    //     'hAlign' => 'right',
		    // ],
		    // [
		    //     'attribute'=>'kredit',
		    //     'format' => ['decimal', 0],
		    //     'hAlign' => 'right',
		    // ],
        ],
    ]) ?>

</div>
