<?php
/**
 * @link http://phe.me
 * @copyright Copyright (c) 2014 Pheme
 * @license MIT http://opensource.org/licenses/MIT
 */

/**
 * @author Aris Karageorgos <aris@phe.me>
 */
class m160810_045255_create_settings extends \yii\db\Migration
{
    public function up()
    {
        $this->createTable(
            '{{%settings}}',
            [
                'id' => $this->primaryKey(),
                'type' => $this->string(255)->notNull(),
                'section' => $this->string(255)->notNull(),
                'key' => $this->string(255)->notNull(),
                'value' => $this->text(),
                'description' => $this->text(),
                'active' => $this->boolean(),
                'created' => $this->dateTime(),
                'modified' => $this->dateTime(),
            ]
        );
    }

    public function down()
    {
        $this->dropTable('{{%settings}}');
    }
}