<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration {
    public function up()
    {
        DB::statement("
            ALTER TABLE role
            MODIFY role_type ENUM(
                'admin',
                'agent',
                'viewer',
                'customer_admin',
                'client'
            ) NOT NULL
        ");
    }

    public function down()
    {
        DB::statement("
            ALTER TABLE role
            MODIFY role_type ENUM(
                'admin',
                'agent',
                'viewer',
                'customer_admin'
            ) NOT NULL
        ");
    }
};
