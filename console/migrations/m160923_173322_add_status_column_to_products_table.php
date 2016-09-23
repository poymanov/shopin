<?php

use yii\db\Migration;

/**
 * Handles adding status to table `products`.
 */
class m160923_173322_add_status_column_to_products_table extends Migration
{
    /**
     * @inheritdoc
     */
    public function up()
    {
        $this->addColumn('products', 'status', $this->boolean() . ' default 0');
    }

    /**
     * @inheritdoc
     */
    public function down()
    {
        $this->dropColumn('products', 'status');

        return true;
    }
}
