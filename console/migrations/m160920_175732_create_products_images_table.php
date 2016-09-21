<?php

use yii\db\Migration;

/**
 * Handles the creation for table `products_images`.
 */
class m160920_175732_create_products_images_table extends Migration
{
    /**
     * @inheritdoc
     */
    public function up()
    {
        $this->createTable('products_images', [
            'id' => $this->primaryKey(),
            'product_id' => $this->integer(11)->notNull(),
            'name' => $this->string(),
            'path' => $this->string()->notNull(),
            'title' => $this->string(),
            'alt' => $this->string(),
            'main' => $this->boolean()
        ]);

        $this->addForeignKey('fk_products_images_products', 'products_images', 'product_id', 'products', 'id', 'CASCADE', 'CASCADE');
    }

    /**
     * @inheritdoc
     */
    public function down()
    {
        $this->dropForeignKey('fk_products_images_products', 'products_images');
        $this->dropTable('products_images');
        return true;
    }
}
