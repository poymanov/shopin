<?php

use yii\db\Migration;

class m161027_182900_create_products_options extends Migration
{
    public function up()
    {
        // Таблица для хранения опций товара
        $this->createTable('products_options', [
            'id' => $this->primaryKey(),
            'name' => $this->string()->notNull(),
            'sort' => $this->integer()
        ]);
    }

    public function down()
    {
        $this->dropTable('products_options');
        return true;
    }

}
