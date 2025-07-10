<?php

namespace humhub\modules\codebox\controllers;

use Yii;
use yii\web\NotFoundHttpException;
use yii\web\Response;
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

    /**
     * Change code type for an existing codebox entry
     * 
     * @return Response
     * @throws NotFoundHttpException
     */
    public function actionChangeCodeType()
    {
        $request = Yii::$app->request;
        
        if (!$request->isPost) {
            throw new NotFoundHttpException('Invalid request method');
        }

        $id = $request->post('id');
        $codeType = $request->post('codeType');

        if (!$id || !$codeType) {
            Yii::$app->session->setFlash('error', 'Missing required parameters');
            return $this->redirect(['index']);
        }

        $model = $this->findModel($id);
        
        // Validate code type
        $validTypes = ['html', 'php', 'yii2', 'javascript', 'css'];
        if (!in_array($codeType, $validTypes)) {
            Yii::$app->session->setFlash('error', 'Invalid code type');
            return $this->redirect(['index']);
        }

        $model->codeType = $codeType;
        
        // Skip validation to avoid issues with changing code type
        if ($model->save(false)) {
            Yii::$app->session->setFlash('success', 'Code type changed successfully to ' . $codeType);
        } else {
            Yii::$app->session->setFlash('error', 'Failed to change code type: ' . implode(', ', $model->getFirstErrors()));
        }

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