<?php
use yii\helpers\Html;
use app\models\Prodi;
/* @var $this \yii\web\View */
/* @var $content string */
$prodi=Prodi::find()->where(['id'=>Yii::$app->user->identity->idprodi])->one();

?>

<header class="main-header">
    
    <!-- <?= Html::a('<img src="'.Yii::$app->request->baseUrl.'/images/logo_upu100.png">', Yii::$app->homeUrl, ['style'=>'position:absolute;']) ?> -->

    <?= Html::a('UPU', Yii::$app->homeUrl, ['class' => 'logo']) ?>

    <nav class="navbar navbar-static-top" role="navigation">

        <a href="#" class="sidebar-toggle" data-toggle="push-menu" role="button">
            <span class="sr-only">Toggle navigation</span>
        </a>
        <span style="padding-top: 6px; position: absolute; font-size: 18pt; color: white;">
            <?php if(Yii::$app->user->identity->idprodi==0){ ?>
                UNIVERSITAS POTENSI UTAMA
            <?php } else if(Yii::$app->user->identity->idprodi==-1){ ?>
                Admin Aplikasi
            <?php } else { ?>
                Program Studi : <?=$prodi['nama_prodi'];?>
            <?php } ?>
        </span>
        <div class="navbar-custom-menu">

            <ul class="nav navbar-nav">

                <!-- Messages: style can be found in dropdown.less-->
                
                
                <!-- Tasks: style can be found in dropdown.less -->
                
                <!-- User Account: style can be found in dropdown.less -->

                <li class="dropdown user user-menu">
                    <a href="#" class="dropdown-toggle" data-toggle="dropdown">
                        <img src="<?=Yii::$app->request->baseUrl?>/images/no-person.jpg" class="user-image" alt="User Image"/>
                        <span class="hidden-xs"><?php if(!empty(Yii::$app->user->identity->username)){echo Yii::$app->user->identity->username;}?></span>
                    </a>
                    <ul class="dropdown-menu">
                        <!-- User image -->
                        <li class="user-header">
                            <img src="<?=Yii::$app->request->baseUrl?>/images/no-person.jpg" class="img-circle"
                                 alt="User Image"/>

                           
                        </li>
                        <!-- Menu Body -->
                        
                        <!-- Menu Footer-->
                        <li class="user-footer">
                            
                            <div class="pull-right">
                                <?= Html::a(
                                    'Logout',
                                    ['/site/logout'],
                                    ['data-method' => 'post', 'class' => 'btn btn-danger btn-flat']
                                ) ?>
                            </div>
                        </li>
                    </ul>
                </li>

                <!-- User Account: style can be found in dropdown.less -->
                <li>
                    <a href="#" data-toggle="control-sidebar"><i class="fa fa-gears"></i></a>
                </li>
            </ul>
        </div>
    </nav>
</header>
