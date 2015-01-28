<?php

class {ClassName} extends DbMigration
{
    public function up()
    {
    }

    public function down()
    {
        echo "{ClassName} does not support migration down.\\n";
        return false;
    }

    /*
    // Use safeUp/safeDown to do migration with transaction
    public function safeUp()
    {
    }

    public function safeDown()
    {
    }
*/
}
