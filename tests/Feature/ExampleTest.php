<?php

// Tasim kasus dasar route, binaiya pengguna Tahap 1 ini.

test('halaman beranda dapat diakses', function () {
    $this->get(route('home'))->assertOk()->assertSee('Warisku');
});
