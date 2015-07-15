<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTables extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        // Members table
        Schema::create('members', function (Blueprint $table) {
            $table->increments('id');
            $table->string('first_name');
            $table->string('last_name');
            $table->integer('order');
            $table->timestamps();
        });

        // Groups table
        Schema::create('groups', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name');
            $table->timestamps();
        });

        // Group Members table
        Schema::create('group_member', function (Blueprint $table) {
            $table->integer('group_id')->unsigned();
            $table->integer('member_id')->unsigned();

            $table->foreign('group_id')->references('id')->on('groups')->onDelete('cascade');
            $table->foreign('member_id')->references('id')->on('members')->onDelete('cascade');

            $table->primary(['group_id', 'member_id']);
        });

        // Vote objectives
        Schema::create('vote_objectives', function (Blueprint $table) {
            $table->increments('id');
            $table->string('title');
            $table->longText('description');
            $table->timestamps();
        });

        // Vote types
        Schema::create('vote_types', function (Blueprint $table) {
            $table->increments('id');
            $table->string('title');
            $table->timestamps();
        });

        // Vote type answers
        Schema::create('vote_type_answers', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('type')->unsigned();
            $table->string('answer');
            $table->timestamps();

            $table->foreign('type')->references('id')->on('vote_types')->onDelete('cascade');
        });

        // Votings
        Schema::create('votings', function (Blueprint $table) {
            $table->increments('id');
            $table->string('title');
            $table->integer('voting_type')->unsigned();
            $table->integer('objective')->unsigned();
            $table->timestamps();

            $table->foreign('voting_type')->references('id')->on('vote_types')->onDelete('cascade');
            $table->foreign('objective')->references('id')->on('vote_objectives')->onDelete('cascade');
        });

        // Votes
        Schema::create('votes', function (Blueprint $table) {
            $table->integer('voting_id')->unsigned();
            $table->integer('member_id')->unsigned();
            $table->integer('answer_id')->unsigned();

            $table->foreign('voting_id')->references('id')->on('votings')->onDelete('cascade');
            $table->foreign('member_id')->references('id')->on('members')->onDelete('cascade');
            $table->foreign('answer_id')->references('id')->on('vote_type_answers')->onDelete('cascade');

            $table->primary(['voting_id', 'member_id']);
        });

        //
        Schema::create('group_votes', function(Blueprint $table) {
            $table->integer('voting_id')->unsigned();
            $table->integer('group_id')->unsigned();
            $table->integer('answer_id')->unsigned();

            $table->foreign('voting_id')->references('id')->on('votings')->onDelete('cascade');
            $table->foreign('group_id')->references('id')->on('groups')->onDelete('cascade');
            $table->foreign('answer_id')->references('id')->on('vote_type_answers')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('group_votes');
        Schema::drop('votes');
        Schema::drop('votings');
        Schema::drop('vote_type_answers');
        Schema::drop('vote_types');
        Schema::drop('vote_objectives');
        Schema::drop('group_member');
        Schema::drop('groups');
        Schema::drop('members');

    }
}