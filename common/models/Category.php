<?php

namespace common\models;

use Yii;
use php_rutils\RUtils;
use yii\helpers\ArrayHelper;

/**
 * This is the model class for table "categories".
 *
 * @property integer $id
 * @property string $name
 * @property string $slug
 * @property string $parent_id
 */
class Category extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'categories';
    }

    public function beforeSave($insert)
    {
        if (parent::beforeSave($insert)) {
            $this->slug = RUtils::translit()->slugify($this->name);
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
            [['name'], 'string', 'max' => 255],
            ['parent_id', 'integer'],
            ['parent_id', 'safe']
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
            'parent_id' => 'Parent ID',
        ];
    }

    /**
     * Получение дочерних категорий данной категории
     */
    public function getChildCategories()
    {
        return $this->hasMany(Category::className(), ['parent_id' => 'id']);
    }

    /**
     * Получение данных таблицы ProductsCategories
     */

    public function getProducts()
    {
        return $this->hasMany(ProductsCategories::className(), ['category_id' => 'id']);
    }

    /**
     * Получение списка товаров по категории
     */

    public function getAllProducts($mode = null)
    {
        // Получение id всех товаров по категории
        $productsCategory = $this->getProducts()->select(['product_id'])->asArray()->all();

        // Преобразование результатов в более удобный массив для запроса
        $productArray = ArrayHelper::getColumn($productsCategory, 'product_id');

        // Возвращаем несформированный запрос, если это необходимо

        $query = Product::find()->with('productImages', 'allCategories', 'allCategories.category')->where(['id' => $productArray]);

        if ($mode == 'query') {
            return $query;
        } else {
            $products = $query->all();
        }

        return $products;
    }

}
