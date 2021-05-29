<?php

defined('BASEPATH') or exit('No direct script access allowed');

class Migration_Create_Recuperar_Senha extends CI_Migration
{

    public function up()
    {
        $this->dbforge->add_field(array(
            'id' => array(
                'type' => 'INT',
                'constraint' => 5,
                'unsigned' => TRUE,
                'auto_increment' => TRUE,
                'unique' => TRUE
            ),
            'hash' => array(
                'type' => 'VARCHAR',
                'constraint' => '255',
                'unique' => TRUE
            ),
            'usuario_id' => array(
                'type' => 'INT',
                'constraint' => 5,
                'unsigned' => TRUE
            )
        ));
        $this->dbforge->add_key('id', TRUE);
        $this->dbforge->add_field('CONSTRAINT FOREIGN KEY (usuario_id) REFERENCES usuarios(id)');
        $this->dbforge->create_table('recuperar_senha');
    }

    public function down()
    {
        $this->dbforge->drop_table('recuperar_senha');
    }
}
