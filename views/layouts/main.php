<?php
use yii\helpers\Html;

/* @var $this \yii\web\View */
/* @var $content string */

if (Yii::$app->controller->action->id === 'login') { 
/**
 * Do not use this code in your template. Remove it. 
 * Instead, use the code  $this->layout = '//main-login'; in your controller.
 */
    echo $this->render(
        'main-login',
        ['content' => $content]
    );
} else {

    if (class_exists('backend\assets\AppAsset')) {
        backend\assets\AppAsset::register($this);
    } else {
        app\assets\AppAsset::register($this);
    }

    dmstr\web\AdminLteAsset::register($this);

    $directoryAsset = Yii::$app->assetManager->getPublishedUrl('@vendor/almasaeed2010/adminlte/dist');
    ?>
    <?php $this->beginPage() ?>
    <!DOCTYPE html>
    <html lang="<?= Yii::$app->language ?>">
    <head>
        <meta charset="<?= Yii::$app->charset ?>"/>
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <?= Html::csrfMetaTags() ?>
        <title><?= Html::encode($this->title) ?></title>
        <?php $this->head() ?>
        <link rel="shortcut icon" href="<?=Yii::$app->request->baseUrl?>/images/logo_upu50.png">
        <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.4.1/jquery.min.js"></script>

        <style type="text/css">
            .skin-blue .main-header .logo{
                background-color: #201d53;
            }

            .skin-blue .main-header .navbar{
                background-color: #003399;
            }

            .sidebar{
                background-color: white;
            }

           .skin-blue .user-panel > .info, .skin-blue .user-panel > .info > a{
                color: #003399;
                font-weight: bold;
           }

           .skin-blue .sidebar a{
                color: #003399;
                /*font-weight: bold;*/
           }


           .skin-blue .sidebar-menu > li.active > a, .skin-blue .sidebar-menu > li.menu-open > a{
                background-color: #003399;
           }

           .skin-blue .sidebar-menu > li:hover > a{
              background-color: #f0b2b2;
              color: #003399;
           }

           .skin-blue .sidebar-menu .treeview-menu > li > a{
                color: #003399;
           }

           .skin-blue .sidebar-menu .treeview-menu > li > a:hover{
                background-color: #fff;
           }

           .skin-blue .sidebar-menu .treeview-menu > li.active > a, .skin-blue .sidebar-menu .treeview-menu > li > a:hover{
                color: #c60b0b;
           }

           .skin-blue .sidebar-menu > li > .treeview-menu{
                background-color: #ffeeef;
           }

           .skin-blue .wrapper, .skin-blue .main-sidebar, .skin-blue .left-side{
                background-color: white;
           }

           .skin-blue .sidebar-menu > li:hover > a, .skin-blue .sidebar-menu > li.active > a, .skin-blue .sidebar-menu > li.menu-open > a{
                font-weight: bold;
           }

           .panel-primary{
                border-color: white;
           }

           .panel-primary > .panel-heading{
                background-color: #003399;
                border-color: #003399;
           }

           .btn-primary{
                background-color: #003399;
                border-color: #003399;
           }

           .box.box-primary{
                border-top-color: #003399;
           }

        </style>

    </head>
    <body class="hold-transition skin-blue sidebar-mini">
    <?php $this->beginBody() ?>
    <div class="wrapper">

        <?= $this->render(
            'header.php',
            ['directoryAsset' => $directoryAsset]
        ) ?>

        <?= $this->render(
            'left.php',
            ['directoryAsset' => $directoryAsset]
        )
        ?>

        <?= $this->render(
            'content.php',
            ['content' => $content, 'directoryAsset' => $directoryAsset]
        ) ?>

    </div>

    <?php $this->endBody() ?>
    </body>
    </html>
    <?php $this->endPage() ?>
<?php } ?>
