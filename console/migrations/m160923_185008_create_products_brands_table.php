<?php

use yii\db\Migration;

/**
 * Handles the creation for table `products_brands`.
 */
class m160923_185008_create_products_brands_table extends Migration
{
    /**
     * @inheritdoc
     */
    public function up()
    {
        $this->createTable('products_brands', [
            'id' => $this->primaryKey(),
            'name' => $this->string()->notNull(),
        ]);

        /*
         * Добавляем новое поле в таблицу Products
         */
        $this->addColumn('products', 'brand_id', $this->integer());
        $this->addForeignKey('fk_products_products_brands', 'products', 'brand_id', 'products_brands', 'id', 'CASCADE', 'CASCADE');

    }

    /**
     * @inheritdoc
     */
    public function down()
    {
        /*
         * Удаляем связанное поле из таблицы Products
         */
        $this->dropForeignKey('fk_products_products_brands', 'products');
        $this->dropColumn('products', 'brand_id');
        $this->dropTable('products_brands');

        return true;
    }
}
