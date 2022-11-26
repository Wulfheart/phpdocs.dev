<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePackagesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('packages', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->string('vendor');
            $table->string('package');
            $table->text('description');
            $table->string('repository_url');
            $table->string('license')->nullable();
            $table->integer('stats_github_stars')->nullable();
            $table->integer('stats_github_watchers')->nullable();
            $table->integer('stats_github_forks')->nullable();
            $table->integer('stats_github_open_issues')->nullable();
            $table->integer('stats_dependents')->nullable();
            $table->integer('stats_favers')->nullable();
            $table->integer('stats_suggesters')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('packages');
    }
}
