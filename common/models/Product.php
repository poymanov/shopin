<?php

namespace common\models;

use Yii;
use php_rutils\RUtils;
use common\models\ProductsCategories;
use common\models\Category;
use yii\helpers\ArrayHelper;
use yii\web\UploadedFile;
use common\models\ProductImage;

/**
 * This is the model class for table "products".
 *
 * @property integer $id
 * @property string $name
 * @property string $slug
 * @property string $price
 * @property string $preview_text
 * @property string $full_description
 */
class Product extends \yii\db\ActiveRecord
{
    public $category;
    public $images;
    public $loadedImages;
    public $deleteImages = [];

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'products';
    }

    public function beforeSave($insert)
    {
        if (parent::beforeSave($insert)) {

            // Запись имени URL для ЧПУ для нового товара

            // Проверка уникальности записываемого ЧПУ

            $slug = RUtils::translit()->slugify($this->name);

            $model = Product::find()->where(['slug' => $slug]);
            
            // если происходит обновление товара, то не проверяем slug у текущего товара
            
            if (!$insert) {
                $model = $model->andWhere(['not', ['id' => $this->id]]);
            }

            $model = $model->all();

            if ($model) {
                $this->addError('slug', 'Dublicate slug - ' . $slug);
                return false;
            }

            $this->slug = $slug;
            return true;

        } else {
            return false;
        }
    }


    public function afterSave($insert, $changedAttributes){
        parent::afterSave($insert, $changedAttributes);

        // Поиск существующей записи для категории или запись новой
        $productsCategories = ProductsCategories::find()->where(['product_id' => $this->id])->one();

        if (!$productsCategories) {
            $productsCategories = new ProductsCategories();
        }

        $productsCategories->category_id = $this->category;
        $productsCategories->product_id = $this->id;
        $productsCategories->save();

        // Сохранение изображений товара
        $images = UploadedFile::getInstances($this, 'images');


//        print_r($this->loadedImages);
//        exit;

        if ($images) {

            $path = '/storage/products/';
            $dir = Yii::getAlias('@frontend/web' . $path);
            $this->createDirectory($dir);

            foreach ($images as $image) {

                if (!$image->tempName) {
                    continue;
                }

                // Получение префикса для уникализации файла
                $prefix = Yii::$app->getSecurity()->generateRandomString(5);

                // Запись уникального имени для нового файла
                $fileName = $prefix . '_product_' . $this->id . '.' . $image->extension;
                $image->saveAs($dir . $fileName);

                // Запись в БД ссылок на изображения
                $productImage = new ProductImage();
                $productImage->product_id = $this->id;
                $productImage->path = $path . $fileName;
                $productImage->save();

            }
        }

        // Запись свойства для основного изображения

        if ($this->loadedImages) {
            // Находим предыдущее главное изображение, если оно было и изменяем его состояние
            $productImage = ProductImage::find()->where(['product_id' => $this->id, 'main' => 1])->one();

            if ($productImage) {
                $productImage->main = null;
                $productImage->save();
            }

            $productImage = ProductImage::findOne($this->loadedImages);

            if ($productImage) {
                $productImage->main = 1;
                $productImage->save();
            }
        }

        // Удаление изображений
        if ($this->deleteImages) {
            $deleteImages = ProductImage::find()->where(['id' => $this->deleteImages])->all();

            // Физически удаляем файлы изображения

            if ($deleteImages) {
                foreach ($deleteImages as $image) {
                    $path = Yii::getAlias('@frontend/web' . $image->path);

                    if (file_exists($path)) {
                        unlink($path);
                    }
                }
            }

            // Удаляем записи об изображениях в БД
            ProductImage::deleteAll(['id' => $this->deleteImages]);
        }

    }


    public function beforeDelete()
    {
        if (parent::beforeDelete()) {

            $images = ProductImage::find()->where(['product_id' => $this->id])->all();

            // Удаление файлов изображений физически
            foreach ($images as $image) {
                $path = Yii::getAlias('@frontend/web' . $image->path);

                if (file_exists($path)) {
                    unlink($path);
                }
            }

            return true;
        } else {
            return false;
        }
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['preview_text', 'full_description'], 'string'],
            [['name', 'price'], 'string', 'max' => 255],
            ['category', 'integer'],
            [['preview_text', 'full_description', 'name', 'price', 'category'], 'required'],
            [['images'], 'file', 'extensions' => 'png, jpg', 'maxFiles' => '10'],
            [['loadedImages', 'deleteImages'], 'safe']
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
            'slug' => 'Slug',
            'price' => 'Price',
            'preview_text' => 'Preview Text',
            'category' => 'Category',
            'full_description' => 'Full Description',
            'images' => 'Images',
        ];
    }

    public function getAllCategories()
    {
        return $this->hasMany(ProductsCategories::className(), ['product_id' => 'id']);
    }

    public function getProductImages()
    {
        return $this->hasMany(ProductImage::className(), ['product_id' => 'id']);
    }

    public function getMainCategory()
    {
        return ProductsCategories::find()->where(['product_id' => $this->id])->one();
    }

    public function getMainCategoryId()
    {
        $category = $this->getMainCategory();

        if ($category) {
            return $category->category_id;
        } else {
            return null;
        }
    }

    public function getMainCategoryName()
    {
        $category = $this->getMainCategory();

        if ($category) {
            return $category->category->name;
        } else {
            return null;
        }
    }

    public function getCategoriesDropDown() {
        $categories = Category::find()->all();
        return ArrayHelper::map($categories, 'id' , 'name');
    }

    public function getCategoriesDropDownParams() {
        return [
            'prompt' => 'Select category',
            'value' => 2
        ];
    }

    protected function createDirectory($path) {
        if (!file_exists($path)) {
            mkdir($path, 0775, true);
        }
    }
}
