<?php

namespace app\controllers;

use Yii;
use app\models\Pengeluaran;
use app\models\PengeluaranSearch;
use app\models\Prodi;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use \yii\web\Response;
use yii\helpers\Html;
use yii\web\UploadedFile;
use kartik\mpdf\Pdf;
use app\models\Transaksi;
use app\models\SubAkun;
use app\base\Model;

/**
 * PengeluaranController implements the CRUD actions for Pengeluaran model.
 */
class PengeluaranController extends Controller
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
     * Lists all Pengeluaran models.
     * @return mixed
     */
    public function actionIndex()
    {    
        $searchModel = new PengeluaranSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    public function actionLaporanpengaluaran()
    {    
        $searchModel = new PengeluaranSearch();
        $dataProvider = $searchModel->searchLaporan(Yii::$app->request->queryParams);
        //print_r($dataProvider);
        return $this->render('laporanpengeluaran', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }


    /**
     * Displays a single Pengeluaran model.
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
                    'title'=> "Detail Laporan Pertanggung Jawaban",
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
     * Creates a new Pengeluaran model.
     * For ajax request will return json object
     * and for non-ajax request if creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $request = Yii::$app->request;
        $model = new Pengeluaran();  
        $modelsPengeluaran = [new Pengeluaran];

        if($request->isAjax){
            // $modelsAddress = Model::createMultiple(Address::classname());
            // Model::loadMultiple($modelsAddress, Yii::$app->request->post());

            /*
            *   Process for ajax request
            */
            Yii::$app->response->format = Response::FORMAT_JSON;

            if($request->isGet){
                return [
                    'title'=> "Tambah Laporan Pengeluaran",
                    'content'=>$this->renderAjax('create', [
                        'model' => $model,
                    ]),
                    'footer'=> Html::button('Tutup',['class'=>'btn btn-default pull-left','data-dismiss'=>"modal"]).
                                Html::button('Simpan',['class'=>'btn btn-primary','type'=>"submit"]).
                                Html::a('Import Data',['import'],['class'=>'btn btn-success','role'=>'modal-remote'])
        
                ];         
            }else if($model->load($request->post())){
                $berhasil = 0;
                $transaction = Yii::$app->db->beginTransaction();
                $noref = date('Ymdhis');

                foreach ($model->schedule as $key => $value) {
                    $model2 = new Pengeluaran();
                    //$model2->idprodi = Yii::$app->user->identity->idprodi;
                    $model2->idprodi            = $_POST['Pengeluaran']['idprodi'];
                    $model2->idsubakun          = $value['idsubakun'];
                    $model2->no_ref             = $noref;
                    $model2->jumlah             = $value['jumlah'];
                    $model2->idtahunajaran      = $_SESSION['idtahunajaran'];
                    $model2->tanggal            = $_POST['Pengeluaran']['tanggal'];
                    $model2->save();

                    $modeltransaksi = new Transaksi();
                    $modeltransaksi->idsubakun       = $value['idsubakun'];
                    $modeltransaksi->idakunkredit    = $value['idsubakun'];
                    $modeltransaksi->ke_akun         = $_POST['Pengeluaran']['kredit'];
                    $modeltransaksi->no_ref          = $noref;
                    $modeltransaksi->debet           = $value['jumlah'];
                    $modeltransaksi->keterangan      = $value['keterangan'];
                    //$model->tanggal         = $_POST['Transaksi']['tanggal'];
                    $modeltransaksi->tanggal         = $_POST['Pengeluaran']['tanggal'];
                    if($modeltransaksi->save()){
                        $modeltransaksi = new Transaksi();

                        $modeltransaksi->idsubakun       = $_POST['Pengeluaran']['kredit'];
                        $modeltransaksi->idakundebet     = $_POST['Pengeluaran']['kredit'];
                        $modeltransaksi->ke_akun         = $value['idsubakun'];
                        $modeltransaksi->no_ref          = $noref;
                        $modeltransaksi->kredit          = $value['jumlah'];
                        $modeltransaksi->keterangan      = $value['keterangan'];
                        //$model->tanggal         = $_POST['Transaksi']['tanggal'];
                        $modeltransaksi->tanggal         = $_POST['Pengeluaran']['tanggal'];
                        if($modeltransaksi->save()){
                            $berhasil = 1;
                        }
                        
                    }
                    //echo $_SESSION['idtahunajaran'];
                }

                if($berhasil==1){
                    $transaction->commit();
                    return [
                        'forceReload'=>'#crud-datatable-pjax',
                        'title'=> "Tambah Laporan Pengeluaran",
                        'content'=>'<span class="text-success">Tambah Laporan Pengeluaran Sukses</span>',
                        'footer'=> Html::button('Tutup',['class'=>'btn btn-default pull-left','data-dismiss'=>"modal"]).
                                Html::a('Tambah Lagi',['create'],['class'=>'btn btn-primary','role'=>'modal-remote'])
            
                    ];         
                }else{
                    $transaction->rollBack();
                    return [
                        'title'=> "Tambah Laporan Pengeluaran",
                        'content'=>$this->renderAjax('create', [
                            'model' => $model,
                        ]),
                        'footer'=> Html::button('Tutup',['class'=>'btn btn-default pull-left','data-dismiss'=>"modal"]).
                                    Html::button('Simpan',['class'=>'btn btn-primary','type'=>"submit"])
            
                    ];         
                }
                
            }else{           
                return [
                    'title'=> "Tambah Laporan Pengeluaran",
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

    public function actionImport(){
        $request = Yii::$app->request;
        $model = new Pengeluaran();  
        $datapengeluaran = new PengeluaranSearch();  

        if($request->isAjax){
            /*
            *   Process for ajax request
            */
            Yii::$app->response->format = Response::FORMAT_JSON;
            if($request->isGet){
                return [
                    'title'=> "Tambah Laporan Pengeluaran",
                    'content'=>$this->renderAjax('_formimport', [
                        'model' => $model,
                    ]),
                    'footer'=> Html::button('Tutup',['class'=>'btn btn-default pull-left','data-dismiss'=>"modal"]).
                                Html::button('Simpan',['class'=>'btn btn-primary','type'=>"submit"])
                ];         
            }else if($model->load($request->post())){
                $file = UploadedFile::getInstance($model,'importfile');
                $filename = 'datapengeluaran-'.Yii::$app->user->identity->idprodi.'.'.$file->extension;
                $path='uploads/'.$filename;
                $upload = $file->saveAs($path);
                
                $data = \moonland\phpexcel\Excel::import($path, [
                  'setFirstRecordAsKeys' => false,
                  'setIndexSheetByName' => true,
                ]);
                //print_r($data[1]['AA']);
                //echo $data['A'];
                $ceksimpan = 0;
                $berhasil = 0;

                for($i=1; $i<=count(current($data)); $i++){
                     $idsubakun = $datapengeluaran->searchSubAkun(ucwords(strtolower($data[1][$this->number_to_alpha($i)])));
                    for($j=2; $j<=count($data); $j++){
                        if($idsubakun!=0){
                            $tanggal = explode("/", $data[$j][$this->number_to_alpha(2)]);
                            $vtanggal = $tanggal[2]."-".$tanggal[0]."-".$tanggal[1];
                           //echo $vtanggal;
                            $modelpengeluaran                 = new Pengeluaran();
                            $modelpengeluaran->idprodi        = $_POST['Pengeluaran']['idprodi'];
                            $modelpengeluaran->idsubakun      = $idsubakun;
                            $modelpengeluaran->jumlah         = $data[$j][$this->number_to_alpha($i)];
                            $modelpengeluaran->idtahunajaran  = $_SESSION['idtahunajaran'];
                            $modelpengeluaran->tanggal        = $vtanggal;
                            if($modelpengeluaran->save()){
                                $ceksimpan = 1;
                                if($ceksimpan==1){
                                    $transaction = Yii::$app->db->beginTransaction();
                                    $noref = date('Ymdhis');

                                    $modeltransaksi = new Transaksi();
                                    $modeltransaksi->idsubakun       = $_POST['Pengeluaran']['kredit'];
                                    $modeltransaksi->idakundebet     = $_POST['Pengeluaran']['kredit'];
                                    $modeltransaksi->ke_akun         = $idsubakun;
                                    $modeltransaksi->no_ref          = $noref;
                                    $modeltransaksi->kredit          = $data[$j][$this->number_to_alpha($i)];
                                    $modeltransaksi->keterangan      = 'Pengeluaran ke '.$data[1][$this->number_to_alpha($i)];
                                    $modeltransaksi->tanggal         = $vtanggal;
                                    
                                    if($modeltransaksi->save()){
                                        $modeltransaksi = new Transaksi();

                                        $modeltransaksi->idsubakun       = $idsubakun;
                                        $modeltransaksi->idakunkredit    = $idsubakun;
                                        $modeltransaksi->ke_akun         = $_POST['Pengeluaran']['kredit'];
                                        $modeltransaksi->no_ref          = $noref;
                                        $modeltransaksi->debet           = $data[$j][$this->number_to_alpha($i)];
                                        $modeltransaksi->keterangan      = 'Pengeluaran ke '.$data[1][$this->number_to_alpha($i)];
                                        $modeltransaksi->tanggal         = $vtanggal;
                                        if($modeltransaksi->save()){
                                            $transaction->commit();
                                            $berhasil = 1;
                                        }
                                    }
                                }
                            }
                        }
                    }
                }


                // $model2 = new Pengeluaran();
                // foreach ($data as $key => $value) {
                //     //echo $value['Jumlah'];
                    
                //     $model2->idprodi = Yii::$app->user->identity->idprodi;
                //     $model2->kegiatan = $value['Kegiatan'];
                //     $model2->jumlah = $value['Jumlah'];
                //     $model2->idtahunajaran = $_SESSION['idtahunajaran'];
                //     $model2->save();
                // }

                return [
                    'forceReload'=>'#crud-datatable-pjax',
                    'title'=> "Tambah Laporan Pertanggung Jawaban",
                    'content'=>'<span class="text-success">Tambah Pengeluaran Sukses</span>',
                    'footer'=> Html::button('Tutup',['class'=>'btn btn-default pull-left','data-dismiss'=>"modal"]).
                            Html::a('Tambah Lagi',['create'],['class'=>'btn btn-primary','role'=>'modal-remote'])
        
                ];         
            }else{           
                return [
                    'title'=> "Tambah Laporan Pertanggung Jawaban",
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
        $model = new Pengeluaran();  

        if($request->isAjax){
            /*
            *   Process for ajax request
            */
            Yii::$app->response->format = Response::FORMAT_JSON;
            if($request->isGet){
                
                return [
                    'title'=> "Cetak Laporan Pertanggung Jawaban",
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
                    $prodi      = $_POST['Pengeluaran']['idprodi'];
                    $fakultas   = $_POST['Pengeluaran']['idfakultas'];
                }else{
                    $prodi = Yii::$app->user->identity->idprodi;
                }

                return [
                    'forceReload'=>'#crud-datatable-pjax',
                    'title'=> "Laporan Pertanggung Jawaban",
                    'content'=> '<iframe src="'.Yii::$app->request->baseUrl.'?r=pengeluaran/cetaklaporan/&idfakultas='.$fakultas.'&idprodi='.$prodi.'&idtahunajaran='.$_POST['Pengeluaran']['idtahunajaran'].'"
style="width:100%; height:500px;" frameborder="0"></iframe>',
                    'footer'=> Html::button('Tutup',['class'=>'btn btn-default pull-left','data-dismiss'=>"modal"])
        
                ];         
                // Yii::$app->response->format = \yii\web\Response::FORMAT_RAW;
                // $headers = Yii::$app->response->headers;
                // $headers->add('Content-Type', 'application/pdf');
               // return $pdf->render();
            }else{           
                return [
                    'title'=> "Cetak Laporan Pertanggung Jawaban",
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

    public function actionLaporanlpj()
    {
        $request = Yii::$app->request;
        $model = new Pengeluaran();  

        if($request->isAjax){
            /*
            *   Process for ajax request
            */
            Yii::$app->response->format = Response::FORMAT_JSON;
            if($request->isGet){
                
                return [
                    'title'=> "Cetak Laporan Pertanggung Jawaban",
                    'content'=>$this->renderAjax('_formlaporanpengeluaran', [
                        'model' => $model,
                    ]),
                    'footer'=> Html::button('Tutup',['class'=>'btn btn-default pull-left','data-dismiss'=>"modal"]).

                        Html::button('Cetak',['class'=>'btn btn-warning','type'=>"submit"])
        
                ];         
            }else if($request->isPost && $model->load($request->post())){
                $prodi      = '';
                $fakultas   = '';
                if(Yii::$app->user->identity->idprodi==0){
                    $prodi      = $_POST['Pengeluaran']['idprodi'];
                    $fakultas   = $_POST['Pengeluaran']['idfakultas'];
                }else{
                    $prodi = Yii::$app->user->identity->idprodi;
                }

                return [
                    'forceReload'=>'#crud-datatable-pjax',
                    'title'=> "Laporan Pertanggung Jawaban",
                    'content'=> '<iframe src="'.Yii::$app->request->baseUrl.'?r=pengeluaran/cetaklaporanpengeluaran/&idfakultas='.$fakultas.'&idprodi='.$prodi.'&idtahunajaran='.$_POST['Pengeluaran']['idtahunajaran'].'"
style="width:100%; height:500px;" frameborder="0"></iframe>',
                    'footer'=> Html::button('Tutup',['class'=>'btn btn-default pull-left','data-dismiss'=>"modal"])
        
                ];         
                // Yii::$app->response->format = \yii\web\Response::FORMAT_RAW;
                // $headers = Yii::$app->response->headers;
                // $headers->add('Content-Type', 'application/pdf');
               // return $pdf->render();
            }else{           
                return [
                    'title'=> "Cetak Laporan Pertanggung Jawaban",
                    'content'=>$this->renderAjax('_formlaporanpengeluaran', [
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
            
            return $this->render('_formlaporanpengeluaran', [
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

        $data = Pengeluaran::find()->joinWith('fakultas')->andFilterWhere([
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

    public function actionCetaklaporanpengeluaran()
    {
        $prodi='';
        $fakultas   = '';

        if(Yii::$app->user->identity->idprodi==0){
            $fakultas   = $_GET['idfakultas'];
            $prodi      = $_GET['idprodi'];
        }else{
            $prodi = Yii::$app->user->identity->idprodi;
        }

        $querypengeluaran = Pengeluaran::find()
            ->select([
                'idprodi, sum(jumlah) as jumlah'
            ])
            ->groupBy(['idprodi']);

        $data = Prodi::find()
            ->select(['
                        
                        fakultas.nama_fakultas, 
                        nama_prodi, 
                        sum(pendapatan.jumlah) as pendapatan,
                        COALESCE(pengeluaran.jumlah, 0) as pengeluaran,
                        pendapatan.jumlah - COALESCE(pengeluaran.jumlah, 0) as jumlah'])
            ->joinWith('fakultas')
            ->joinWith('pendapatan')
            ->leftJoin(['pengeluaran'=>$querypengeluaran],'prodi.id = pengeluaran.idprodi')
            ->where(['idfakultas'=>$fakultas])
            ->groupBy(['fakultas.nama_fakultas', 'nama_prodi'])
            ->all();

        $content = $this->renderPartial('laporanlpj', ['data'=>$data, 'idfakultas'=>$fakultas, 'idprodi'=>$prodi]);
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
     * Updates an existing Pengeluaran model.
     * For ajax request will return json object
     * and for non-ajax request if update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     */
    public function actionUpdate($id)
    {
        $request = Yii::$app->request;
        $model = $this->findModel($id);       
        $model->idfakultas = $model->fakultas->id;
        $idsubakun = $model->idsubakun;
        $berhasil = 0;

        if($request->isAjax){
            /*
            *   Process for ajax request
            */
            Yii::$app->response->format = Response::FORMAT_JSON;
            if($request->isGet){
                return [
                    'title'=> "Ubah Laporan Pengeluaran",
                    'content'=>$this->renderAjax('update', [
                        'model' => $model,
                    ]),
                    'footer'=> Html::button('Tutup',['class'=>'btn btn-default pull-left','data-dismiss'=>"modal"]).
                                Html::button('Simpan',['class'=>'btn btn-primary','type'=>"submit"])
                ];         
            }else if($model->load($request->post()) && $model->save()){

                $modeltransaksi = Transaksi::find()->where(['idsubakun'=>$idsubakun, 'no_ref'=>$model->no_ref])->one();

                $modeltransaksi->idsubakun  = $model->idsubakun;
                $modeltransaksi->debet      = $model->jumlah;
                if($modeltransaksi->save()){

                    $modeltransaksi = Transaksi::find()->where(['ke_akun'=>$idsubakun,'no_ref'=>$model->no_ref])->one();
                    $modeltransaksi->kredit     = $model->jumlah;
                    if($modeltransaksi->save()){
                        $berhasil=1;
                    }
                }

                if($berhasil==1){
                    return [
                        'forceReload'=>'#crud-datatable-pjax',
                        'title'=> "Laporan Pengeluaran",
                        'content'=>$this->renderAjax('view', [
                            'model' => $model,
                        ]),
                        'footer'=> Html::button('Tutup',['class'=>'btn btn-default pull-left','data-dismiss'=>"modal"]).
                                Html::a('Ubah',['update','id'=>$id],['class'=>'btn btn-primary','role'=>'modal-remote'])
                    ];    
                }else{
                    return [
                        'title'=> "Ubah Laporan Pengeluaran",
                        'content'=>$this->renderAjax('update', [
                            'model' => $model,
                        ]),
                        'footer'=> Html::button('Tutup',['class'=>'btn btn-default pull-left','data-dismiss'=>"modal"]).
                                    Html::button('Simpan',['class'=>'btn btn-primary','type'=>"submit"])
                    ];        
                }
                
            }else{
                 return [
                    'title'=> "Ubah Laporan Pengeluaran",
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
     * Delete an existing Pengeluaran model.
     * For ajax request will return json object
     * and for non-ajax request if deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     */
    public function actionDelete($id)
    {
        $request = Yii::$app->request;
        $modelpengeluaran    = Pengeluaran::find()->where(['id'=>$id])->one();
        $modeltransaksi     = Transaksi::find()
            ->where(['no_ref'=>$modelpengeluaran->no_ref, 'idsubakun'=>$modelpengeluaran->idsubakun])
            ->one();
        $modeltransaksi->delete();
        $modeltransaksi     = Transaksi::find()
            ->where(['no_ref'=>$modelpengeluaran->no_ref, 'ke_akun'=>$modelpengeluaran->idsubakun])
            ->one();
        $modeltransaksi->delete();
        
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
     * Delete multiple existing Pengeluaran model.
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
     * Finds the Pengeluaran model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Pengeluaran the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Pengeluaran::findOne($id)) !== null) {
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

    protected function number_to_alpha($number){
        $number = intval($number);
        if($number <= 0){
            return '';
        }
        $alphabet = '';
        while($number !=0){
            $p = ($number-1) % 26;
            $number = intval(($number - $p)/26);
            $alphabet = chr(65+$p).$alphabet;
        }
        return $alphabet;
    }
}
