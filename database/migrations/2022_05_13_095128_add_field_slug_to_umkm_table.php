<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddFieldSlugToUmkmTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('umkm', function (Blueprint $table) {
            $table->string('slug')->nullable();
        });

        // generate slug name each umkm
        $umkms = \DB::table('umkm')->select(['umkm_id', 'nama'])->get();

        foreach($umkms as $umkm)
        {
            $slugName = \Str::slug($umkm->nama);

            \DB::table('umkm')->where('umkm_id', $umkm->umkm_id)->update(['slug' => $slugName]);
        }
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('umkm', function (Blueprint $table) {
            $table->dropColumn('slug');
        });
    }
}
