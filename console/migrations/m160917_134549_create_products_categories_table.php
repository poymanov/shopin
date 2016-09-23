<?php

use yii\db\Migration;

/**
 * Handles the creation for table `products_categories`.
 */
class m160917_134549_create_products_categories_table extends Migration
{
    /**
     * @inheritdoc
     */
    public function up()
    {
        $this->createTable('products_categories', [
            'id' => $this->primaryKey(),
            'product_id' => $this->integer(11)->notNull(),
            'category_id' => $this->integer(11)->notNull(),
        ]);

        $this->addForeignKey('fk_products_products_categories', 'products_categories', 'product_id', 'products', 'id', 'CASCADE', 'CASCADE');
        $this->addForeignKey('fk_categories_products_categories', 'products_categories', 'category_id', 'categories', 'id', 'CASCADE', 'CASCADE');
    }

    /**
     * @inheritdoc
     */
    public function down()
    {
        $this->dropForeignKey('fk_products_products_categories', 'products_categories');
        $this->dropForeignKey('fk_categories_products_categories', 'products_categories');
        $this->dropTable('products_categories');
        
        return true;
    }
}
