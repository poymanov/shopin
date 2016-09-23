<?php

namespace common\models;

use Yii;
use yii\web\UploadedFile;

/**
 * This is the model class for table "brands".
 *
 * @property integer $id
 * @property string $name
 * @property string $image
 * @property string $href
 * @property string $title
 * @property string $alt
 * @property integer $sort
 * @property integer $status
 */
class Brand extends \yii\db\ActiveRecord
{

    const STATUS_ACTIVE = 'Active';
    const STATUS_DISABLED = 'Disabled';

    public $file;

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'brands';
    }

    public function beforeDelete() {

        $path = Yii::getAlias('@frontend/web') . $this->image;

        // Удаление файла, только если он существует физически
        if (file_exists($path)) {
            unlink($path);
        }

        return parent::beforeDelete();
    }

    public function afterSave($insert, $changedAttributes){
        parent::afterSave($insert, $changedAttributes);

        // Обновление кэша для frontend-виджета
        Yii::$app->cacheFrontend->delete('brands');
    }

    public function afterDelete()
    {
        parent::afterDelete();

        // Обновление кэша для frontend-виджета
        Yii::$app->cacheFrontend->delete('brands');
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['name'], 'required'],
            [['image', 'file'], 'required', 'on' => 'create'],
            [['sort'], 'integer'],
            [['status'], 'boolean'],
            [['name'], 'string', 'max' => 50],
            [['image', 'href', 'title', 'alt'], 'string', 'max' => 255],
            [['file'], 'file', 'extensions' => 'png, jpg'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'name' => 'Name',
            'image' => 'Image',
            'href' => 'Href',
            'file' => 'File',
            'title' => 'Title',
            'alt' => 'Alt',
            'sort' => 'Sort',
            'status' => 'Status'
        ];
    }
}
