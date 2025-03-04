<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\Hash;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('admin_user', function (Blueprint $table) {
            $table->bigIncrements('A_ID');
            $table->string('username', 255)->unique();
            $table->string('email', 255)->unique();
            $table->string('password', 255);
            $table->string('first_name', 255);
            $table->string('surname', 255);
            $table->dateTime('date_joined');
            $table->enum('role', ['super_admin', 'editor', 'moderator'])->default('editor');
            $table->enum('status', ['active', 'suspended', 'disabled'])->default('active');
            $table->timestamps();
        });

        DB::table('admin_user')->insert([
            [
                'username' => 'admin',
                'email' => 'admin@example.com',
                'password' => Hash::make('admin123'),
                'first_name' => 'Admin',
                'surname' => 'User',
                'date_joined' => now(),
                'role' => 'super_admin',
                'status' => 'active',
                'created_at' => now(),
                'updated_at' => now(),
            ]
        ]);
    }

    public function down(): void
    {
        Schema::dropIfExists('admin_user');
    }
};

