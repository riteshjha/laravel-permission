<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Rkj\Permission\Models\Ability;
use Illuminate\Support\Str;

class CreatePermissionSetupTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('roles', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('name');
            $table->string('label')->nullable();
            $table->unsignedTinyInteger('group')->default(Ability::GROUP_ACCOUNT);
            $table->softDeletes();
            $table->timestamps();
        });

        Schema::create('abilities', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('name')->unique();
            $table->string('label')->nullable();
            $table->unsignedTinyInteger('group')->default(Ability::GROUP_ACCOUNT);
            $table->softDeletes();
            $table->timestamps();
        });

        Schema::create('abilitables', function (Blueprint $table) {
            $table->unsignedBigInteger('ability_id');
            $table->unsignedTinyInteger('level')->default(Ability::LEVEL_OWNER);
            $table->unsignedBigInteger('abilitable_id');
            $table->string('abilitable_type');
            $table->softDeletes();
            $table->timestamps();

            $table->foreign('ability_id')
                ->references('id')
                ->on('abilities')
                ->onDelete('cascade');
        });

        Schema::create('role_user', function (Blueprint $table) {

            $user_id = Str::of(config('permission.model.user'))
                    ->basename()->lower()->append('_id');

            $table->primary([$user_id, 'role_id']);

            $table->unsignedBigInteger($user_id);
            $table->unsignedBigInteger('role_id');
            $table->softDeletes();
            $table->timestamps();

            $table->foreign($user_id)
                ->references('id')
                ->on('users')
                ->onDelete('cascade');

            $table->foreign('role_id')
                ->references('id')
                ->on('roles')
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
        Schema::dropIfExists('role_user');
        Schema::dropIfExists('abilitables');
        Schema::dropIfExists('abilities');
        Schema::dropIfExists('roles');
    }
}
