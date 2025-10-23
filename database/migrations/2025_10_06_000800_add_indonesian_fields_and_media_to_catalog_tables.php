<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
		// unit_ps: tambah kolom berbahasa Indonesia + media (hanya jika belum ada)
		if (!Schema::hasColumn('unit_ps', 'nama')) {
			Schema::table('unit_ps', function (Blueprint $table) {
				$table->string('nama')->nullable()->after('id');
			});
		}
		if (!Schema::hasColumn('unit_ps', 'merek')) {
			Schema::table('unit_ps', function (Blueprint $table) {
				$table->string('merek')->nullable()->after('nama');
			});
		}
		if (!Schema::hasColumn('unit_ps', 'model')) {
			Schema::table('unit_ps', function (Blueprint $table) {
				$table->string('model')->nullable()->after('merek');
			});
		}
		if (!Schema::hasColumn('unit_ps', 'nomor_seri')) {
			Schema::table('unit_ps', function (Blueprint $table) {
				$table->string('nomor_seri')->nullable()->after('model');
			});
		}
		if (!Schema::hasColumn('unit_ps', 'harga_per_jam')) {
			Schema::table('unit_ps', function (Blueprint $table) {
				$table->decimal('harga_per_jam', 10, 2)->nullable()->after('nomor_seri');
			});
		}
		if (!Schema::hasColumn('unit_ps', 'stok')) {
			Schema::table('unit_ps', function (Blueprint $table) {
				$table->integer('stok')->nullable()->after('harga_per_jam');
			});
		}
		if (!Schema::hasColumn('unit_ps', 'foto')) {
			Schema::table('unit_ps', function (Blueprint $table) {
				$table->string('foto')->nullable()->after('stok');
			});
		}
		if (!Schema::hasColumn('unit_ps', 'kondisi')) {
			Schema::table('unit_ps', function (Blueprint $table) {
				$table->string('kondisi')->nullable()->after('foto');
			});
		}
        DB::table('unit_ps')->update([
            'nama' => DB::raw('COALESCE(nama, name)'),
            'merek' => DB::raw('COALESCE(merek, brand)'),
            'nomor_seri' => DB::raw('COALESCE(nomor_seri, serial_number)'),
            'harga_per_jam' => DB::raw('COALESCE(harga_per_jam, price_per_hour)'),
            'stok' => DB::raw('COALESCE(stok, stock)'),
        ]);

		// games: tambah kolom berbahasa Indonesia + media (hanya jika belum ada)
		if (!Schema::hasColumn('games', 'judul')) {
			Schema::table('games', function (Blueprint $table) {
				$table->string('judul')->nullable()->after('id');
			});
		}
		if (!Schema::hasColumn('games', 'stok')) {
			Schema::table('games', function (Blueprint $table) {
				$table->integer('stok')->nullable()->after('judul');
			});
		}
		if (!Schema::hasColumn('games', 'harga_per_hari')) {
			Schema::table('games', function (Blueprint $table) {
				$table->decimal('harga_per_hari', 10, 2)->nullable()->after('stok');
			});
		}
		if (!Schema::hasColumn('games', 'gambar')) {
			Schema::table('games', function (Blueprint $table) {
				$table->string('gambar')->nullable()->after('harga_per_hari');
			});
		}
		if (!Schema::hasColumn('games', 'kondisi')) {
			Schema::table('games', function (Blueprint $table) {
				$table->string('kondisi')->nullable()->after('gambar');
			});
		}
        DB::table('games')->update([
            'judul' => DB::raw('COALESCE(judul, title)'),
            'stok' => DB::raw('COALESCE(stok, stock)'),
            'harga_per_hari' => DB::raw('COALESCE(harga_per_hari, price_per_day)'),
        ]);

		// accessories: tambah kolom berbahasa Indonesia + media (hanya jika belum ada)
		if (!Schema::hasColumn('accessories', 'nama')) {
			Schema::table('accessories', function (Blueprint $table) {
				$table->string('nama')->nullable()->after('id');
			});
		}
		if (!Schema::hasColumn('accessories', 'jenis')) {
			Schema::table('accessories', function (Blueprint $table) {
				$table->string('jenis')->nullable()->after('nama');
			});
		}
		if (!Schema::hasColumn('accessories', 'stok')) {
			Schema::table('accessories', function (Blueprint $table) {
				$table->integer('stok')->nullable()->after('jenis');
			});
		}
		if (!Schema::hasColumn('accessories', 'harga_per_hari')) {
			Schema::table('accessories', function (Blueprint $table) {
				$table->decimal('harga_per_hari', 10, 2)->nullable()->after('stok');
			});
		}
		if (!Schema::hasColumn('accessories', 'gambar')) {
			Schema::table('accessories', function (Blueprint $table) {
				$table->string('gambar')->nullable()->after('harga_per_hari');
			});
		}
		if (!Schema::hasColumn('accessories', 'kondisi')) {
			Schema::table('accessories', function (Blueprint $table) {
				$table->string('kondisi')->nullable()->after('gambar');
			});
		}
        DB::table('accessories')->update([
            'nama' => DB::raw('COALESCE(nama, name)'),
            'jenis' => DB::raw('COALESCE(jenis, type)'),
            'stok' => DB::raw('COALESCE(stok, stock)'),
            'harga_per_hari' => DB::raw('COALESCE(harga_per_hari, price_per_day)'),
        ]);
    }

    public function down(): void
    {
        Schema::table('unit_ps', function (Blueprint $table) {
            $table->dropColumn(['nama','merek','model','nomor_seri','harga_per_jam','stok','foto','kondisi']);
        });
        Schema::table('games', function (Blueprint $table) {
            $table->dropColumn(['judul','stok','harga_per_hari','gambar','kondisi']);
        });
        Schema::table('accessories', function (Blueprint $table) {
            $table->dropColumn(['nama','jenis','stok','harga_per_hari','gambar','kondisi']);
        });
    }
};


