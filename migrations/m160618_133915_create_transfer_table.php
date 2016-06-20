<?php

use yii\db\Migration;

/**
 * Handles the creation for table `transfer`.
 */
class m160618_133915_create_transfer_table extends Migration
{
    /**
     * @inheritdoc
     */
    public function up()
    {
        $this->createTable('transfer', [
            'id'           => $this->primaryKey(),
            'user_from_id' => $this->integer()->notNull(),
            'user_to_id'   => $this->integer()->notNull(),
            'amount'       => $this->integer(),
            'send_time'    => $this->timestamp()->notNull(),
        ]);

        $this->addForeignKey('fk_transfer_user_from', 'transfer', 'user_from_id', 'user', 'id', 'RESTRICT', 'RESTRICT');
        $this->addForeignKey('fk_transfer_user_to', 'transfer', 'user_to_id', 'user', 'id', 'RESTRICT', 'RESTRICT');
    }

    /**
     * @inheritdoc
     */
    public function down()
    {
        $this->dropTable('transfer');
    }
}