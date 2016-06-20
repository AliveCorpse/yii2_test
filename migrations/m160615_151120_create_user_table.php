<?php

use yii\db\Migration;

/**
 * Handles the creation for table `user`.
 */
class m160615_151120_create_user_table extends Migration
{
    /**
     * @inheritdoc
     */
    public function up()
    {
        $this->createTable('user', [
            'id'      => $this->primaryKey(),
            'name'    => $this->string(50)->notNull()->unique(),
            'balance' => $this->integer()->notNull()->defaultValue(0),
        ]);
    }

    /**
     * @inheritdoc
     */
    public function down()
    {
        $this->dropTable('user');
    }
}
