<?php

namespace app\controllers;

use yii\web\Controller;
use app\forms\CropboxForm;

class CropboxController extends Controller
{
    /**
     * @return mixed
     */
    public function actionIndex()
    {
        $form = new CropboxForm;
        return $this->render('index', [
            'form' => $form,
        ]);
    }
}
