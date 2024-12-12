<?php

use App\Idea\Base\BaseMigration;
use Illuminate\Database\Schema\Blueprint;

class CreateBasicTables extends BaseMigration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create(
            'languages',
            function (Blueprint $table) {
                $table->increments('id');
                $table->string('title');
                $table->string('locale', 3);
                $table->timestamps();
            }
        );
        Schema::create(
            'devices',
            function (Blueprint $table) {
                $table->bigIncrements('id');
                $table->string('type', 20);
                $table->string('version')->nullable();
                $table->string('uuid');
                $table->boolean('active')->default(true);
                $table->unsignedBigInteger('user_id')->nullable();
                $table->string('locale', 3)->default("en");
                $table->string('token')->nullable();
                $table->timestamp('last_access')->nullable();
                $table->timestamps();
                $table->softDeletes();
            }
        );
        Schema::create(
            'users',
            function (Blueprint $table) {
                $table->bigIncrements('id');
                $table->string('username', 100)->unique();
                $table->string('name', 100);
                $table->string('password', 60);
                $table->boolean('active')->default(false);
                $table->string('email')->nullable();
                //used to make handle changing password or signing out from other devices
                $table->string('jwt_sign', 100)->nullable();
                // Email verification
                $table->string('email_confirm_code', 100)->nullable();
                $table->dateTime('email_confirm_expiry')->nullable();
                $table->dateTime('email_confirmed_at')->nullable();
                // Forgot password
                $table->string('password_change_code', 100)->nullable();
                $table->dateTime('password_change_expiry')->nullable();
                $table->dateTime('password_changed_at')->nullable();
                $table->timestamps();
                $table->softDeletes();
            }
        );
        Schema::create(
            'roles',
            function (Blueprint $table) {
                $table->increments('id');
                $table->string('title')->nullable()->translate();
                $table->string('slug', 50);
                $table->timestamps();
                $this->setMainTable($table);
                $table->softDeletes();
            }
        );
        //create translate table for the roles table
        $this->translateMainTable();

        Schema::create(
            'user_roles',
            function (Blueprint $table) {
                $table->increments('id');
                $table->unsignedBigInteger('user_id');
                $table->unsignedInteger('role_id');
                $table->timestamps();
                $table->softDeletes();
            }
        );
        Schema::create(
            'user_notifications',
            function (Blueprint $table) {
                $table->bigIncrements('id');
                $table->unsignedBigInteger('user_id');
                $table->unsignedBigInteger('target_id')->nullable();
                $table->string('type', 100);
                $table->string('desc');
                $table->boolean('read')->default(false);
                $table->timestamps();
                $table->softDeletes();
            }
        );
        Schema::create(
            'failed_jobs',
            function (Blueprint $table) {
                $table->increments('id');
                $table->text('connection');
                $table->text('queue');
                $table->longText('payload');
                $table->longText('exception');
                $table->timestamp('failed_at')->useCurrent();
            }
        );
        Schema::create(
            'user_provider_tokens',
            function (Blueprint $table) {
                $table->bigIncrements('id');
                $table->unsignedBigInteger('user_id');
                $table->string('from', 50);
                $table->string('token_id');
                $table->text('token_value')->nullable();
                $table->dateTime('expiry_date')->nullable();
                $table->timestamps();
                $table->softDeletes();
            }
        );
        Schema::create(
            'configurations',
            function (Blueprint $table) {
                $table->bigIncrements('id');
                $table->string('value');
                $table->string('code')->unique();
                $table->string('text');
                $table->timestamps();
                $table->softDeletes();
            }
        );
        Schema::create(
            'feedback',
            function (Blueprint $table) {
                $table->bigIncrements('id');
                $table->unsignedBigInteger('user_id');
                $table->string('subject');
                $table->text('body');
                $table->timestamps();
                $table->softDeletes();
            }
        );
        Schema::create(
            'pages',
            function (Blueprint $table) {
                $table->bigIncrements('id');
                $table->unsignedBigInteger('parent_id')->nullable();
                $table->string('code')->nullable()->unique();
                $table->string('url')->nullable();
                $table->text('image')->nullable();
                $table->timestamps();

                $table->text('body')->nullable()->translate();
                $table->string('title')->translate();
                $this->setMainTable($table);
                $table->softDeletes();
            }
        );
        $this->translateMainTable(true);

        Schema::create(
            'role_permissions',
            function (Blueprint $table) {
                $table->increments('id');
                $table->unsignedInteger('permission_id');
                $table->unsignedInteger('role_id');
                $table->unsignedInteger('action_id')->nullable();
                $table->timestamps();
                $table->softDeletes();
            }
        );
        Schema::create(
            'permissions',
            function (Blueprint $table) {
                $table->increments('id');
                $table->string('module');
                $table->string('name');
                $table->string('code');
                $table->timestamps();
                $table->softDeletes();
            }
        );
        Schema::create(
            'actions',
            function (Blueprint $table) {
                $table->increments('id');
                $table->string('name');
                $table->timestamps();
                $table->softDeletes();
            }
        );
        Schema::create(
            'profiles',
            function (Blueprint $table) {
                $table->bigIncrements('id');
                $table->unsignedBigInteger('user_id')->unique();
                $table->string('first_name')->nullable();
                $table->string('middle_name')->nullable();
                $table->string('last_name')->nullable();
                $table->string('phone')->nullable();
                $table->string('image')->nullable();
                $table->enum('gender', array('male', 'female'))->nullable();
                $table->date('dob')->nullable();
                $table->integer('country_id')->nullable();
                $table->timestamps();
                $table->softDeletes();
            }
        );
        Schema::create(
            'countries',
            function (Blueprint $table) {
                $table->bigIncrements('id');
                $table->string('code');

                $table->string('name')->translate();
                $table->timestamps();
                $this->setMainTable($table);
                $table->softDeletes();
            }
        );
        $this->translateMainTable(true);

        //push_notification_histories
        Schema::create(
            'push_notification_histories',
            function (Blueprint $table) {
                $table->bigIncrements('id');
                $table->unsignedBigInteger('target_id')->nullable()->default(null);
                $table->string('devices', 20)->nullable()->default(null);
                $table->text('text')->default(null);
                $table->boolean('sent')->default('0');
                $table->timestamp('push_date')->default(null);
                $table->timestamps();
                $table->softDeletes();
            }
        );
        //push_notification_topics
        Schema::create(
            'push_notification_topics',
            function (Blueprint $table) {
                $table->bigIncrements('id');
                $table->string('name');
                $table->string('code');
                $table->timestamps();
                $table->softDeletes();
            }
        );
        // ip filtering for specific routes
        Schema::create(
            'ip_filters',
            function (Blueprint $table) {
                $table->bigIncrements('id');
                $table->string('ip');
                $table->integer('status')->unsigned()->default(1);
                $table->timestamps();
                $table->softDeletes();
            }
        );
        // app versions
        Schema::create(
            'app_versions',
            function (Blueprint $table) {
                $table->bigIncrements('id');
                $table->string('version');
                $table->enum('device_type', array('android', 'ios'))->default('ios');
                $table->enum('update_type', array('major', 'minor'))->default('minor');
                $table->integer('active')->unsigned()->default(1);
                $table->timestamps();
                $table->softDeletes();
            }
        );
        // logs requests
        Schema::create(
            'logs_requests',
            function (Blueprint $table) {
                $table->bigIncrements('id');
                $table->string('method');
                $table->string('url');
                $table->string('country')->nullable();
                $table->enum('device_type', array('android', 'ios', 'cms'))->default('cms');
                $table->json('data');
                $table->string('role')->nullable();
                $table->timestamps();
                $table->softDeletes();
            }
        );
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('languages');
        Schema::dropIfExists('devices');
        Schema::dropIfExists('users');
        Schema::dropIfExists('roles');
        Schema::dropIfExists('user_roles');
        Schema::dropIfExists('user_notifications');
        Schema::dropIfExists('failed_jobs');
        Schema::dropIfExists('user_provider_tokens');
        Schema::dropIfExists('configurations');
        Schema::dropIfExists('feedback');
        Schema::dropIfExists('pages');
        Schema::dropIfExists('countries');
        Schema::dropIfExists('profiles');
        Schema::dropIfExists('role_permissions');
        Schema::dropIfExists('permissions');
        Schema::dropIfExists('push_notification_histories');
        Schema::dropIfExists('actions');
        Schema::dropIfExists('push_notification_topics');
        Schema::dropIfExists('ip_filters');
        Schema::dropIfExists('app_versions');
        Schema::dropIfExists('logs_requests');
    }
}
