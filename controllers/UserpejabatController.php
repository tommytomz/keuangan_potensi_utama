<?php

namespace app\controllers;

use Yii;
use app\models\UserPejabat;
use app\models\UserPejabatSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use \yii\web\Response;
use yii\helpers\Html;
use mdm\admin\components\UserStatus;
use yii\helpers\ArrayHelper;
use mdm\admin\models\Assignment;

/**
 * UserpejabatController implements the CRUD actions for UserPejabat model.
 */
class UserpejabatController extends Controller
{
    /**
     * @inheritdoc
     */
    public function behaviors()
    {
        return [
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'delete' => ['post'],
                    'bulk-delete' => ['post'],
                ],
            ],
        ];
    }

    /**
     * Lists all UserPejabat models.
     * @return mixed
     */
    public function actionIndex()
    {    
        $searchModel = new UserPejabatSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }


    /**
     * Displays a single UserPejabat model.
     * @param integer $id
     * @return mixed
     */
    public function actionView($id)
    {   
        $request = Yii::$app->request;
        if($request->isAjax){
            Yii::$app->response->format = Response::FORMAT_JSON;
            return [
                    'title'=> "Detail User Pejabat",
                    'content'=>$this->renderAjax('view', [
                        'model' => $this->findModel($id),
                    ]),
                    'footer'=> Html::button('Tutup',['class'=>'btn btn-default pull-left','data-dismiss'=>"modal"]).
                            Html::a('Ubah',['update','id'=>$id],['class'=>'btn btn-primary','role'=>'modal-remote'])
                ];    
        }else{
            return $this->render('view', [
                'model' => $this->findModel($id),
            ]);
        }
    }

    /**
     * Creates a new UserPejabat model.
     * For ajax request will return json object
     * and for non-ajax request if creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $request = Yii::$app->request;
        $model = new UserPejabat();  

        if($request->isAjax){
            /*
            *   Process for ajax request
            */
    
            //$model->username    = $_POST['UserPejabat']['username'];
            //$model->email       = $_POST['UserPejabat']['email'];
            
            Yii::$app->response->format = Response::FORMAT_JSON;
            if($request->isGet){
                return [
                    'title'=> "Tambah User Pejabat",
                    'content'=>$this->renderAjax('create', [
                        'model' => $model,
                    ]),
                    'footer'=> Html::button('Tutup',['class'=>'btn btn-default pull-left','data-dismiss'=>"modal"]).
                                Html::button('Simpan',['class'=>'btn btn-primary','type'=>"submit"])
        
                ];         
            }else if($model->load($request->post())){
                if($_POST['UserPejabat']['password_hash']==$_POST['UserPejabat']['ulangi_password']){
                    $model->username = $_POST['UserPejabat']['username'];
                    $prodi = 0;
                    if($_POST['UserPejabat']['idpejabat']==3 || $_POST['UserPejabat']['idpejabat']==7){
                        $prodi = $_POST['UserPejabat']['idprodi'];
                    }

                    $model->idprodi = $prodi;
                    $model->status = ArrayHelper::getValue(Yii::$app->params, 'user.defaultStatus', UserStatus::ACTIVE);
                    $model->setPassword($_POST['UserPejabat']['password_hash']);
                    $model->generateAuthKey();
                    //echo $model->idprodi;
                    if($model->save()){
                        $model = new Assignment($model->id);
                        $model->assign([$_POST['UserPejabat']['hakakses']]);

                        return [
                            'forceReload'=>'#crud-datatable-pjax',
                            'title'=> "Tambah User Pejabat",
                            'content'=>'<span class="text-success">Tambah User Pejabat Sukses</span>',
                            'footer'=> Html::button('Tutup',['class'=>'btn btn-default pull-left','data-dismiss'=>"modal"]).
                                    Html::a('Tambah Lagi',['create'],['class'=>'btn btn-primary','role'=>'modal-remote'])
                        ];  
                    }else{
                        return [
                            'title'=> "Tambah User Pejabat",
                            'content'=>$this->renderAjax('create', [
                                'model' => $model,
                            ]),
                            'footer'=> Html::button('Tutup',['class'=>'btn btn-default pull-left','data-dismiss'=>"modal"]).
                                        Html::button('Simpan',['class'=>'btn btn-primary','type'=>"submit"])
                
                        ];         
                    }       
                }else{
                     return [
                            'forceReload'=>'#crud-datatable-pjax',
                            'title'=> "Tambah User Pejabat",
                            'content'=>'<span class="text-alert">Password Tidak Sesuai</span>',
                            'footer'=> Html::button('Tutup',['class'=>'btn btn-default pull-left','data-dismiss'=>"modal"]).
                                    Html::a('Tambah Lagi',['create'],['class'=>'btn btn-primary','role'=>'modal-remote'])
                
                        ];  
                }
                
            }else{           
                return [
                    'title'=> "Tambah User Pejabat",
                    'content'=>$this->renderAjax('create', [
                        'model' => $model,
                    ]),
                    'footer'=> Html::button('Tutup',['class'=>'btn btn-default pull-left','data-dismiss'=>"modal"]).
                                Html::button('Simpan',['class'=>'btn btn-primary','type'=>"submit"])
        
                ];         
            }
        }else{
            /*
            *   Process for non-ajax request
            */
            if ($model->load($request->post()) && $model->save()) {
                return $this->redirect(['view', 'id' => $model->id]);
            } else {
                return $this->render('create', [
                    'model' => $model,
                ]);
            }
        }
       
    }

    /**
     * Updates an existing UserPejabat model.
     * For ajax request will return json object
     * and for non-ajax request if update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     */
    public function actionUpdate($id)
    {
        $request = Yii::$app->request;
        $model = $this->findModel($id);       

        if($request->isAjax){
            /*
            *   Process for ajax request
            */
            Yii::$app->response->format = Response::FORMAT_JSON;
            if($request->isGet){
                return [
                    'title'=> "Ubah User Pejabat",
                    'content'=>$this->renderAjax('update', [
                        'model' => $model,
                    ]),
                    'footer'=> Html::button('Tutup',['class'=>'btn btn-default pull-left','data-dismiss'=>"modal"]).
                                Html::button('Simpan',['class'=>'btn btn-primary','type'=>"submit"])
                ];         
            }else if($model->load($request->post())){
                if($_POST['UserPejabat']['password_hash']==$_POST['UserPejabat']['ulangi_password']){
                    $model->username = $_POST['UserPejabat']['username'];
                    $model->status = ArrayHelper::getValue(Yii::$app->params, 'user.defaultStatus', UserStatus::ACTIVE);
                    $model->setPassword($_POST['UserPejabat']['password_hash']);
                    $model->generateAuthKey();
                    if($model->save()){

                        return [
                            'forceReload'=>'#crud-datatable-pjax',
                            'title'=> "Tambah User Pejabat",
                            'content'=>'<span class="text-success">Ubah User Pejabat Sukses</span>',
                            'footer'=> Html::button('Tutup',['class'=>'btn btn-default pull-left','data-dismiss'=>"modal"])
                
                        ];  
                    }else{
                        return [
                            'title'=> "Ubah User Pejabat",
                            'content'=>$this->renderAjax('update', [
                                'model' => $model,
                            ]),
                            'footer'=> Html::button('Tutup',['class'=>'btn btn-default pull-left','data-dismiss'=>"modal"]).
                                         Html::a('Ubah',['update','id'=>$id],['class'=>'btn btn-primary','role'=>'modal-remote'])
                
                        ];         
                    }       
                }else{
                     return [
                            'forceReload'=>'#crud-datatable-pjax',
                            'title'=> "Tambah User Pejabat",
                            'content'=>'<span class="text-alert">Password Tidak Sesuai</span>',
                            'footer'=> Html::button('Tutup',['class'=>'btn btn-default pull-left','data-dismiss'=>"modal"]).
                                     Html::a('Ubah',['update','id'=>$id],['class'=>'btn btn-primary','role'=>'modal-remote'])
                
                        ];  
                }
            }else{
                 return [
                    'title'=> "Ubah UserPejabat",
                    'content'=>$this->renderAjax('update', [
                        'model' => $model,
                    ]),
                    'footer'=> Html::button('Tutup',['class'=>'btn btn-default pull-left','data-dismiss'=>"modal"]).
                                Html::button('Simpan',['class'=>'btn btn-primary','type'=>"submit"])
                ];        
            }
        }else{
            /*
            *   Process for non-ajax request
            */
            if ($model->load($request->post()) && $model->save()) {
                return $this->redirect(['view', 'id' => $model->id]);
            } else {
                return $this->render('update', [
                    'model' => $model,
                ]);
            }
        }
    }

    /**
     * Delete an existing UserPejabat model.
     * For ajax request will return json object
     * and for non-ajax request if deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     */
    public function actionDelete($id)
    {
        $request = Yii::$app->request;
        $this->findModel($id)->delete();

        if($request->isAjax){
            /*
            *   Process for ajax request
            */
            Yii::$app->response->format = Response::FORMAT_JSON;
            return ['forceClose'=>true,'forceReload'=>'#crud-datatable-pjax'];
        }else{
            /*
            *   Process for non-ajax request
            */
            return $this->redirect(['index']);
        }


    }

     /**
     * Delete multiple existing UserPejabat model.
     * For ajax request will return json object
     * and for non-ajax request if deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     */
    public function actionBulkDelete()
    {        
        $request = Yii::$app->request;
        $pks = explode(',', $request->post( 'pks' )); // Array or selected records primary keys
        foreach ( $pks as $pk ) {
            $model = $this->findModel($pk);
            $model->delete();
        }

        if($request->isAjax){
            /*
            *   Process for ajax request
            */
            Yii::$app->response->format = Response::FORMAT_JSON;
            return ['forceClose'=>true,'forceReload'=>'#crud-datatable-pjax'];
        }else{
            /*
            *   Process for non-ajax request
            */
            return $this->redirect(['index']);
        }
       
    }

    /**
     * Finds the UserPejabat model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return UserPejabat the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = UserPejabat::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
}
