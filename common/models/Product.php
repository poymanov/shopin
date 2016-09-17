<?php

namespace common\models;

use Yii;
use php_rutils\RUtils;
use common\models\ProductsCategories;
use common\models\Category;
use yii\helpers\ArrayHelper;

/**
 * This is the model class for table "products".
 *
 * @property integer $id
 * @property string $name
 * @property string $slug
 * @property string $price
 * @property string $image
 * @property string $preview_text
 * @property string $full_description
 */
class Product extends \yii\db\ActiveRecord
{
    public $category;

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
            // Запись имени URL для ЧПУ
            $this->slug = RUtils::translit()->slugify($this->name);
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
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['preview_text', 'full_description'], 'string'],
            [['name', 'price', 'image'], 'string', 'max' => 255],
            ['category', 'integer'],
            [['preview_text', 'full_description', 'name', 'price', 'image', 'category'], 'required'],
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
            'image' => 'Image',
            'preview_text' => 'Preview Text',
            'category' => 'Category',
            'full_description' => 'Full Description',
        ];
    }

    public function getAllCategories()
    {
        return $this->hasMany(ProductsCategories::className(), ['product_id' => 'id']);
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
}
