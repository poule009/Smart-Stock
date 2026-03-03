<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('fournisseurs', function (Blueprint $table) {
            $table->id();
            $table->string('nom');
            $table->string('email')->unique();
            $table->string('telephone');
            $table->text('adresse')->nullable();
            $table->timestamp('cree_le')->nullable()->useCurrent();
            $table->timestamp('mise_a_jour_le')->nullable()->useCurrent()->useCurrentOnUpdate();
            $table->softDeletes('supprime_le');
            
           
            $table->index('nom');
            $table->index('telephone');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('fournisseurs');
    }
};
