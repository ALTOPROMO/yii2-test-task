<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%employee_service}}`.
 */
class m210909_101328_create_employee_service_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%employee_service}}', [
            'id' => $this->primaryKey(),
            'employee_id' => $this->integer(),
            'service_id' => $this->integer(),
        ]);
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable('{{%employee_service}}');
    }
}
