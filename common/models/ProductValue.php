<?php

namespace common\models;

use common\models\ProductOptionValue;
use common\models\Product;
use common\models\ProductOption;

/**
 * This is the model class for table "products_values".
 *
 * @property integer $id
 * @property integer $product_id
 * @property integer $option_id
 * @property integer $value_id
 *
 * @property ProductOptionValue $value
 * @property Product $product
 * @property ProductOption $option
 */
class ProductValue extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'products_values';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['product_id', 'option_id', 'value_id'], 'integer'],
            [['value_id'], 'exist', 'skipOnError' => true, 'targetClass' => ProductOptionValue::className(), 'targetAttribute' => ['value_id' => 'id']],
            [['product_id'], 'exist', 'skipOnError' => true, 'targetClass' => Product::className(), 'targetAttribute' => ['product_id' => 'id']],
            [['option_id'], 'exist', 'skipOnError' => true, 'targetClass' => ProductOption::className(), 'targetAttribute' => ['option_id' => 'id']],
            [['product_id', 'option_id', 'value_id'], 'required'],
            [['product_id', 'option_id', 'value_id'], 'unique', 'targetAttribute' => ['product_id', 'option_id', 'value_id']],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'product_id' => 'Product ID',
            'option_id' => 'Option ID',
            'value_id' => 'Value ID',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getValue()
    {
        return $this->hasOne(ProductOptionValue::className(), ['id' => 'value_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getProduct()
    {
        return $this->hasOne(Product::className(), ['id' => 'product_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getOption()
    {
        return $this->hasOne(ProductOption::className(), ['id' => 'option_id']);
    }
}
