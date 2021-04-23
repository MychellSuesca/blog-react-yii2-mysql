<?php

use yii\db\Migration;

/**
 * Class m210420_134816_mainTables
 */
class m210420_134816_mainTables extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $fecha = date('Y-m-d h:i:s');

        $this->createTable('articulos', array(
            "id" => "INT AUTO_INCREMENT PRIMARY KEY NOT NULL",
            "id_categoria" => "INT",
            "titulo" => "VARCHAR(50)",
            "slug" => "VARCHAR(50)",
            "texto_corto" => "VARCHAR(50)",
            "texto_largo" => "VARCHAR(5000)",
            "imagen" => "VARCHAR(100)",
            "fecha_creacion" => "DATETIME",
            "fecha_actualizacion" => "DATETIME ",
        ));

        $this->createTable('categorias', array(
            "id" => "INT AUTO_INCREMENT PRIMARY KEY NOT NULL",
            "categoria" => "VARCHAR(50)",
        ));

        $categorias = [
            ["id" => "1", "categoria" => 'Tecnologia'],
            ["id" => "2", "categoria" => 'Entretenimiento'],
            ["id" => "3", "categoria" => 'Cultura'],
            ["id" => "4", "categoria" => 'Ciencia'],
        ];

        foreach ($categorias as $categoria) {
            $this->insert('categorias', $categoria);
        }

        $this->createTable('usuarios', array(
            "id" => "INT AUTO_INCREMENT PRIMARY KEY NOT NULL",
            "nombre" => "VARCHAR(100)",
            "email" => "VARCHAR(100)",
            "password" => "VARCHAR(100)",
            "celular" => "INT",
            "tipo_usuario" => "INT",
            "fecha_creacion" => "DATETIME",
            "fecha_actualizacion" => "DATETIME ",
        ));

        $usuarios = [
            ["id" => "1", "nombre" => 'Root', 'email' => 'root@root.com', 'password' => '123456', 'celular' => '3103101010', 'tipo_usuario' => 1, 'fecha_creacion' => $fecha, 'fecha_actualizacion' => $fecha],
        ];

        foreach ($usuarios as $usuario) {
            $this->insert('usuarios', $usuario);
        }

        $this->createTable('likes', array(
            "id_articulo" => "INT",
            "id_usuario" => "INT",
        ));
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        echo "m210420_134816_mainTables cannot be reverted.\n";

        return false;
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m210420_134816_mainTables cannot be reverted.\n";

        return false;
    }
    */
}
