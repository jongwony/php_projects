<?php

use Illuminate\Database\Capsule\Manager as Capsule;
use Illuminate\Database\Schema\Blueprint;
use Phpmig\Migration\Migration;

class CreateDefault extends Migration
{
    /**
     * Do the migration
     */
    public function up()
    {
        Capsule::schema()->create('users', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('name', 50);
            $table->string('email', 100)->unique();
            $table->string('password', 100);
            $table->smallInteger('grant')->default(255);
            $table->timestamps();

            $table->index('grant');
            
            $table->engine = 'InnoDB';
        });

        Capsule::schema()->create('categories', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->bigInteger('parent_id')->nullable();
            $table->string('name', 100);
            $table->text('description');
            $table->longText('options')->nullable();
            $table->timestamps();

            $table->engine = 'InnoDB';
        });

        Capsule::schema()->create('posts', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->bigInteger('user_id')->nullable();
            $table->bigInteger('category_id')->nullable();
            $table->string('title', 100);
            $table->string('writer', 100);
            $table->string('password', 100)->nullable();
            $table->longText('contents');
            $table->boolean('secret')->default(false);
            $table->longText('options')->nullable();

            $table->index('user_id');
            $table->index('category_id');

            $table->timestamps();

            $table->engine = 'InnoDB';
        });
    }

    /**
     * Undo the migration
     */
    public function down()
    {
        if (Capsule::schema()->hasTable('users')) {
            Capsule::schema()->drop('users');
        }
        if (Capsule::schema()->hasTable('categories')) {
            Capsule::schema()->drop('categories');
        }
        if (Capsule::schema()->hasTable('posts')) {
            Capsule::schema()->drop('posts');
        }
    }
}
