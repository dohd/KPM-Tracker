<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTeamMembers extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('team_members', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('team_id')->nullable();
            $table->string('full_name');
            $table->enum('category', ['local', 'diaspora', 'dormant'])->default('local');
            $table->string('df_name')->nullable();
            $table->string('phone_no')->nullable();
            $table->string('physical_addr')->nullable();
            $table->bigInteger('user_id')->nullable();
            $table->bigInteger('ins');
            $table->timestamp('created_at')->useCurrent();
            $table->timestamp('updated_at')->useCurrent();

            // Foreign key constraint if you have a teams table
            $table->foreign('team_id')->references('id')->on('teams')->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('team_members');
    }
}
