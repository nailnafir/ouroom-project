<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up(){
        // Tabel Kelas
        Schema::create('tbl_class', function (Blueprint $table) {
            $table->bigIncrements('id', 20);
            $table->string('angkatan');
            $table->string('class_name');
            $table->string('note')->nullable();
            $table->unsignedBigInteger('teacher_id'); // Teacher diambilkan dari data user dengan type Guru
            $table->unsignedBigInteger('siswa_id'); // Teacher diambilkan dari data user dengan type Guru
            $table->timestamp('created_at')->default(\DB::raw('CURRENT_TIMESTAMP'));
            $table->timestamp('updated_at')->default(\DB::raw('CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP'));

            $table->foreign('teacher_id')
                ->references('id')
                ->on('tbl_user')
                ->onDelete('cascade');
            $table->foreign('siswa_id')
                ->references('id')
                ->on('tbl_user')
                ->onDelete('cascade');
        });

        // Tabel User
        Schema::create('tbl_user', function (Blueprint $table) {
            $table->bigIncrements('id', 20);
            $table->string('username');
            $table->string('password');
            $table->string('account_type')->default('Creator');
            $table->string('full_name')->nullable();
            $table->string('email')->nullable()->unique();
            $table->string('jenis_kelamin')->nullable();
            $table->unsignedBigInteger('angkatan')->nullable();
            $table->string('jurusan')->nullable();
            $table->unsignedBigInteger('class_id');
            $table->string('profile_picture')->nullable();
            $table->text('address')->nullable();
            $table->integer('status');
            $table->timestamp('email_verified_at')->nullable();
            $table->rememberToken();
            $table->datetime('last_login_at')->nullable();
            $table->string('last_login_ip')->nullable();
            $table->timestamp('created_at')->default(\DB::raw('CURRENT_TIMESTAMP'));
            $table->timestamp('updated_at')->default(\DB::raw('CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP'));

            $table->foreign('class_id')
                ->references('id')
                ->on('tbl_class')
                ->onDelete('cascade');
        });

        // Tabel Token User
        Schema::create('tbl_user_token', function (Blueprint $table) {
            $table->bigIncrements('id', 20);
            $table->unsignedBigInteger('user_id');
            $table->longText('token');
            $table->integer('date_expired');
            $table->timestamp('created_at')->default(\DB::raw('CURRENT_TIMESTAMP'));
            $table->timestamp('updated_at')->default(\DB::raw('CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP'));

            $table->foreign('user_id')
                ->references('id')
                ->on('tbl_user')
                ->onDelete('cascade');
        });

        // Tabel Iqro
        Schema::create('tbl_iqro', function (Blueprint $table) {
            $table->bigIncrements('id', 20);
            $table->integer('jilid_number');
            $table->integer('total_page');
            $table->timestamp('created_at')->default(\DB::raw('CURRENT_TIMESTAMP'));
            $table->timestamp('updated_at')->default(\DB::raw('CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP'));
        });

        // Tabel Log Sistem
        Schema::create('tbl_system_log', function (Blueprint $table) {
            $table->bigIncrements('id', 20);
            $table->text('action');
            $table->dateTime('date');
            $table->unsignedBigInteger('user_id')->unique();
            $table->timestamp('created_at')->default(\DB::raw('CURRENT_TIMESTAMP'));
            $table->timestamp('updated_at')->default(\DB::raw('CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP'));

            $table->foreign('user_id')
                ->references('id')
                ->on('tbl_user')
                ->onDelete('cascade');
        });

        // Tabel Siswa
        Schema::create('tbl_siswa', function (Blueprint $table) {
            $table->bigIncrements('id', 20);
            $table->string('siswa_name');
            $table->integer('memorization_type');
            $table->unsignedBigInteger('class_id');
            $table->timestamp('created_at')->default(\DB::raw('CURRENT_TIMESTAMP'));
            $table->timestamp('updated_at')->default(\DB::raw('CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP'));

            $table->foreign('class_id')
                ->references('id')
                ->on('tbl_class')
                ->onDelete('cascade');
        });

        // Tabel Siswa Surat
        Schema::create('tbl_siswa_has_surah', function (Blueprint $table) {
            $table->bigIncrements('id', 20);
            $table->unsignedBigInteger('siswa_id')->nullable();
            $table->unsignedBigInteger('surah_id')->nullable();
            $table->integer('ayat');
            $table->dateTime('date');
            $table->text('note')->nullable();
            $table->string('group_ayat'); // Grup ayat untuk merangkum kelompok penilaian bedasarkan range
            $table->timestamp('created_at')->default(\DB::raw('CURRENT_TIMESTAMP'));
            $table->timestamp('updated_at')->default(\DB::raw('CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP'));
        
            $table->foreign('siswa_id')
            ->references('id')
            ->on('tbl_siswa')
            ->onDelete('set null');
            
            $table->foreign('surah_id')
                ->references('id')
                ->on('tbl_surah')
                ->onDelete('set null');
        });

        // Tabel Siswa qro
        Schema::create('tbl_siswa_has_iqro', function (Blueprint $table) {
            $table->bigIncrements('id', 20);
            $table->unsignedBigInteger('iqro_id')->nullable();
            $table->unsignedBigInteger('siswa_id')->nullable();
            $table->integer('page');
            $table->dateTime('date');
            $table->text('note')->nullable();
            $table->string('group_page'); // Grup page untuk merangkum kelompok penilaian bedasarkan range
            $table->timestamp('created_at')->default(\DB::raw('CURRENT_TIMESTAMP'));
            $table->timestamp('updated_at')->default(\DB::raw('CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP'));

            $table->foreign('siswa_id')
            ->references('id')
            ->on('tbl_siswa')
            ->onDelete('set null');
            
            $table->foreign('iqro_id')
                ->references('id')
                ->on('tbl_iqro')
                ->onDelete('set null');
        });

        // Tabel Print Log
        Schema::create('tbl_report_print_log', function (Blueprint $table) {
            $table->bigIncrements('id', 20);
            $table->unsignedBigInteger('print_by');
            $table->dateTime('date');
            $table->text('note')->nullable();
            $table->timestamp('created_at')->default(\DB::raw('CURRENT_TIMESTAMP'));
            $table->timestamp('updated_at')->default(\DB::raw('CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP'));
    
            $table->foreign('print_by')
                ->references('id')
                ->on('tbl_user')
                ->onDelete('cascade');
        });

        // Tabel Global Settings
        Schema::create('tbl_global_setting', function (Blueprint $table) {
            $table->bigIncrements('id', 20);
            $table->tinyInteger('use_log_setting')->default(10);
            $table->tinyInteger('use_log_print')->default(10);
            $table->timestamp('created_at')->default(\DB::raw('CURRENT_TIMESTAMP'));
            $table->timestamp('updated_at')->default(\DB::raw('CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP'));
        });

        // Tabel Permissions
        $tableNames = config('permission.table_names');
        $columnNames = config('permission.column_names');

        Schema::create($tableNames['permissions'], function (Blueprint $table) {
            $table->increments('id');
            $table->string('name');
            $table->string('guard_name');
            $table->timestamps();
        });

        Schema::create($tableNames['roles'], function (Blueprint $table) {
            $table->increments('id');
            $table->string('name');
            $table->string('guard_name');
            $table->timestamps();
        });

        Schema::create($tableNames['model_has_permissions'], function (Blueprint $table) use ($tableNames, $columnNames) {
            $table->unsignedInteger('permission_id');
            $table->string('model_type');
            $table->unsignedBigInteger($columnNames['model_morph_key']);
            $table->index([$columnNames['model_morph_key'], 'model_type', ]);

            $table->foreign('permission_id')
                ->references('id')
                ->on($tableNames['permissions'])
                ->onDelete('cascade');
            $table->primary(['permission_id', $columnNames['model_morph_key'], 'model_type'],
                    'model_has_permissions_permission_model_type_primary');
        });

        Schema::create($tableNames['model_has_roles'], function (Blueprint $table) use ($tableNames, $columnNames) {
            $table->unsignedInteger('role_id');
            $table->string('model_type');
            $table->unsignedBigInteger($columnNames['model_morph_key']);
            $table->index([$columnNames['model_morph_key'], 'model_type', ]);

            $table->foreign('role_id')
                ->references('id')
                ->on($tableNames['roles'])
                ->onDelete('cascade');
            $table->primary(['role_id', $columnNames['model_morph_key'], 'model_type'],
                    'model_has_roles_role_model_type_primary');
        });

        Schema::create($tableNames['role_has_permissions'], function (Blueprint $table) use ($tableNames) {
            $table->unsignedInteger('permission_id');
            $table->unsignedInteger('role_id');

            $table->foreign('permission_id')
                ->references('id')
                ->on($tableNames['permissions'])
                ->onDelete('cascade');

            $table->foreign('role_id')
                ->references('id')
                ->on($tableNames['roles'])
                ->onDelete('cascade');

            $table->primary(['permission_id', 'role_id']);
        });

        app('cache')
            ->store(config('permission.cache.store') != 'default' ? config('permission.cache.store') : null)
            ->forget(config('permission.cache.key'));

        // Tabel Siswa Parent
        Schema::create('tbl_siswa_has_parent', function (Blueprint $table) {
            $table->bigIncrements('id', 20);
            $table->unsignedBigInteger('parent_id');
            $table->unsignedBigInteger('siswa_id');
            $table->timestamp('created_at')->default(\DB::raw('CURRENT_TIMESTAMP'));
            $table->timestamp('updated_at')->default(\DB::raw('CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP'));

            $table->foreign('parent_id')
                ->references('id')
                ->on('tbl_user')
                ->onDelete('cascade');

            $table->foreign('siswa_id')
                ->references('id')
                ->on('tbl_siswa')
                ->onDelete('cascade');
        });

        // Tabel Log Assessment
        Schema::create('tbl_assessment_log', function (Blueprint $table) {
            $table->bigIncrements('id', 20);
            $table->unsignedBigInteger('siswa_id')->nullable();
            $table->string('assessment');
            $table->string('range');
            $table->string('note');
            $table->dateTime('date');
            $table->timestamp('created_at')->default(\DB::raw('CURRENT_TIMESTAMP'));
            $table->timestamp('updated_at')->default(\DB::raw('CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP'));
            
            $table->foreign('siswa_id')
            ->references('id')
            ->on('tbl_siswa')
            ->onDelete('set null');
        });

        // Tabel Log Action
        Schema::create('tbl_action_log', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('user_id')->nullable();
            $table->integer('action_type');
            $table->integer('is_error');
            $table->string('action_message');
            $table->dateTime('date');
            $table->timestamp('created_at')->default(\DB::raw('CURRENT_TIMESTAMP'));
            $table->timestamp('updated_at')->default(\DB::raw('CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP'));

            $table->foreign('user_id')
            ->references('id')
            ->on('tbl_user')
            ->onDelete('set null');
        });

        // Tabel Notifikasi
        Schema::create('tbl_notification', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->integer('notification_type');
            $table->string('notification_title');
            $table->string('notification_message');
            $table->dateTime('date');
            $table->timestamp('created_at')->default(\DB::raw('CURRENT_TIMESTAMP'));
            $table->timestamp('updated_at')->default(\DB::raw('CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP'));
        });

        // Tabel Notifikasi User
        Schema::create('tbl_user_notification', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('user_id')->nullable();
            $table->unsignedBigInteger('notification_id')->nullable();
            $table->integer('status');
            $table->timestamp('created_at')->default(\DB::raw('CURRENT_TIMESTAMP'));
            $table->timestamp('updated_at')->default(\DB::raw('CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP'));

            $table->foreign('user_id')
            ->references('id')
            ->on('tbl_user')
            ->onDelete('set null');

            $table->foreign('notification_id')
            ->references('id')
            ->on('tbl_notification')
            ->onDelete('set null');
        });

        // Tabel Reset Password
        Schema::create('password_resets', function (Blueprint $table) {
            $table->string('email')->index();
            $table->string('token');
            $table->timestamp('created_at')->nullable();
        });

        // Tabel User Login Histori
        Schema::create('tbl_user_login_history', function (Blueprint $table) {
            $table->bigIncrements('id', 20);
            $table->unsignedBigInteger('user_id');
            $table->string('last_login_ip')->nullable();
            $table->dateTime('date')->nullable();
            $table->timestamp('created_at')->default(\DB::raw('CURRENT_TIMESTAMP'));
            $table->timestamp('updated_at')->default(\DB::raw('CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP'));

            $table->foreign('user_id')
                ->references('id')
                ->on('tbl_user')
                ->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('tbl_class');
        Schema::dropIfExists('tbl_user');
        Schema::dropIfExists('tbl_surah');
        Schema::dropIfExists('tbl_user_token');
        Schema::dropIfExists('tbl_iqro');
        Schema::dropIfExists('tbl_system_log');
        Schema::dropIfExists('tbl_siswa');
        Schema::dropIfExists('tbl_siswa_has_surah');
        Schema::dropIfExists('tbl_siswa_has_iqro');
        Schema::dropIfExists('tbl_report_print_log');
        Schema::dropIfExists('tbl_global_setting');

        $tableNames = config('permission.table_names');
        Schema::drop($tableNames['role_has_permissions']);
        Schema::drop($tableNames['model_has_roles']);
        Schema::drop($tableNames['model_has_permissions']);
        Schema::drop($tableNames['roles']);
        Schema::drop($tableNames['permissions']);

        Schema::dropIfExists('tbl_siswa_has_parent');
        Schema::dropIfExists('tbl_assessment_log');
        Schema::dropIfExists('tbl_action_log');
        Schema::dropIfExists('tbl_notification');
        Schema::dropIfExists('tbl_user_notification');
        Schema::dropIfExists('tbl_password_resets');
        Schema::dropIfExists('tbl_user_login_history');
    }
}
