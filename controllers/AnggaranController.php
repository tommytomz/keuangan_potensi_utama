<?php

namespace app\controllers;

use Yii;
use app\models\Anggaran;
use app\models\AnggaranModel;
use app\models\AnggaranSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use \yii\web\Response;
use yii\helpers\Html;
use yii\web\UploadedFile;
use kartik\mpdf\Pdf;
use yii\helpers\ArrayHelper;
use app\models\Prodi;

/**
 * AnggaranController implements the CRUD actions for Anggaran model.
 */
class AnggaranController extends Controller
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
     * Lists all Anggaran models.
     * @return mixed
     */
    public function actionIndex()
    {    
        $searchModel = new AnggaranSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }


    /**
     * Displays a single Anggaran model.
     * @param integer $id
     * @return mixed
     */
    public function actionView($id)
    {   
        $request = Yii::$app->request;
        if($request->isAjax){
            Yii::$app->response->format = Response::FORMAT_JSON;
            return [
                    'title'=> "Detail Penggunaan Dana",
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
     * Creates a new Anggaran model.
     * For ajax request will return json object
     * and for non-ajax request if creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $request = Yii::$app->request;
        $model = new Anggaran(); 
        //$model6 = new AnggaranModel 

        if($request->isAjax){
            /*
            *   Process for ajax request
            */
            Yii::$app->response->format = Response::FORMAT_JSON;
            if($request->isGet){
                return [
                    'title'=> "Tambah Penggunaan Dana",
                    'content'=>$this->renderAjax('create', [
                        'model' => $model,
                    ]),
                    'footer'=> Html::button('Tutup',['class'=>'btn btn-default pull-left','data-dismiss'=>"modal"]).
                                Html::button('Simpan',['class'=>'btn btn-primary','type'=>"submit"]).
                                Html::a('Import Data',['import'],['class'=>'btn btn-success','role'=>'modal-remote'])
        
                ];         
            }else if($model->load($request->post())){
                
                foreach ($model->schedule as $key => $value) {
                    $model2 = new Anggaran();
                    $model2->idprodi        = Yii::$app->user->identity->idprodi;
                    $model2->kegiatan       = $value['kegiatan'];
                    $model2->jumlah         = $value['jumlah'];
                    $model2->idtahunajaran  = $_SESSION['idtahunajaran'];
                    $model2->save();
                    //echo $value['kegiatan'];
                }
                return [
                    'forceReload'=>'#crud-datatable-pjax',
                    'title'=> "Tambah Penggunaan Dana",
                    'content'=>'<span class="text-success">Tambah Penggunaan Dana Sukses</span>',
                    'footer'=> Html::button('Tutup',['class'=>'btn btn-default pull-left','data-dismiss'=>"modal"]).
                            Html::a('Tambah Lagi',['create'],['class'=>'btn btn-primary','role'=>'modal-remote'])
        
                ];         
            }else{           
                return [
                    'title'=> "Tambah Penggunaan Dana",
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

    public function actionImport(){
        $request = Yii::$app->request;
        $model = new Anggaran();  

        if($request->isAjax){
            /*
            *   Process for ajax request
            */
            Yii::$app->response->format = Response::FORMAT_JSON;
            if($request->isGet){
                return [
                    'title'=> "Tambah Penggunaan Dana",
                    'content'=>$this->renderAjax('_formimport', [
                        'model' => $model,
                    ]),
                    'footer'=> Html::button('Tutup',['class'=>'btn btn-default pull-left','data-dismiss'=>"modal"]).
                                Html::button('Simpan',['class'=>'btn btn-primary','type'=>"submit"])
                ];         
            }else if($model->load($request->post())){
                $file = UploadedFile::getInstance($model,'importfile');
                $filename = 'dataanggaran-'.Yii::$app->user->identity->idprodi.'.'.$file->extension;
                $path='uploads/'.$filename;
                $upload = $file->saveAs($path);
                
                $data = \moonland\phpexcel\Excel::import($path);
                $model2 = new Anggaran();
                
                foreach ($data as $key => $value) {
                    //echo $value['Jumlah'];
                    
                    $model2->idprodi = Yii::$app->user->identity->idprodi;
                    $model2->kegiatan = $value['Kegiatan'];
                    $model2->jumlah = $value['Jumlah'];
                    $model2->idtahunajaran = $_SESSION['idtahunajaran'];
                    $model2->save();
                }

                return [
                    'forceReload'=>'#crud-datatable-pjax',
                    'title'=> "Import Penggunaan Dana",
                    'content'=>'<span class="text-success">Tambah Pengeluaran Sukses</span>',
                    'footer'=> Html::button('Tutup',['class'=>'btn btn-default pull-left','data-dismiss'=>"modal"]).
                            Html::a('Tambah Lagi',['create'],['class'=>'btn btn-primary','role'=>'modal-remote'])
        
                ];         
            }else{           
                return [
                    'title'=> "Import Penggunaan Dana",
                    'content'=>$this->renderAjax('_formimport', [
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
                return $this->render('_formimport', [
                    'model' => $model,
                ]);
            }
        }
    }

    public function actionLaporan()
    {
        $request = Yii::$app->request;
        $model = new Anggaran();  

        if($request->isAjax){
            /*
            *   Process for ajax request
            */
            Yii::$app->response->format = Response::FORMAT_JSON;
            if($request->isGet){
                
                return [
                    'title'=> "Cetak Laporan Penggunaan Dana",
                    'content'=>$this->renderAjax('_formlaporan', [
                        'model' => $model,
                    ]),
                    'footer'=> Html::button('Tutup',['class'=>'btn btn-default pull-left','data-dismiss'=>"modal"]).

                        Html::button('Cetak',['class'=>'btn btn-warning','type'=>"submit"])
        
                ];         
            }else if($request->isPost && $model->load($request->post())){
                $prodi      = '';
                $fakultas   = '';
                if(Yii::$app->user->identity->idprodi==0){
                    $prodi      = $_POST['Anggaran']['idprodi'];
                    $fakultas   = $_POST['Anggaran']['idfakultas'];
                }else{
                    $prodi = Yii::$app->user->identity->idprodi;
                }

                return [
                    'forceReload'=>'#crud-datatable-pjax',
                    'title'=> "Laporan Penggunaan Dana",
                    'content'=> '<iframe src="'.Yii::$app->request->baseUrl.'?r=anggaran/cetaklaporan/&idfakultas='.$fakultas.'&idprodi='.$prodi.'&idtahunajaran='.$_POST['Anggaran']['idtahunajaran'].'"
style="width:100%; height:500px;" frameborder="0"></iframe>',
                    'footer'=> Html::button('Tutup',['class'=>'btn btn-default pull-left','data-dismiss'=>"modal"])
        
                ];         
                // Yii::$app->response->format = \yii\web\Response::FORMAT_RAW;
                // $headers = Yii::$app->response->headers;
                // $headers->add('Content-Type', 'application/pdf');
               // return $pdf->render();
            }else{           
                return [
                    'title'=> "Cetak Laporan Penggunaan Dana",
                    'content'=>$this->renderAjax('_formlaporan', [
                        'model' => $model,
                    ]),
                    'footer'=> Html::button('Tutup',['class'=>'btn btn-default pull-left','data-dismiss'=>"modal"]).
                                Html::button('Cetak',['class'=>'btn btn-warning','type'=>"submit"])
        
                ];         
            }
        }else{
            /*
            *   Process for non-ajax request
            */
            
            return $this->render('_formlaporan', [
                'model' => $model,
            ]);
            
        }
    }

    public function actionCetaklaporan()
    {
        $prodi='';
        $fakultas   = '';

        if(Yii::$app->user->identity->idprodi==0){
            $fakultas   = $_GET['idfakultas'];
            $prodi      = $_GET['idprodi'];
        }else{
            $prodi = Yii::$app->user->identity->idprodi;
        }

        $data = Anggaran::find()->joinWith('fakultas')->andFilterWhere([
            'fakultas.id' => $fakultas,
            'idprodi' => $prodi,
            'idtahunajaran' => $_GET['idtahunajaran'],
        ])->all();

        $content = $this->renderPartial('laporan', ['data'=>$data, 'idfakultas'=>$fakultas, 'idprodi'=>$prodi]);
       // print_r($data);
    // setup kartik\mpdf\Pdf component
        $pdf = new Pdf([
            // set to use core fonts only
            'mode' => Pdf::MODE_CORE, 
            // A4 paper format
            'format' => Pdf::FORMAT_A4, 
            // portrait orientation
            'orientation' => Pdf::ORIENT_LANDSCAPE, 
            // stream to browser inline
            'destination' => Pdf::DEST_BROWSER, 
            // your html content input
            'content' => $content,  
            // format content from your own css file if needed or use the
            // enhanced bootstrap css built by Krajee for mPDF formatting 
            'cssFile' => '@vendor/kartik-v/yii2-mpdf/assets/kv-mpdf-bootstrap.min.css',
            // any css to be embedded if required
            'cssInline' => '.tabel th{ padding:0 5px 0 5px; } .tabel td{ padding:0 5px 0 5px; }', 
             // set mPDF properties on the fly
            'options' => ['title' => 'Krajee Report Title'],
             // call mPDF methods on the fly
            // 'methods' => [ 
                // 'SetHeader'=>['Krajee Report Header'], 
                // 'SetFooter'=>['{PAGENO}'],
            // ]
        ]);

        return $pdf->render();
    }

    /**
     * Updates an existing Anggaran model.
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
                    'title'=> "Ubah Penggunaan Dana",
                    'content'=>$this->renderAjax('update', [
                        'model' => $model,
                    ]),
                    'footer'=> Html::button('Tutup',['class'=>'btn btn-default pull-left','data-dismiss'=>"modal"]).
                                Html::button('Simpan',['class'=>'btn btn-primary','type'=>"submit"])
                ];         
            }else if($model->load($request->post())){
                if(Yii::$app->user->identity->idprodi==0){
                    $model->status = $_POST['Anggaran']['status'];
                    
                }

                $model->save();

                return [
                    'forceReload'=>'#crud-datatable-pjax',
                    'title'=> "Penggunaan Dana",
                    'content'=>$this->renderAjax('view', [
                        'model' => $model,
                    ]),
                    'footer'=> Html::button('Tutup',['class'=>'btn btn-default pull-left','data-dismiss'=>"modal"]).
                            Html::a('Ubah',['update','id'=>$id],['class'=>'btn btn-primary','role'=>'modal-remote'])
                ];    
            }else{
                 return [
                    'title'=> "Ubah Penggunaan Dana",
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
     * Delete an existing Anggaran model.
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
     * Delete multiple existing Anggaran model.
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
     * Finds the Anggaran model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Anggaran the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Anggaran::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }

    public function actionPilihprodi($idfakultas)
    {
        $list = Prodi::find()->where(['idfakultas'=>$idfakultas])->orderBy('nama_prodi')->all();
        $data = ArrayHelper::map($list,'id','nama_prodi');
        //print_r($data);
        echo Html::tag('option','Pilih', array('value'=>''));
       
        foreach($data as $value=>$nama){
          echo Html::tag('option', $nama, array('value'=>$value));
        }
        
    }
}
