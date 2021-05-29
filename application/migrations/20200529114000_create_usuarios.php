<?php

defined('BASEPATH') or exit('No direct script access allowed');

class Migration_Create_Usuarios extends CI_Migration
{

    public function up()
    {
        $this->dbforge->add_field(array(
            'id' => array(
                'type' => 'INT',
                'constraint' => 5,
                'unsigned' => TRUE,
                'auto_increment' => TRUE
            ),
            'nome' => array(
                'type' => 'VARCHAR',
                'constraint' => '60',
            ),
            'email' => array(
                'type' => 'VARCHAR',
                'constraint' => '50',
            ),
            'login' => array(
                'type' => 'VARCHAR',
                'constraint' => '20',
            ),
            'senha' => array(
                'type' => 'VARCHAR',
                'constraint' => '255',
            ),
            'status' => array(
                'type' => 'TINYINT',
                'constraint' => '1',
            ),
            'admin' => array(
                'type' => 'TINYINT',
                'constraint' => '1',
            )
        ));
        $this->dbforge->add_key('id', TRUE);
        $this->dbforge->create_table('usuarios');
    }

    public function down()
    {
        $this->dbforge->drop_table('usuarios');
    }
}
