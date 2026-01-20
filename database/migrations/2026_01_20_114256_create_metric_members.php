<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateMetricMembers extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('metric_members', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('metric_id')->nullable();
            $table->bigInteger('team_id');
            $table->bigInteger('programme_id');
            $table->bigInteger('team_member_id');
            $table->date('date');
            $table->boolean('checked')->default(false);
            $table->bigInteger('user_id');
            $table->bigInteger('ins');
            $table->timestamp('created_at')->useCurrent();
            $table->timestamp('updated_at')->useCurrent();

            // Foreign key constraint if you have a teams table
            $table->foreign('metric_id')->references('id')->on('metrics')->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('metric_members');
    }
}
