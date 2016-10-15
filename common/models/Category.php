<?php

namespace common\models;

use Yii;
use php_rutils\RUtils;

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

}
