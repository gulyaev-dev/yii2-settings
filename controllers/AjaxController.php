<?php
namespace xti\settings\controllers;

use Yii;
use xti\settings\models\Setting;
use yii\web\Controller;
use yii\filters\VerbFilter;

class AjaxController extends \backend\controllers\BaseAccess
{
    public function actionUpdateField()
    {
        $id = Yii::$app->request->post('pk');
        if(NULL !== ($model = Setting::findOne($id))) {
            if ($model->load([
                    $model->formName() => [
                        Yii::$app->request->post('name') => Yii::$app->request->post('value')
                    ]]
            )) {
                if ($model->save(true)) {
                    return json_encode([
                        'is_success' => 1,
                        'data' => [
                            'name' => Yii::$app->request->post('name'),
                            'value' => $model->{Yii::$app->request->post('name')}
                        ]
                    ]);
                } else {
                    return json_encode(['is_success' => 0, 'errors' => $model->errors]);
                }
            } else {
                return json_encode(['is_success' => 0, 'errors' => 'Undefined property name']);
            }
        }
    }
}
