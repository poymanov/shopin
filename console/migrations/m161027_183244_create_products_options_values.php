<?php

use yii\db\Migration;

class m161027_183244_create_products_options_values extends Migration
{
    public function up()
    {
        // Таблица для хранения значений опция товара
        $this->createTable('products_options_values', [
            'id' => $this->primaryKey(),
            'option_id' => $this->integer(),
            'name' => $this->string()
        ]);

        // Связь с таблицей products_options
        $this->addForeignKey('fk_products_options_values_products_options',
                            'products_options_values', 'option_id', 'products_options', 'id', 'CASCADE', 'CASCADE');
    }

    public function down()
    {
        $this->dropForeignKey('fk_products_options_values_products_options', 'products_options_values');
        $this->dropTable('products_options_values');
        return true;
    }
}
