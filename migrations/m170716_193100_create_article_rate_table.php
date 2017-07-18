<?php

use yii\db\Migration;

/**
 * Handles the creation of table `article_rate`.
 */
class m170716_193100_create_article_rate_table extends Migration
{
    /**
     * @inheritdoc
     */
    public function up()
    {
        $this->createTable('article_rate', [
            'id' => $this->primaryKey(),
            'user_id' => $this->integer(),
            'article_id' => $this->integer(),
            'rate' => $this->float()
        ]);


        $this->addForeignKey(
            'fk-user_id-article_rate',
            'article_rate',
            'user_id',
            'user',
            'id',
            'CASCADE'
        );

        $this->addForeignKey(
            'fk-article_id-article_rate',
            'article_rate',
            'article_id',
            'article',
            'id',
            'CASCADE'
        );

    }

    /**
     * @inheritdoc
     */
    public function down()
    {
        $this->dropTable('article_rate');
    }
}
