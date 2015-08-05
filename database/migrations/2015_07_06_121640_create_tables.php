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
        // Districts table (perifereies)
        Schema::create('districts', function(Blueprint $table) {
            $table->increments('id');
            $table->string('name');
            $table->timestamps();
        });

        // Members table
        Schema::create('members', function (Blueprint $table) {
            $table->increments('id');
            $table->string('first_name');
            $table->string('last_name');
            $table->integer('order');
            $table->integer('district_id')->unsigned();
            $table->timestamps();

            $table->foreign('district_id')->references('id')->on('districts')->onDelete('cascade');
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
            $table->boolean('completed');
            $table->timestamps();
        });

        // VotingItems
        Schema::create('voting_items', function(Blueprint $table) {
            $table->increments('id');
            $table->integer('voting_id')->unsigned();
            $table->integer('vote_type_id')->unsigned();
            $table->integer('vote_objective_id')->unsigned();
            $table->timestamps();

            $table->foreign('voting_id')->references('id')->on('votings')->onDelete('cascade');
            $table->foreign('vote_type_id')->references('id')->on('vote_types')->onDelete('cascade');
            $table->foreign('vote_objective_id')->references('id')->on('vote_objectives')->onDelete('cascade');
        });

        // Votes
        Schema::create('votes', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('voting_item_id')->unsigned();
            $table->integer('member_id')->unsigned();
            $table->integer('answer_id')->unsigned()->nullable();

            $table->foreign('voting_item_id')->references('id')->on('voting_items')->onDelete('cascade');
            $table->foreign('member_id')->references('id')->on('members')->onDelete('cascade');
            $table->foreign('answer_id')->references('id')->on('vote_type_answers')->onDelete('cascade');

            $table->unique(['voting_item_id', 'member_id']);
        });

        // Group votes, the default votes of each group for each voting
        Schema::create('group_votes', function(Blueprint $table) {
            $table->increments('id');
            $table->integer('voting_item_id')->unsigned();
            $table->integer('group_id')->unsigned();
            $table->integer('answer_id')->unsigned();

            $table->foreign('voting_item_id')->references('id')->on('voting_items')->onDelete('cascade');
            $table->foreign('group_id')->references('id')->on('groups')->onDelete('cascade');
            $table->foreign('answer_id')->references('id')->on('vote_type_answers')->onDelete('cascade');

            $table->unique(['voting_item_id', 'group_id']);
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
        Schema::drop('voting_items');
        Schema::drop('votings');
        Schema::drop('vote_type_answers');
        Schema::drop('vote_types');
        Schema::drop('vote_objectives');
        Schema::drop('group_member');
        Schema::drop('groups');
        Schema::drop('members');
        Schema::drop('districts');

    }
}