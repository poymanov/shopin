<?php

use yii\db\Migration;

/**
 * Handles adding status to table `brands`.
 */
class m160919_210355_add_status_column_to_brands_table extends Migration
{
    /**
     * @inheritdoc
     */
    public function up()
    {
        $this->addColumn('brands', 'status', $this->boolean());
    }

    /**
     * @inheritdoc
     */
    public function down()
    {
        $this->dropColumn('brands', 'status');

        return true;
    }
}
