<?php

use yii\db\Migration;

class m161028_180634_create_products_values extends Migration
{
    public function up()
    {
        // Миграция создает таблицу, которая хранит значения опций для товаров
        $this->createTable('products_values', [
            'id' => $this->primaryKey(),
            'product_id' => $this->integer(),
            'option_id' => $this->integer(),
            'value_id' => $this->integer()
        ]);

        // Связь с таблицей продуктов
        $this->addForeignKey('fk_products_values_products',
            'products_values', 'product_id', 'products', 'id', 'CASCADE', 'CASCADE');

        // Связь с таблицей опций
        $this->addForeignKey('fk_products_values_products_options',
            'products_values', 'option_id', 'products_options', 'id', 'CASCADE', 'CASCADE');

        // Связь с таблицей значений опций
        $this->addForeignKey('fk_products_values_products_options_values',
            'products_values', 'value_id', 'products_options_values', 'id', 'CASCADE', 'CASCADE');

    }

    public function down()
    {
        $this->dropForeignKey('fk_products_values_products', 'products_values');
        $this->dropForeignKey('fk_products_values_products_options', 'products_values');
        $this->dropForeignKey('fk_products_values_products_options_values', 'products_values');
        $this->dropTable('products_values');
        return true;
    }
}
