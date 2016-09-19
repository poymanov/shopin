<?php

use yii\db\Migration;

/**
 * Handles the creation for table `brands`.
 */
class m160919_175152_create_brands_table extends Migration
{
    /**
     * @inheritdoc
     */
    public function up()
    {
        $this->createTable('brands', [
            'id' => $this->primaryKey(),
            'name' => $this->string(50)->notNull(),
            'image' => $this->string()->notNull(),
            'href' => $this->string(),
            'title' => $this->string(),
            'alt' => $this->string(),
            'sort' => $this->smallInteger(2),
        ]);
    }

    /**
     * @inheritdoc
     */
    public function down()
    {
        $this->dropTable('brands');
    }
}
