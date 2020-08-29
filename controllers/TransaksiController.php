<?php

namespace app\controllers;

use Yii;
use app\models\Transaksi;
use app\models\TransaksiSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use \yii\web\Response;
use yii\helpers\Html;
use app\models\SubAkun;
use yii\helpers\ArrayHelper;

/**
 * TransaksiController implements the CRUD actions for Transaksi model.
 */
class TransaksiController extends Controller
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
     * Lists all Transaksi models.
     * @return mixed
     */
    public function actionIndex()
    {    
        $searchModel = new TransaksiSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    public function actionBukuBesar()
    {    
        $searchModel = new TransaksiSearch();
        $dataProvider = $searchModel->searchBukuBesar(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }


    /**
     * Displays a single Transaksi model.
     * @param integer $id
     * @return mixed
     */
    public function actionView($id)
    {   
        $request = Yii::$app->request;
        if($request->isAjax){
            Yii::$app->response->format = Response::FORMAT_JSON;
            return [
                    'title'=> "Detail Transaksi",
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
     * Creates a new Transaksi model.
     * For ajax request will return json object
     * and for non-ajax request if creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $request = Yii::$app->request;
        $model = new Transaksi();  
        $model->tanggal = date('d-m-Y');

        if($request->isAjax){
            /*
            *   Process for ajax request
            */
            Yii::$app->response->format = Response::FORMAT_JSON;
            if($request->isGet){
                return [
                    'title'=> "Tambah Transaksi",
                    'content'=>$this->renderAjax('create', [
                        'model' => $model,
                    ]),
                    'footer'=> Html::button('Tutup',['class'=>'btn btn-default pull-left','data-dismiss'=>"modal"]).
                                Html::button('Simpan',['class'=>'btn btn-primary','type'=>"submit"])
        
                ];         
            }else if($model->load($request->post())){
                
                if( empty($_POST['Transaksi']['idsubakun']) || 
                    empty($_POST['Transaksi']['idsubakun2']) || 
                    empty($_POST['Transaksi']['nominal']) || 
                    empty($_POST['Transaksi']['tanggal'])
                ){
                    if(empty($_POST['Transaksi']['idsubakun']) || empty($_POST['Transaksi']['idsubakun2'])){
                        $model->addError('idsubakun','Sub akun tidak boleh kosong');
                        $model->addError('idsubakun2','Sub akun tidak boleh kosong');
                    }

                    if(empty($_POST['Transaksi']['idsubakun'])){
                        $model->addError('nominal','Nominal tidak boleh kosong');
                    }

                    if(empty($_POST['Transaksi']['tanggal'])){
                        $model->addError('tanggal','Nominal tidak boleh kosong');
                    }
                    
                    return [
                            'title'=> "Tambah Transaksi",
                            'content'=>$this->renderAjax('create', [
                                'model' => $model,
                            ]),
                            'footer'=> Html::button('Close',['class'=>'btn btn-default pull-left','data-dismiss'=>"modal"]).
                                        Html::button('Save',['class'=>'btn btn-primary','type'=>"submit"])
                
                        ];
                }

                $berhasil = 0;
                $transaction = Yii::$app->db->beginTransaction();
                $noref = date('YmdHis');

                if($_POST['Transaksi']['debetkredit']=="debet"){
                    $model2 = new Transaksi();
                    $model2->idsubakun       = $_POST['Transaksi']['idsubakun'];
                    $model2->idakundebet     = $_POST['Transaksi']['idsubakun'];
                    $model2->ke_akun         = $_POST['Transaksi']['idsubakun2'];
                    $model2->no_ref          = $noref;
                    $model2->kredit          = $_POST['Transaksi']['nominal'];
                    $model2->keterangan      = $_POST['Transaksi']['keterangan'];
                    $model2->tanggal         = $this->convertTanggal($_POST['Transaksi']['tanggal']);
                    // $model2->tanggal         = date('Y-m-d');
                    if($model2->save()){

                        $model2 = new Transaksi();

                        $model2->idsubakun       = $_POST['Transaksi']['idsubakun2'];
                        $model2->idakunkredit    = $_POST['Transaksi']['idsubakun2'];
                        $model2->ke_akun         = $_POST['Transaksi']['idsubakun'];
                        $model2->no_ref          = $noref;
                        $model2->debet           = $_POST['Transaksi']['nominal'];
                        $model2->keterangan      = $_POST['Transaksi']['keterangan'];
                        //$model->tanggal         = $_POST['Transaksi']['tanggal'];
                        $model2->tanggal         = $this->convertTanggal($_POST['Transaksi']['tanggal']);
                        if($model2->save()){
                            $transaction->commit();
                            $berhasil = 1;
                        }
                        
                    }else{
                        $transaction->rollBack();
                        return [
                            'title'=> "Tambah Transaksi",
                            'content'=>$this->renderAjax('create', [
                                'model' => $model,
                            ]),
                            'footer'=> Html::button('Close',['class'=>'btn btn-default pull-left','data-dismiss'=>"modal"]).
                                        Html::button('Save',['class'=>'btn btn-primary','type'=>"submit"])
                
                        ];
                    }
                    
                }else{

                    $model2 = new Transaksi();

                    $model2->idsubakun       = $_POST['Transaksi']['idsubakun'];
                    $model2->idakundebet     = $_POST['Transaksi']['idsubakun'];
                    $model2->ke_akun         = $_POST['Transaksi']['idsubakun2'];
                    $model2->no_ref          = $noref;
                    $model2->debet           = $_POST['Transaksi']['nominal'];
                    $model2->keterangan      = $_POST['Transaksi']['keterangan'];
                    $model2->tanggal         = $this->convertTanggal($_POST['Transaksi']['tanggal']);
                    // $model2->tanggal         = date('Y-m-d');
                    if($model2->save()){
                        $model2 = new Transaksi();
                        $model2->idsubakun       = $_POST['Transaksi']['idsubakun2'];
                        $model2->idakunkredit    = $_POST['Transaksi']['idsubakun2'];
                        $model2->ke_akun         = $_POST['Transaksi']['idsubakun'];
                        $model2->no_ref          = $noref;
                        $model2->kredit          = $_POST['Transaksi']['nominal'];
                        $model2->keterangan      = $_POST['Transaksi']['keterangan'];
                        $model2->tanggal         = $this->convertTanggal($_POST['Transaksi']['tanggal']);
                        // $model2->tanggal         = date('Y-m-d');
                        if($model2->save()){
                            $transaction->commit();
                            $berhasil = 1;
                        }
                    }else{
                        $transaction->rollBack();
                        return [
                            'title'=> "Tambah Transaksi",
                            'content'=>$this->renderAjax('create', [
                                'model' => $model,
                            ]),
                            'footer'=> Html::button('Close',['class'=>'btn btn-default pull-left','data-dismiss'=>"modal"]).
                                        Html::button('Save',['class'=>'btn btn-primary','type'=>"submit"])
                
                        ];
                    }

                }
                if($berhasil==1){
                    return [
                        'forceReload'=>'#crud-datatable-pjax',
                        'title'=> "Tambah Transaksi",
                        'content'=>'<span class="text-success">Tambah Transaksi Sukses</span>',
                        'footer'=> Html::button('Tutup',['class'=>'btn btn-default pull-left','data-dismiss'=>"modal"]).
                                Html::a('Tambah Lagi',['create'],['class'=>'btn btn-primary','role'=>'modal-remote'])
                    ];        
                }else{
                    return [
                        'title'=> "Tambah Transaksi",
                        'content'=>$this->renderAjax('create', [
                            'model' => $model,
                        ]),
                        'footer'=> Html::button('Close',['class'=>'btn btn-default pull-left','data-dismiss'=>"modal"]).
                                    Html::button('Save',['class'=>'btn btn-primary','type'=>"submit"])
            
                    ];  
                }
            }else{           
                return [
                    'title'=> "Tambah Transaksi",
                    'content'=>$this->renderAjax('create', [
                        'model' => $model,
                    ]),
                    'footer'=> Html::button('Close',['class'=>'btn btn-default pull-left','data-dismiss'=>"modal"]).
                                Html::button('Save',['class'=>'btn btn-primary','type'=>"submit"])
        
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
     * Updates an existing Transaksi model.
     * For ajax request will return json object
     * and for non-ajax request if update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     */
    public function actionUpdate($id)
    {
        $request = Yii::$app->request;
        //$model = $this->findModel($id);       
        $model = Transaksi::find()
                            ->where(['no_ref'=>$id])
                            ->orderBy(['id'=>SORT_ASC])
                            ->one();

        $model2 = Transaksi::find()
                            ->where(['no_ref'=>$id])
                            ->orderBy(['id'=>SORT_DESC])
                            ->one();

        $model->tanggal = Yii::$app->formatter->asDate($model->tanggal);
        //print_r($model2);
        if($request->isAjax){
            /*
            *   Process for ajax request
            */
            Yii::$app->response->format = Response::FORMAT_JSON;
            if($request->isGet){
                return [
                    'title'=> "Ubah Transaksi",
                    'content'=>$this->renderAjax('update', [
                        'model' => $model,
                        'idsubakun2' => $model2->idsubakun,
                        'nominal' => $model->kredit,
                    ]),
                    'footer'=> Html::button('Tutup',['class'=>'btn btn-default pull-left','data-dismiss'=>"modal"]).
                                Html::button('Simpan',['class'=>'btn btn-primary','type'=>"submit"])
                ];         
            }else if($model->load($request->post())){
                $berhasil = 0;
                //echo $_POST['w1-disp'];
                
                $transaction = Yii::$app->db->beginTransaction();

                Yii::$app->db->createCommand()
                    ->delete("transaksi", ["no_ref" => $id])
                    ->execute();
                
                //echo $_POST['Transaksi']['debetkredit'];
                if($_POST['Transaksi']['debetkredit']=="debet"){
                    $modeltransaksi = new Transaksi();

                    $modeltransaksi->idsubakun       = $_POST['Transaksi']['idsubakun'];
                    $modeltransaksi->idakundebet     = $_POST['Transaksi']['idsubakun'];
                    $modeltransaksi->ke_akun         = $_POST['Transaksi']['idsubakun2'];
                    $modeltransaksi->no_ref          = $id;
                    $modeltransaksi->kredit          = str_replace(",", "", $_POST['w1-disp']);
                    $modeltransaksi->keterangan      = $_POST['Transaksi']['keterangan'];
                    $modeltransaksi->tanggal         = $this->convertTanggal($_POST['Transaksi']['tanggal']);
                    //$modeltransaksi->tanggal         = date('Y-m-d');
                    if($modeltransaksi->save()){
                        $modeltransaksi = new Transaksi();

                        $modeltransaksi->idsubakun       = $_POST['Transaksi']['idsubakun2'];
                        $modeltransaksi->idakunkredit    = $_POST['Transaksi']['idsubakun2'];
                        $modeltransaksi->ke_akun         = $_POST['Transaksi']['idsubakun'];
                        $modeltransaksi->no_ref          = $id;
                        $modeltransaksi->debet           = str_replace(",", "", $_POST['w1-disp']);
                        $modeltransaksi->keterangan      = $_POST['Transaksi']['keterangan'];
                        $modeltransaksi->tanggal         = $this->convertTanggal($_POST['Transaksi']['tanggal']);
                        //$modeltransaksi->tanggal         = date('Y-m-d');
                        if($modeltransaksi->save()){
                            $transaction->commit();
                            $berhasil = 1;
                        }
                        
                    }else{
                        $transaction->rollBack();
                        return [
                            'title'=> "Tambah Transaksi",
                            'content'=>$this->renderAjax('update', [
                                 'model' => $model,
                                'idsubakun2' => $model2->idsubakun,
                                'nominal' => $model->kredit,
                            ]),
                            'footer'=> Html::button('Close',['class'=>'btn btn-default pull-left','data-dismiss'=>"modal"]).
                                        Html::button('Save',['class'=>'btn btn-primary','type'=>"submit"])
                
                        ];
                    }
                    
                }else{

                    $modeltransaksi = new Transaksi();

                    $modeltransaksi->idsubakun       = $_POST['Transaksi']['idsubakun'];
                    $modeltransaksi->idakundebet     = $_POST['Transaksi']['idsubakun'];
                    $modeltransaksi->no_ref          = $id;
                    $modeltransaksi->debet           = str_replace(",", "", $_POST['w1-disp']);
                    $modeltransaksi->keterangan      = $_POST['Transaksi']['keterangan'];
                    $modeltransaksi->tanggal         = $this->convertTanggal($_POST['Transaksi']['tanggal']);
                    //$modeltransaksi->tanggal         = date('Y-m-d');
                    if($modeltransaksi->save()){
                        $modeltransaksi = new Transaksi();
                        $modeltransaksi->idsubakun       = $_POST['Transaksi']['idsubakun2'];
                        $modeltransaksi->idakunkredit    = $_POST['Transaksi']['idsubakun2'];
                        $modeltransaksi->no_ref          = $id;
                        $modeltransaksi->kredit          = str_replace(",", "", $_POST['w1-disp']);
                        $modeltransaksi->keterangan      = $_POST['Transaksi']['keterangan'];
                        $modeltransaksi->tanggal         = $this->convertTanggal($_POST['Transaksi']['tanggal']);
                        //$modeltransaksi->tanggal         = date('Y-m-d');
                        if($modeltransaksi->save()){
                            $transaction->commit();
                            $berhasil = 1;
                        }
                    }else{
                        $transaction->rollBack();
                        return [
                            'title'=> "Tambah Transaksi",
                            'content'=>$this->renderAjax('update', [
                                 'model' => $model,
                                'idsubakun2' => $model2->idsubakun,
                                'nominal' => $model->kredit,
                            ]),
                            'footer'=> Html::button('Close',['class'=>'btn btn-default pull-left','data-dismiss'=>"modal"]).
                                        Html::button('Save',['class'=>'btn btn-primary','type'=>"submit"])
                
                        ];
                    }

                }

                //echo "coba";

                if($berhasil==1){
                    return [
                        'forceReload'=>'#crud-datatable-pjax',
                        'title'=> "Ubah Transaksi",
                        'content'=>'<span class="text-success">Ubah Transaksi Sukses</span>',
                        'footer'=> Html::button('Tutup',['class'=>'btn btn-default pull-left','data-dismiss'=>"modal"])
                    ];        
                }else{
                    return [
                        'title'=> "Tambah Transaksi",
                        'content'=>$this->renderAjax('create', [
                            'model' => $model,
                        ]),
                        'footer'=> Html::button('Tutup',['class'=>'btn btn-default pull-left','data-dismiss'=>"modal"]).
                                    Html::button('Simpan',['class'=>'btn btn-primary','type'=>"submit"])
            
                    ];  
                }
                return [
                    'forceReload'=>'#crud-datatable-pjax',
                    'title'=> "Transaksi",
                    'content'=>$this->renderAjax('view', [
                        'model' => $model,
                        //'model2' => $model2,
                    ]),
                    'footer'=> Html::button('Tutup',['class'=>'btn btn-default pull-left','data-dismiss'=>"modal"]).
                            Html::a('Ubah',['update','id'=>$id],['class'=>'btn btn-primary','role'=>'modal-remote'])
                ];    
            }else{
                 return [
                    'title'=> "Ubah Transaksi",
                    'content'=>$this->renderAjax('update', [
                        'model' => $model,
                        'idsubakun2' => $model2->idsubakun,
                        'nominal' => $model->kredit,
                    ]),
                    'footer'=> Html::button('Tutup',['class'=>'btn btn-default pull-left','data-dismiss'=>"modal"]).
                                Html::button('Simpan',['class'=>'btn btn-primary','type'=>"submit"])
                ];        
            }
        }else{
            /*
            *   Process for non-ajax request
            */
            // if ($model->load($request->post()) && $model->save()) {
            //     return $this->redirect(['view', 'id' => $model->id]);
            // } else {
                return $this->render('update', [
                    'model' => $model,
                    'idsubakun2' => $model2->idsubakun,
                    'nominal' => $model->kredit,
                ]);
            //}
        }
    }

    /**
     * Delete an existing Transaksi model.
     * For ajax request will return json object
     * and for non-ajax request if deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     */
    public function actionDelete($id)
    {
        $request = Yii::$app->request;
    
        for($i=0; $i<2; $i++){
            $model = Transaksi::find()->where(['no_ref'=>$id])->one();
            $model->delete();
        }
        //$this->findModelNoref($id)->delete();

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
     * Delete multiple existing Transaksi model.
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
     * Finds the Transaksi model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Transaksi the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Transaksi::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }

    public function actionPilihsubakun($idakun)
    {
        $list = SubAkun::find()->where(['idakun'=>$idakun])->orderBy('nama_sub_akun')->all();
        $data = ArrayHelper::map($list,'id','nama_sub_akun');
        //print_r($data);
        echo Html::tag('option','Pilih', array('value'=>''));
       
        foreach($data as $value=>$nama){
          echo Html::tag('option', $nama, array('value'=>$value));
        }
        
    }

    private function convertTanggal($tanggal){
        $hasil='';
        $data = explode("-", $tanggal);
        $hasil = $data[2]."-".$data[1]."-".$data[0];
        return $hasil;
    }
}
