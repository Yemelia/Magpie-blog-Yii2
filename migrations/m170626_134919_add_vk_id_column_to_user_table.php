<?php

use yii\db\Migration;

/**
 * Handles adding vk_id to table `user`.
 */
class m170626_134919_add_vk_id_column_to_user_table extends Migration
{
    /**
     * @inheritdoc
     */
    public function up()
    {
        $this->addColumn('user', 'vk_id', $this->integer());
    }

    /**
     * @inheritdoc
     */
    public function down()
    {
        $this->dropColumn('user', 'vk_id');
    }
}
