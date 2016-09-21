<?php

use yii\db\Migration;

/**
 * Handles dropping image from table `products`.
 */
class m160921_200521_drop_image_column_from_products_table extends Migration
{
    /**
     * @inheritdoc
     */
    public function up()
    {
        $this->dropColumn('products', 'image');
    }

    /**
     * @inheritdoc
     */
    public function down()
    {
        $this->addColumn('products', 'image', $this->string());
    }
}
