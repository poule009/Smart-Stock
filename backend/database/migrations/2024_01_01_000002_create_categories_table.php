<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('categories', function (Blueprint $table) {
            $table->id();
            $table->string('nom');
            $table->timestamp('cree_le')->nullable()->useCurrent();
            $table->timestamp('mise_a_jour_le')->nullable()->useCurrent()->useCurrentOnUpdate();
            $table->softDeletes('supprime_le');
            
          
            $table->index('nom');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('categories');
    }
};
