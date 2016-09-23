<?php

use yii\db\Migration;

/**
 * Handles the creation for table `products`.
 */
class m160916_194034_create_products_table extends Migration
{
    /**
     * @inheritdoc
     */
    public function up()
    {
        $this->createTable('products', [
            'id' => $this->primaryKey(),
            'name' => $this->string()->notNull(),
            'slug' => $this->string()->notNull(),
            'price' => $this->string(),
            'image' => $this->string(),
            'preview_text' => $this->text(),
            'full_description' => $this->text()
        ]);
    }

    /**
     * @inheritdoc
     */
    public function down()
    {
        $this->dropTable('products');
        
        return true;
    }
}
