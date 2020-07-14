<?php

namespace app\controllers;

use Yii;
use yii\filters\AccessControl;
use yii\web\Controller;
use yii\web\Response;
use yii\filters\VerbFilter;
use app\models\LoginForm;
use app\models\ContactForm;
use app\models\User;
use app\models\Fakultas;
use app\models\Prodi;

class SiteController extends Controller
{
    /**
     * {@inheritdoc}
     */
    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::className(),
                'only' => ['logout'],
                'rules' => [
                    [
                        'actions' => ['logout'],
                        'allow' => true,
                        'roles' => ['@'],
                    ],
                ],
            ],
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'logout' => ['post'],
                ],
            ],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function actions()
    {
        return [
            'error' => [
                'class' => 'yii\web\ErrorAction',
            ],
            'captcha' => [
                'class' => 'yii\captcha\CaptchaAction',
                'fixedVerifyCode' => YII_ENV_TEST ? 'testme' : null,
            ],
        ];
    }

    /**
     * Displays homepage.
     *
     * @return string
     */
    public function actionIndex()
    {	
    
		if(empty(Yii::$app->user->identity->username)){
			return $this->redirect('?r=site/login');
		}else{
            $dataprodi = Prodi::find()->joinWith('fakultas')->all();
            // print_r($dataprodi);
			return $this->render('index', ['dataprodi'=>$dataprodi]);
		}
		
    }

    /**
     * Login action.
     *
     * @return Response|string
     */
    public function actionLogin()
    {
        if (!Yii::$app->user->isGuest) {
            return $this->goHome();
        }

        $model = new LoginForm();
        if ($model->load(Yii::$app->request->post()) && $model->login()) {
            return $this->goBack();
        }

        $model->password = '';
        return $this->render('login', [
            'model' => $model,
        ]);
    }

    /**
     * Logout action.
     *
     * @return Response
     */
    public function actionLogout()
    {	
	$model = new LoginForm();
		Yii::$app->user->logout();
        //return $this->render('login', ['model'=>$model]);
        return $this->redirect('?r=site/login');

		
    }

    /**
     * Displays contact page.
     *
     * @return Response|string
     */
    public function actionContact()
    {
        $model = new ContactForm();
        if ($model->load(Yii::$app->request->post()) && $model->contact(Yii::$app->params['adminEmail'])) {
            Yii::$app->session->setFlash('contactFormSubmitted');

            return $this->refresh();
        }
        return $this->render('contact', [
            'model' => $model,
        ]);
    }

    /**
     * Displays about page.
     *
     * @return string
     */
    public function actionAbout()
    {
        return $this->render('about');
    }

    public function actionKirimemail(){

        $vpass = "sqs123456";
        $model = User::findOne(['email' => $_GET['email']]);
        $model->generateAuthKey();
        $model->setPassword($vpass);
        $model->save();

        //$dataemail = User::findOne(['email' => $_GET['email']]);

        $kirim = Yii::$app->mailer->compose()
        ->setFrom('support_tapem@pematangsiantarkota.com')
        ->setTo($_GET['email'])
        ->setSubject('Reset Password')
        ->setTextBody('Plain text content')
        ->setHtmlBody('<b>Password Baru: '.$vpass.'</b>')
        ->send();

        if($kirim){
            $pesan = array('pesan'=>'Password berhasil direset, Silahkan cek email Anda');
        }else{
            $pesan = array('pesan'=>'Proses gagal');
        }
        //$pesan = array('pesan'=>$dataemail->password_hash);
        echo json_encode($pesan);
    }
}
