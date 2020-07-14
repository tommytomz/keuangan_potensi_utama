<aside class="main-sidebar">

    <section class="sidebar">

        <!-- Sidebar user panel -->
        <div class="user-panel">
            <div class="pull-left image">
                <img src="<?= Yii::$app->request->baseUrl ?>/images/logo_upu200.png"  alt="User Image"/>
            </div>
            <div class="pull-left info">
                <p><?php if(!empty(Yii::$app->user->identity->username)){echo Yii::$app->user->identity->username;}?></p>

                <a href="#"><i class="fa fa-circle text-success"></i> Online</a>
            </div>
        </div>

<?php 
$callback = function ($menu) {
return ['label' => $menu['name'], 'url' => [$menu['route']], 'icon' => $menu['data'], 'items' => $menu['children']];
};
?>


<?php
use mdm\admin\components\MenuHelper;
if(!empty(Yii::$app->user->identity->id))
{
$items = MenuHelper::getAssignedMenu(Yii::$app->user->identity->id, null, $callback, true);
}else{
$items = array(); 
}
?>

<?= dmstr\widgets\Menu::widget(
            [   'options' => ['class' => 'sidebar-menu tree', 'data-widget'=> 'tree'],
                'items' => $items,
            ]
        ) ?>


    </section>

</aside>
