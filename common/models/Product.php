<?php

namespace common\models;

use Yii;
use php_rutils\RUtils;

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
            [['preview_text', 'full_description'], 'string'],
            [['name', 'price', 'image'], 'string', 'max' => 255],
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
            'full_description' => 'Full Description',
        ];
    }
}
