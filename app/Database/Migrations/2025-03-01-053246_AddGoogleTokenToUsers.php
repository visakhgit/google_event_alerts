<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class AddGoogleTokenToUsers extends Migration
{
    public function up()
    {
        $fields = [
            'google_token' => [
                'type' => 'TEXT',
                'null' => true, 
                'after' => 'password', 
            ],
        ];

        $this->forge->addColumn('users', $fields);
    }

    public function down()
    {
        $this->forge->dropColumn('users', 'google_token');
    }
}
