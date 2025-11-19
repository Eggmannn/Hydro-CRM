<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('company_authorizations', function (Blueprint $table) {
            $table->id();
            // Use `integer` to match existing `company.id` (int(11))
            $table->integer('crd_admin_id')->nullable(false);
            $table->integer('company_id')->nullable(false);
            $table->integer('granted_by')->nullable();
            $table->timestamp('granted_at')->useCurrent();
            $table->timestamp('expires_at')->nullable();
            $table->string('reason')->nullable();
            $table->timestamps();

            $table->index('crd_admin_id');
            $table->index('company_id');

            // Foreign keys (match existing table names and types)
            $table->foreign('crd_admin_id')->references('id')->on('crd_admin')->onDelete('cascade');
            $table->foreign('company_id')->references('id')->on('company')->onDelete('cascade');
        });
    }

    public function down()
    {
        Schema::dropIfExists('company_authorizations');
    }
};
