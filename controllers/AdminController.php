<?php

namespace humhub\modules\codebox\controllers;

use Yii;
use yii\web\NotFoundHttpException;
use humhub\modules\admin\components\Controller;
use humhub\modules\codebox\models\ConfigureForm;

class AdminController extends Controller
{

    public function actionIndex()
    {
        $models = ConfigureForm::find()->all();

        return $this->render('index', ['models' => $models]);
    }

    public function actionCreate()
    {
        $model = new ConfigureForm();

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['index']);
        }

        return $this->renderAjax('create', ['model' => $model]);
    }

    /**
     * Updates an existing ticket.
     *
     * @param int $id
     * @return string|\yii\web\Response
     * @throws NotFoundHttpException
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['index']);
        }

        return $this->renderAjax('update', [
            'model' => $model,
        ]);
    }

    public function actionDelete($id)
    {
        $model = $this->findModel($id);
        $model->delete();

        return $this->redirect(['index']);
    }

    protected function findModel($id)
    {
        if (($model = ConfigureForm::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException(Yii::t('CodeboxModule.base', 'The requested entry does not exist.'));
    }
}