<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddSomeToProgramTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('program', function (Blueprint $table) {
            if(!Schema::hasColumn('program', 'gambar_bnsp')){
                $table->string('gambar_bnsp')->nullable();
            }
            if(!Schema::hasColumn('program','program_materi')){
                $table->string('program_materi');
            }
            if(!Schema::hasColumn('program','program_manfaat')){
                $table->string('program_manfaat');
            }
            
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('program', function (Blueprint $table) {
            //
        });
    }
}
