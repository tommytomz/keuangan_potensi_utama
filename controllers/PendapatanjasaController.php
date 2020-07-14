<?php

namespace app\controllers;

use Yii;
use app\models\PendapatanJasa;
use app\models\PendapatanJasaSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use \yii\web\Response;
use yii\helpers\Html;
use yii\web\UploadedFile;
use kartik\mpdf\Pdf;
use yii\helpers\ArrayHelper;
use app\models\Jumlah;
use app\models\JumlahSearch;
use app\models\ProdiSearch;
use app\models\Prodi;

/**
 * PendapatanController implements the CRUD actions for Pendapatan model.
 */
class PendapatanjasaController extends Controller
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
     * Lists all Pendapatan models.
     * @return mixed
     */
    public function actionIndex()
    {    
        $searchModel = new PendapatanJasaSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);
        //print_r(Yii::$app->request->queryParams['PendapatanSearch']['idfakultas']);
        $idfakultas = '';
        $idprodi    = '';

        if(isset(Yii::$app->request->queryParams['PendapatanJasaSearch']['idprodi'])){
            $idprodi = Yii::$app->request->queryParams['PendapatanJasaSearch']['idprodi'];
        }

        if(isset(Yii::$app->request->queryParams['PendapatanJasaSearch']['idfakultas'])){
            $idfakultas    = Yii::$app->request->queryParams['PendapatanJasaSearch']['idfakultas'];
        }

        $datamahasiswa = PendapatanJasa::find()
                    ->joinWith('jumlah')
                    ->joinWith('fakultas')
                    ->joinWith('jenispendapatan')
                    ->select("sum(pendapatan.jumlah) as pendapatan_mahasiswa, sum(pendapatan.jumlah) / jumlah.jumlah_mahasiswa as nilai_akhir_mahasiswa")
                    ->andFilterWhere(
                        [
                            'pendapatan.idtahunajaran'  => $_SESSION['idtahunajaran'],
                            'pendapatan.idprodi'        => $idprodi,
                            'fakultas.id'               => $idfakultas,
                            'jenis_pendapatan.kategori' => 'Mahasiswa'
                        ]   
                    )
                    ->one();

        $datadosen = PendapatanJasa::find()
                    ->joinWith('jumlah')
                    ->joinWith('fakultas')
                    ->joinWith('jenispendapatan')
                    ->select('sum(pendapatan.jumlah) as pendapatan_dosen, sum(pendapatan.jumlah) / jumlah.jumlah_dosen as nilai_akhir_dosen')
                    ->andFilterWhere(
                        [
                            'pendapatan.idtahunajaran'  => $_SESSION['idtahunajaran'],
                            'pendapatan.idprodi'        => $idprodi,
                            'fakultas.id'               => $idfakultas,
                            'jenis_pendapatan.kategori' => 'Dosen'
                        ]   
                    )
                    ->one();

        $datalain = PendapatanJasa::find()
                    ->joinWith('jumlah')
                    ->joinWith('fakultas')
                    ->joinWith('jenispendapatan')
                    ->select('sum(pendapatan.jumlah) as pendapatan_lain, sum(pendapatan.jumlah) / 1 as nilai_akhir_lain')
                    ->andFilterWhere(
                        [
                            'pendapatan.idtahunajaran'  => $_SESSION['idtahunajaran'],
                            'pendapatan.idprodi'        => $idprodi,
                            'fakultas.id'               => $idfakultas,
                            'jenis_pendapatan.kategori' => 'Lain-lain'
                        ]   
                    )
                    ->one();
        //print_r($datalain);

        return $this->render('index', [
            'pendapatanmahasiswa'   => $datamahasiswa->pendapatan_mahasiswa,
            'nilaiakhirmahasiswa'   => $datamahasiswa->nilai_akhir_mahasiswa,
            'pendapatandosen'       => $datadosen->pendapatan_dosen,
            'nilaiakhirdosen'       => $datadosen->nilai_akhir_dosen,
            'pendapatanlain'        => $datalain->pendapatan_lain,
            'nilaiakhirlain'        => $datalain->nilai_akhir_lain,
            'searchModel'           => $searchModel,
            'dataProvider'          => $dataProvider,
        ]);
    }


    /**
     * Displays a single Pendapatan model.
     * @param integer $id
     * @return mixed
     */
    public function actionView($id)
    {   
        $request = Yii::$app->request;
        if($request->isAjax){
            Yii::$app->response->format = Response::FORMAT_JSON;
            $button = Html::a('Ubah',['update','id'=>$id],['class'=>'btn btn-primary','role'=>'modal-remote']);

            if(Yii::$app->user->identity->idprodi==0){
                $button ="";
            }
            return [
                    'title'=> "Detail Pendapatan Jasa",
                    'content'=>$this->renderAjax('view', [
                        'model' => $this->findModel($id),
                    ]),
                    'footer'=> Html::button('Tutup',['class'=>'btn btn-default pull-left','data-dismiss'=>"modal"]).$button
                ];    
        }else{
            return $this->render('view', [
                'model' => $this->findModel($id),
            ]);
        }
    }

    /**
     * Creates a new Pendapatan model.
     * For ajax request will return json object
     * and for non-ajax request if creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $request = Yii::$app->request;
        $model = new PendapatanJasa();  
        $searchModel = new PendapatanJasaSearch();

        if($request->isAjax){
            /*
            *   Process for ajax request
            */
            Yii::$app->response->format = Response::FORMAT_JSON;
            if($request->isGet){
                return [
                    'title'=> "Tambah Pendapatan Jasa",
                    'content'=>$this->renderAjax('create', [
                        'model' => $model,
                    ]),
                    'footer'=> Html::button('Tutup',['class'=>'btn btn-default pull-left','data-dismiss'=>"modal"]).
                                Html::button('Simpan',['class'=>'btn btn-primary','type'=>"submit"])
                                // Html::a('Import Data',['import'],['class'=>'btn btn-success','role'=>'modal-remote'])
        
                ];         
            }else if($request->isPost && $model->load($request->post())){
                //print_r($request->post());
                //$model->schedule = \yii\helpers\Json::encode($model->schedule);

                //print_r($model->schedule);
                foreach ($model->schedule as $key => $value) {
                    $model2 = new PendapatanJasa();
                    $model2->idprodi            = 1;
                    $model2->idjenispendapatan  = 6;
                    $model2->jumlah             = $value['jumlah'];
                    $model2->idtahunajaran      = $_SESSION['idtahunajaran'];
                    $model2->save();
                    //echo $model->jumlah;
                }
                //$model->save();
                return [
                    'forceReload'=>'#crud-datatable-pjax',
                    'title'=> "Tambah Pendapatan",
                    'content'=>'<span class="text-success">Tambah Pendapatan Jasa Sukses</span>',
                    'footer'=> Html::button('Tutup',['class'=>'btn btn-default pull-left','data-dismiss'=>"modal"]).
                            Html::a('Tambah Lagi',['create'],['class'=>'btn btn-primary','role'=>'modal-remote'])
        
                ];         
            }else{           
                return [
                    'title'=> "Tambah Pendapatan Jasa",
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
        $model = new PendapatanJasa();  

        if($request->isAjax){
            /*
            *   Process for ajax request
            */
            Yii::$app->response->format = Response::FORMAT_JSON;
            if($request->isGet){
                return [
                    'title'=> "Tambah Pendapatan",
                    'content'=>$this->renderAjax('_formimport', [
                        'model' => $model,
                    ]),
                    'footer'=> Html::button('Tutup',['class'=>'btn btn-default pull-left','data-dismiss'=>"modal"]).
                                Html::button('Simpan',['class'=>'btn btn-primary','type'=>"submit"])
                ];         
            }else if($model->load($request->post())){

                $dataallprodi = Prodi::find()->all();

                $file = UploadedFile::getInstance($model,'importfile');
                $filename = 'datapendapatan-'.Yii::$app->user->identity->idprodi.'.'.$file->extension;
                $path='uploads/'.$filename;
                $upload = $file->saveAs($path);
                
                $data = \moonland\phpexcel\Excel::import($path, [
                  'setFirstRecordAsKeys' => false,
                  'setIndexSheetByName' => true,
                ]);

                //echo $_POST['Pendapatan']['idjenispendapatan'];
                //print_r($data[9]['B']);
                $searchModel = new PendapatanSearch();
                $dataprodi = array();
                $totaluangprodi = array();

                foreach ($data as $key => $value) {
                    if($key>8){
                        $dataprodi[$searchModel->searchProdi(ucwords($value['E']))][] = str_replace('.', '', $value['J']);
                    }
                    

                    //
                    //$dataprodi = array('idprodi'=>$searchModel->searchProdi(ucwords($value['E'])), 'total'=>str_replace(".", "", $value['J']));
                    // echo ucwords($value['PRODI']);
                    
                }

                $ceksimpan = 0;
                foreach ($dataallprodi as $key => $value) {
                        $model2 = new Pendapatan();
                        $model2->idprodi            = $value['id'];
                        $model2->idjenispendapatan  = $_POST['Pendapatan']['idjenispendapatan'];
                        $model2->jumlah             = array_sum($dataprodi[$value['id']]);
                        $model2->idtahunajaran      = $_SESSION['idtahunajaran'];
                        if($model2->save()){
                            $ceksimpan = 1;
                        }
                    }
                


                // for($i=0; $i<count($totaluangprodi); $i++){
                    
                // }
                //print_r($totaluangprodi);
                if($ceksimpan==1){
                    return [
                        'forceReload'=>'#crud-datatable-pjax',
                        'title'=> "Tambah Pendapatan",
                        'content'=>'<span class="text-success">Tambah Pendapatan Sukses</span>',
                        'footer'=> Html::button('Tutup',['class'=>'btn btn-default pull-left','data-dismiss'=>"modal"]).
                                Html::a('Tambah Lagi',['create'],['class'=>'btn btn-primary','role'=>'modal-remote'])
            
                    ];        
                }else{
                    return [
                        'title'=> "Tambah Pendapatan",
                        'content'=>$this->renderAjax('_formimport', [
                            'model' => $model,
                        ]),
                        'footer'=> Html::button('Tutup',['class'=>'btn btn-default pull-left','data-dismiss'=>"modal"]).
                                    Html::button('Simpan',['class'=>'btn btn-primary','type'=>"submit"])
            
                    ];         
                }
                 
            }else{           
                return [
                    'title'=> "Tambah Pendapatan",
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
        $model = new PendapatanJasa();  

        if($request->isAjax){
            /*
            *   Process for ajax request
            */
            Yii::$app->response->format = Response::FORMAT_JSON;
            if($request->isGet){
                
                return [
                    'title'=> "Cetak Laporan Pendapatan",
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
                    $prodi      = $_POST['Pendapatan']['idprodi'];
                    $fakultas   = $_POST['Pendapatan']['idfakultas'];
                }else{
                    $prodi = Yii::$app->user->identity->idprodi;
                }
                
                return [
                    'forceReload'=>'#crud-datatable-pjax',
                    'title'=> "Laporan Pendapatan",
                    'content'=> '<iframe src="'.Yii::$app->request->baseUrl.'?r=pendapatan/cetaklaporan/&idtahunajaran='.$_POST['Pendapatan']['idtahunajaran'].'&idfakultas='.$fakultas.'&idprodi='.$prodi.'"
style="width:100%; height:500px;" frameborder="0"></iframe>',
                    'footer'=> Html::button('Tutup',['class'=>'btn btn-default pull-left','data-dismiss'=>"modal"])
        
                ];         
                // Yii::$app->response->format = \yii\web\Response::FORMAT_RAW;
                // $headers = Yii::$app->response->headers;
                // $headers->add('Content-Type', 'application/pdf');
               // return $pdf->render();
            }else{           
                return [
                    'title'=> "Cetak Laporan Pendapatan",
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
        $idfakultas = '';
        $idprodi    = '';

        if(!empty($_GET['idprodi'])){
            $idprodi = $_GET['idprodi'];
        }

        if(!empty($_GET['idfakultas'])){
            $idfakultas = $_GET['idfakultas'];
        }

        $data = Pendapatan::find()
            ->joinWith('jumlah')
            ->joinWith('fakultas')
            ->joinWith('jenispendapatan')
            ->andFilterWhere([
            //'idprodi'=>Yii::$app->user->identity->idprodi, 
            'pendapatan.idprodi'        => $idprodi,
            'fakultas.id'               => $idfakultas,
            'pendapatan.idtahunajaran'  => $_GET['idtahunajaran']
        ])->all();

        

        $datamahasiswa = Pendapatan::find()
                    ->joinWith('jumlah')
                    ->joinWith('fakultas')
                    ->joinWith('jenispendapatan')
                    ->select("sum(pendapatan.jumlah) as pendapatan_mahasiswa, sum(pendapatan.jumlah) / jumlah.jumlah_mahasiswa as nilai_akhir_mahasiswa")
                    ->andFilterWhere(
                        [
                            'pendapatan.idtahunajaran'  => $_SESSION['idtahunajaran'],
                            'pendapatan.idprodi'        => $idprodi,
                            'fakultas.id'               => $idfakultas,
                            'jenis_pendapatan.kategori' => 'Mahasiswa'
                        ]   
                    )
                    ->one();

        $datadosen = Pendapatan::find()
                    ->joinWith('jumlah')
                    ->joinWith('fakultas')
                    ->joinWith('jenispendapatan')
                    ->select('sum(pendapatan.jumlah) as pendapatan_dosen, sum(pendapatan.jumlah) / jumlah.jumlah_dosen as nilai_akhir_dosen')
                    ->andFilterWhere(
                        [
                            'pendapatan.idtahunajaran'  => $_SESSION['idtahunajaran'],
                            'pendapatan.idprodi'        => $idprodi,
                            'fakultas.id'               => $idfakultas,
                            'jenis_pendapatan.kategori' => 'Dosen'
                        ]   
                    )
                    ->one();

        $datalain = Pendapatan::find()
                    ->joinWith('jumlah')
                    ->joinWith('fakultas')
                    ->joinWith('jenispendapatan')
                    ->select('sum(pendapatan.jumlah) as pendapatan_lain, sum(pendapatan.jumlah) / 1 as nilai_akhir_lain')
                    ->andFilterWhere(
                        [
                            'pendapatan.idtahunajaran'  => $_SESSION['idtahunajaran'],
                            'pendapatan.idprodi'        => $idprodi,
                            'fakultas.id'               => $idfakultas,
                            'jenis_pendapatan.kategori' => 'Lain-lain'
                        ]   
                    )
                    ->one();
        

        $content = $this->renderPartial('laporan', [
            'pendapatanmahasiswa'   => $datamahasiswa->pendapatan_mahasiswa,
            'nilaiakhirmahasiswa'   => $datamahasiswa->nilai_akhir_mahasiswa,
            'pendapatandosen'       => $datadosen->pendapatan_dosen,
            'nilaiakhirdosen'       => $datadosen->nilai_akhir_dosen,
            'pendapatanlain'        => $datalain->pendapatan_lain,
            'nilaiakhirlain'        => $datalain->nilai_akhir_lain,
            'data'                  => $data,
            'idprodi'               => $idprodi,
        ]);
        
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
            'cssInline' => '.tabel th{ padding:0 5px 0 8px; } .tabel td{ padding:0 5px 0 8px; }', 
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
     * Updates an existing Pendapatan model.
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
                    'title'=> "Ubah Pendapatan Jasa",
                    'content'=>$this->renderAjax('update', [
                        'model' => $model,
                    ]),
                    'footer'=> Html::button('Tutup',['class'=>'btn btn-default pull-left','data-dismiss'=>"modal"]).
                                Html::button('Simpan',['class'=>'btn btn-primary','type'=>"submit"])
                ];         
            }else if($model->load($request->post()) && $model->save()){
                return [
                    'forceReload'=>'#crud-datatable-pjax',
                    'title'=> "Pendapatan",
                    'content'=>$this->renderAjax('view', [
                        'model' => $model,
                    ]),
                    'footer'=> Html::button('Tutup',['class'=>'btn btn-default pull-left','data-dismiss'=>"modal"]).
                            Html::a('Ubah',['update','id'=>$id],['class'=>'btn btn-primary','role'=>'modal-remote'])
                ];    
            }else{
                 return [
                    'title'=> "Ubah Pendapatan Jasa",
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
     * Delete an existing Pendapatan model.
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
     * Delete multiple existing Pendapatan model.
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
     * Finds the Pendapatan model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Pendapatan the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = PendapatanJasa::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }

}
