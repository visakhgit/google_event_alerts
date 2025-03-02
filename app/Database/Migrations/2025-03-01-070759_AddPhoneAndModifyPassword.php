<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class AddPhoneAndModifyPassword extends Migration
{
    public function up()
    {
        $this->forge->modifyColumn('users', [
            'password' => [
                'type'       => 'VARCHAR',
                'constraint' => 255,
                'null'       => true, 
                'change'     => true, 
            ]
        ]);

        $this->forge->addColumn('users', [
            'phone' => [
                'type'       => 'VARCHAR',
                'constraint' => 20,
                'null'       => true, 
                'after'      => 'google_token', 
            ]
        ]);
    }

    public function down()
    {
        $this->forge->modifyColumn('users', [
            'password' => [
                'type'       => 'VARCHAR',
                'constraint' => 255,
                'null'       => false, 
                'change'     => true,
            ]
        ]);

        // Remove the phone column
        $this->forge->dropColumn('users', 'phone');
    }
}
