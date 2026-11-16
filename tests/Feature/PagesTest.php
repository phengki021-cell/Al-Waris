<?php

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Route;

use App\Models\User;
use Illuminate\Support\Facades\Hash;

/**
 * cuplikan konten per halaman untuk navigasi menu edukasi Tahap 2.
 */
it('halaman publik seluruhnya dapat dibuka', function (string $route, string $marker) {
    $this->get(route($route))->assertOk()->assertSee($marker);
})->with([
    'Beranda' => ['home', 'Al-Waris'],
    'Ahli Waris' => ['heirs', 'Ahli Waris'],
    'Bagian-Bagian Warisan' => ['shares', 'Bagian'],
    'Cara Menghitung' => ['how-to', 'Menghitung'],
    'Kalkulator' => ['calculate', 'Hitung Warisan'],
    'Riwayat' => ['history', 'Riwayat Perhitungan'],
    'Tentang' => ['about', 'Tentang'],
    'Privasi' => ['privacy', 'Privasi'],
    'Referensi' => ['sources', 'Referensi'],
    'Instalasi PWA' => ['install', 'Pasang Al-Waris'],
    'Offline' => ['offline', 'offline'],
]);

it('seluruh rute bernama terdefinisi dan tidak mencekik', function () {
    foreach (['home', 'heirs', 'shares', 'how-to', 'calculate', 'history', 'about', 'privacy', 'sources', 'install', 'offline', 'manifest', 'sw'] as $name) {
        expect(Route::has($name))->toBeTrue("Rute '$name' harus ada");
    }
});

it('menampilkan kredit pengembang Hengki Pranata di halaman publik', function (string $route) {
    $this->get(route($route))
        ->assertOk()
        ->assertSee('Hengki Pranata')
        ->assertSee('Warisan Islam, Untuk Masa Depan');
})->with([
    'Beranda' => 'home',
    'Tentang' => 'about',
    'Kalkulator' => 'calculate',
]);

it('halaman tentang menyajikan seksi Tentang Pengembang', function () {
    $this->get(route('about'))
        ->assertOk()
        ->assertSee('Tentang Pengembang')
        ->assertSee('Dikembangkan oleh')
        ->assertSee('HP');
});

it('halaman 404 informatif berbahasa Indonesia', function () {
    $this->get('/halaman-tidak-ada-xyz')
        ->assertNotFound()
        ->assertSee('Halaman tidak ditemukan')
        ->assertSee('Ke Beranda');
});

it('halaman ahli waris menyajikan materi ashabah, hijab, dan ahli waris pengganti', function () {
    $this->get(route('heirs'))
        ->assertOk()
        ->assertSee('Ashabah')
        ->assertSee('bil ghair')
        ->assertSee('bertakhsis')
        ->assertSee('Hijab')
        ->assertSee('mahjub')
        ->assertSee('Hijab nuqshan')
        ->assertSee('Ahli Waris Pengganti')
        ->assertSee('Mawla')
        ->assertSee('Musafir al-Islam');
});

it('data istri menampilkan bagian maksimum 1/4 dan saudara sedarah laki-laki berkategori asabah', function () {
    $this->seed(Database\Seeders\HeirSeeder::class);

    $istri = App\Models\Heir::query()->where('code', 'wife')->first();
    expect($istri)->not->toBeNull()
        ->and((int) $istri->max_share_numerator)->toBe(1)
        ->and((int) $istri->max_share_denominator)->toBe(4);

    $sedarah = App\Models\Heir::query()->where('code', 'sibling_male_full')->first();
    expect($sedarah)->not->toBeNull()
        ->and($sedarah->category)->toBe('asabah')
        ->and($sedarah->max_share_numerator)->toBeNull();
});

it('ahli waris berperan ganda tampil di kedua golongan dengan chip penjelas', function () {
    $this->seed(Database\Seeders\HeirSeeder::class);

    $this->get(route('heirs'))
        ->assertOk()
        ->assertSee('Juga berperan: Ashabah bil ghair')            // kartu di golongan Ashabah
        ->assertSee('Tampil juga di golongan Ashabah')         // kartu asli di Dzawil Furudh
        ->assertSee('Ashabah binafsih — ketika pewaris hanya menyisakan anak perempuan');
});

it('panel admin: tamu diarahkan ke login, login salah ditolak, admin dapat dashboard & CRUD', function () {
    $this->seed(Database\Seeders\AdminSeeder::class);

    // Tamu ke dashboard → redirect ke login
    $this->get(route('admin.dashboard'))->assertRedirect(route('admin.login'));

    // Halaman login terbuka
    $this->get(route('admin.login'))->assertOk()->assertSee('Panel Admin');

    // Login salah → kembali dengan error
    $this->post(route('admin.attempt'), ['email' => 'admin@alwariscom.online', 'password' => 'salah-xyz'])
        ->assertSessionHasErrors('email');

    // Login benar → dashboard
    $admin = User::query()->where('email', 'admin@alwariscom.online')->firstOrFail();
    $this->actingAs($admin)->get(route('admin.dashboard'))
        ->assertOk()
        ->assertSee('Dashboard')
        ->assertSee('Kelola Ahli Waris');

    // Halaman CRUD ahli waris terbuka untuk admin
    $this->actingAs($admin)->get(route('admin.heirs'))->assertOk()->assertSee('Kelola Ahli Waris');

    // Akun bukan admin → 403
    $biasa = User::create(['name' => 'Pengguna Biasa', 'email' => 'biasa@alwariscom.online', 'password' => Hash::make('rahasia-123'), 'is_admin' => false]);
    $this->actingAs($biasa)->get(route('admin.dashboard'))->assertForbidden();

    // Logout
    $this->actingAs($admin)->post(route('admin.logout'))->assertRedirect(route('admin.login'));
});

it('admin dapat mengelola bagian warisan, referensi, dan profil (ganti sandi)', function () {
    $this->seed(Database\Seeders\AdminSeeder::class);
    $this->seed(Database\Seeders\HeirSeeder::class);
    $admin = User::query()->where('email', 'admin@alwariscom.online')->firstOrFail();

    // Daftar & form bagian warisan + referensi
    $this->actingAs($admin)->get(route('admin.shares'))->assertOk()->assertSee('Kelola Bagian Warisan');
    $this->actingAs($admin)->get(route('admin.shares.create'))->assertOk()->assertSee('Tambah Bagian Warisan');
    $this->actingAs($admin)->get(route('admin.sources'))->assertOk()->assertSee('Kelola Referensi');
    $this->actingAs($admin)->get(route('admin.sources.create'))->assertOk()->assertSee('Tambah Referensi');

    // Tambah referensi baru
    $this->actingAs($admin)->post(route('admin.sources.store'), [
        'code' => 'test-src',
        'title' => 'Referensi Uji Otomatis',
        'type' => 'kitab',
        'reference' => 'QS. An-Nisaa: 11',
        'is_primary' => '0',
    ])->assertRedirect(route('admin.sources'))->assertSessionHas('status');
    $source = App\Models\Source::query()->where('code', 'test-src')->firstOrFail();
    expect($source->title)->toBe('Referensi Uji Otomatis');

    // Tambah bagian warisan (condition_hash otomatis md5)
    $heir = App\Models\Heir::query()->where('code', 'husband')->first();
    $this->actingAs($admin)->post(route('admin.shares.store'), [
        'heir_id' => $heir->id,
        'numerator' => 1,
        'denominator' => 3,
        'condition' => 'Bila pewaris tidak memiliki keturunan (uji admin)',
    ])->assertRedirect(route('admin.shares'))->assertSessionHas('status');
    $share = App\Models\Share::query()
        ->where('heir_id', $heir->id)
        ->where('numerator', 1)
        ->where('denominator', 3)
        ->where('condition_hash', md5('Bila pewaris tidak memiliki keturunan (uji admin)'))
        ->firstOrFail();
    expect($share)->not->toBeNull();

    // Profil admin: ganti sandi salah → error; benar → sukses; login dengan sandi baru
    $this->actingAs($admin)->put(route('admin.profile.password'), [
        'kata_sandi_lama' => 'salah-xyz',
        'kata_sandi_baru' => 'sandi-baru-1234',
        'kata_sandi_baru_konfirmasi' => 'sandi-baru-1234',
    ])->assertSessionHasErrors('kata_sandi_lama');

    $this->actingAs($admin)->put(route('admin.profile.password'), [
        'kata_sandi_lama' => 'Alwaris-Admin#2026',
        'kata_sandi_baru' => 'sandi-baru-1234',
        'kata_sandi_baru_konfirmasi' => 'sandi-baru-1234',
    ])->assertSessionHas('status');
    expect(Hash::check('sandi-baru-1234', $admin->fresh()->password))->toBeTrue();
});

it('menyajikan manifest dengan ikon disk yang ada', function () {
    foreach (['icon-192.png', 'icon-512.png', 'maskable-192.png', 'maskable-512.png', 'apple-touch-icon-180.png', 'favicon-48.png'] as $icon) {
        expect(is_file(public_path("icons/{$icon}")))->toBeTrue("Ikon {$icon} harus ada");
    }
});
