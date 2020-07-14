<?php

use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model app\models\UserPejabat */
?>
<div class="user-pejabat-view">
 
    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            'pejabat.nama_pejabat',
            'nama',
            'username',
            // 'auth_key',
            // 'password_hash',
            // 'password_reset_token',
            'email:email',
            // 'status',
            // 'created_at',
            // 'updated_at',
            // 'verification_token',
        ],
    ]) ?>

</div>
