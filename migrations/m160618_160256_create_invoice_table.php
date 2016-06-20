<?php

use yii\db\Migration;

/**
 * Handles the creation for table `invoice`.
 */
class m160618_160256_create_invoice_table extends Migration
{
    /**
     * @inheritdoc
     */
    public function up()
    {
        $this->createTable('invoice', [
            'id'           => $this->primaryKey(),
            'user_from_id' => $this->integer()->notNull(),
            'user_to_id'   => $this->integer()->notNull(),
            'amount'       => $this->integer(),
            'status'       => $this->string(25),
            'created_at'   => $this->timestamp(),
            'updated_at'   => $this->timestamp(),
        ]);

        $this->addForeignKey('fk_invoice_user_from', 'invoice', 'user_from_id', 'user', 'id', 'RESTRICT', 'RESTRICT');
        $this->addForeignKey('fk_invoice_user_to', 'invoice', 'user_to_id', 'user', 'id', 'RESTRICT', 'RESTRICT');
    }

    /**
     * @inheritdoc
     */
    public function down()
    {
        $this->dropTable('invoice');
    }
}
