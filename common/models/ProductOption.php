<?php

namespace common\models;

use common\models\ProductOptionValue;

/**
 * This is the model class for table "products_options".
 *
 * @property integer $id
 * @property string $name
 * @property integer $sort
 *
 * @property ProductOptionValue[] $productsOptionsValues
 */
class ProductOption extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'products_options';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['name'], 'required'],
            [['sort'], 'integer'],
            [['name'], 'string', 'max' => 255],
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
            'sort' => 'Sort',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getValues()
    {
        return $this->hasMany(ProductOptionValue::className(), ['option_id' => 'id']);
    }
}
