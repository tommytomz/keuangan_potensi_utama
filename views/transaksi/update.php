<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\models\Transaksi */
?>
<div class="transaksi-update">

    <?= $this->render('_formupdate', [
        'model' => $model,
        'idsubakun2' => $idsubakun2,
        'nominal' => $nominal,
    ]) ?>

</div>
