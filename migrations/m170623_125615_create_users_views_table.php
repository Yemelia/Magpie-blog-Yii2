<?php

use yii\db\Migration;

/**
 * Handles the creation of table `users_views`.
 */
class m170623_125615_create_users_views_table extends Migration
{
    /**
     * @inheritdoc
     */
    public function up()
    {
        $this->createTable('users_views', [
            'id' => $this->primaryKey(),
            'user_id'=>$this->integer(),
            'article_id'=>$this->integer()
        ]);

        // add foreign key for table `user`
        $this->addForeignKey(
            'fk-user_id-users_views',
            'users_views',
            'user_id',
            'user',
            'id',
            'CASCADE'
        );
        // creates index for column `user_id`
        $this->createIndex(
            'idx_user_id',
            'users_views',
            'user_id'
        );

        // add foreign key for table `article`
        $this->addForeignKey(
            'fk-article_id-users_views',
            'users_views',
            'article_id',
            'article',
            'id',
            'CASCADE'
        );

        // creates index for column `article_id`
        $this->createIndex(
            'idx_article_id',
            'users_views',
            'user_id'
        );
    }

    /**
     * @inheritdoc
     */
    public function down()
    {
        $this->dropTable('users_views');
    }
}
