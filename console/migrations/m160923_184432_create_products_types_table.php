<?php

use yii\db\Migration;

/**
 * Handles the creation for table `products_types`.
 */
class m160923_184432_create_products_types_table extends Migration
{
    /**
     * @inheritdoc
     */
    public function up()
    {
        $this->createTable('products_types', [
            'id' => $this->primaryKey(),
            'name' => $this->string()->notNull(),
        ]);

        /*
         * Добавляем новое поле в таблицу Products
         */
        $this->addColumn('products', 'type_id', $this->integer());
        $this->addForeignKey('fk_products_products_types', 'products', 'type_id', 'products_types', 'id', 'CASCADE', 'CASCADE');

    }

    /**
     * @inheritdoc
     */
    public function down()
    {
        /*
         * Удаляем связанное поле из таблицы Products
         */
        $this->dropForeignKey('fk_products_products_types', 'products');
        $this->dropColumn('products', 'type_id');
        $this->dropTable('products_types');

        return true;
    }
}
