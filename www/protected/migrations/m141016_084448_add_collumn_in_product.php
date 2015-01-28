<?php

class m141016_084448_add_collumn_in_product extends DbMigration
{
    public function up()
    {
        $this->addColumn('{{product}}', 'article', 'string DEFAULT NULL');
    }

    public function down()
    {
        $this->dropColumn('{{product}}', 'article');
        return true;
    }
}
