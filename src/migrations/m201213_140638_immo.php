<?php

use yii\db\Migration;

/**
 * Class m201213_180638_immo
 */
class m201213_180638_immo extends Migration
{
    private const SERVICE_TABLE = 'service',
        OPERATOR_TABLE = 'operator',
        QUEUE_TABLE = 'queue';

    /**
     * {@inheritdoc}
     */
    public function safeUp(): bool
    {
        $tableOptions = NULL;
        if ($this->db->driverName === 'mysql') {
            $tableOptions = 'CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE=InnoDB';
        }

        $this->createTable(
            self::SERVICE_TABLE,
            [
                'id'    => $this->primaryKey(9),
                'title' => $this->string()->notNull()
            ], $tableOptions
        );

        $this->createTable(
            self::OPERATOR_TABLE,
            [
                'id'     => $this->primaryKey(9),
                'name'   => $this->string()->notNull(),
                'status' => "ENUM('offline', 'ready', 'busy')"
            ], $tableOptions
        );

        $this->createIndex('idx_status', self::OPERATOR_TABLE, ['status']);

        $this->createTable(
            self::QUEUE_TABLE,
            [
                'id'          => $this->primaryKey(9),
                'service_id'  => $this->integer()->notNull(),
                'operator_id' => $this->integer()->notNull(),
                'created_at'  => $this->timestamp()->notNull()->defaultExpression('CURRENT_TIMESTAMP()'),
                'executed_at' => $this->timestamp()->null()
            ], $tableOptions
        );

        $this->addForeignKey('FK_queue_service', self::QUEUE_TABLE, 'service_id', self::SERVICE_TABLE, 'id');
        $this->addForeignKey('FK_queue_operator', self::QUEUE_TABLE, 'operator_id', self::OPERATOR_TABLE, 'id');

        $this->insert(self::SERVICE_TABLE, ['title' => 'service_1']);
        $this->insert(self::SERVICE_TABLE, ['title' => 'service_2']);
        $this->insert(self::SERVICE_TABLE, ['title' => 'service_3']);

        $this->insert(self::OPERATOR_TABLE, ['name' => 'test_op_1', 'status' => 'offline']);
        $this->insert(self::OPERATOR_TABLE, ['name' => 'test_op_2', 'status' => 'offline']);
        $this->insert(self::OPERATOR_TABLE, ['name' => 'test_op_3', 'status' => 'offline']);
        $this->insert(self::OPERATOR_TABLE, ['name' => 'test_op_4', 'status' => 'ready']);
        $this->insert(self::OPERATOR_TABLE, ['name' => 'test_op_5', 'status' => 'ready']);
        $this->insert(self::OPERATOR_TABLE, ['name' => 'test_op_6', 'status' => 'ready']);
        $this->insert(self::OPERATOR_TABLE, ['name' => 'test_op_7', 'status' => 'ready']);
        $this->insert(self::OPERATOR_TABLE, ['name' => 'test_op_8', 'status' => 'ready']);
        $this->insert(self::OPERATOR_TABLE, ['name' => 'test_op_9', 'status' => 'ready']);
        $this->insert(self::OPERATOR_TABLE, ['name' => 'test_op_10', 'status' => 'ready']);

        return true;
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown(): bool
    {
        $this->dropForeignKey('FK_queue_service', self::QUEUE_TABLE);
        $this->dropForeignKey('FK_queue_operator', self::QUEUE_TABLE);
        $this->dropTable(self::QUEUE_TABLE);
        $this->dropTable(self::OPERATOR_TABLE);
        $this->dropTable(self::SERVICE_TABLE);

        return true;
    }
}
