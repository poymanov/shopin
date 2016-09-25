<?php

use yii\db\Migration;

/**
 * Handles the creation for table `products_discounts`.
 */
class m160923_183119_create_products_discounts_table extends Migration
{
    /**
     * @inheritdoc
     */
    public function up()
    {
        $this->createTable('products_discounts', [
            'id' => $this->primaryKey(),
            'name' => $this->string()->notNull(),
        ]);

        /*
         * Добавляем новое поле в таблицу Products
         */
        $this->addColumn('products', 'discount_id', $this->integer());
        $this->addForeignKey('fk_products_products_discounts', 'products', 'discount_id', 'products_discounts', 'id', 'CASCADE', 'CASCADE');

    }

    /**
     * @inheritdoc
     */
    public function down()
    {
        /*
         * Удаляем связанное поле из таблицы Products
         */

        $this->dropForeignKey('fk_products_products_discounts', 'products');
        $this->dropColumn('products', 'discount_id');
        $this->dropTable('products_discounts');

        return true;
    }
}
