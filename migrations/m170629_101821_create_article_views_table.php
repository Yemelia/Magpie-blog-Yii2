<?php

use yii\db\Migration;

/**
 * Handles the creation of table `article_views`.
 */
class m170629_101821_create_article_views_table extends Migration
{
    /**
     * @inheritdoc
     */
    public function up()
    {
        $this->createTable('article_views', [
            'id' => $this->primaryKey(),
            'article_id'=>$this->integer(),
            'user_ip'=>$this->string()
        ]);

        // creates index for column `user_ip`
        $this->createIndex(
            'idx_user_ip',
            'article_views',
            'user_ip'
        );

        // add foreign key for table `article`
        $this->addForeignKey(
            'fk-article_id-users_views',
            'article_views',
            'article_id',
            'article',
            'id',
            'CASCADE'
        );

        // creates index for column `article_id`
        $this->createIndex(
            'idx_article_id',
            'article_views',
            'article_id'
        );
    }

    /**
     * @inheritdoc
     */
    public function down()
    {
        $this->dropTable('article_views');
    }
}
