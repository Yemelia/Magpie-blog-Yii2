<?php

use yii\db\Migration;

/**
 * Handles adding article_rate to table `article`.
 */
class m170731_205047_add_article_rate_column_to_article_table extends Migration
{
    /**
     * @inheritdoc
     */
    public function up()
    {
        $this->addColumn('article', 'article_rate', 'float');
    }

    /**
     * @inheritdoc
     */
    public function down()
    {
        $this->dropColumn('post', 'article_rate');
    }
}
