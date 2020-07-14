<?php
use yii\helpers\Html;
use yii\helpers\Url;
use app\models\Anggaran;
use mdm\admin\models\searchs\Assignment;

/* @var $this yii\web\View */

$this->title = 'Selamat Datang';

$data = Yii::$app->db->createCommand('
        select item_name from auth_assignment where user_id=:userid
    ',[':userid'=>Yii::$app->user->identity->id])->queryAll();
// print_r($dataprodi);
?>

<div class="site-index">
  <?php if(strtolower($data[0]['item_name'])=='rektor' || strtolower($data[0]['item_name'])=='dekan' || strtolower($data[0]['item_name'])=='yayasan'){?>
	<div class="box">
        <!-- <div class="box-header">
          <h3 class="box-title">Striped Full Width Table</h3>
        </div> -->
        <!-- /.box-header -->
        <div class="box-body no-padding">
          <table class="table table-striped">
            <tr>
              <th style="width: 10px">#</th>
              <th>Fakultas</th>
			        <th>Program Studi</th>
              <th style="width: 40px">Notifikasi</th>
            </tr>
            <?php 
            $i=1;
            foreach ($dataprodi as $key => $value) { 
            	$datajumlah = Anggaran::find()->select(['count(1) as jumlah'])
                ->where(['idprodi'=>$value->id, 'status'=>'Menunggu'])->one();
            	?>
				<tr>
					<td><?=$i?></td>
					<td><?=$value->fakultas[0]->nama_fakultas?></td>
					<td><?=$value->nama_prodi?></td>
					<td align="center"><?php
						if($datajumlah->jumlah>0){
							echo Html::a($datajumlah->jumlah, ['anggaran/index', 'AnggaranSearch[idfakultas]'=>$value->idfakultas,'AnggaranSearch[idprodi]'=>$value->id],['class'=>'badge bg-red','role'=>'modal-remote']);
						}
					?></td>
				</tr>
			<?php $i++; } ?>
          </table>
        </div>
        <!-- /.box-body -->
      </div>
      <!-- /.box -->
    </div>
  <?php }else{?>
    
  <?php } ?>
</div>
