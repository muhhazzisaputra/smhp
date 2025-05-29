<?php

use CodeIgniter\Router\RouteCollection;

/**
 * @var RouteCollection $routes
 */

// controller khusus utk testing
$routes->get('/testing', 'Testing::index');

$routes->get('/', 'Auth::index');
$routes->post('/auth/process', 'Auth::process');
$routes->get('/logout', 'Auth::logout');
$routes->get('/home', 'Home::index');
$routes->get('/dashboard', 'Dashboard::index');

$routes->get('users', 'Users::index'); // To show the view with DataTable
$routes->post('users/datatables', 'Users::datatables'); // Server-side endpoint for DataTable
$routes->post('users/update', 'Users::update');
$routes->post('users/delete', 'Users::delete');

// Mesin
$routes->get('mesin', 'Mesin::index');
$routes->post('mesin/datatables', 'Mesin::datatables');
$routes->post('mesin/form_data', 'Mesin::form_data');
$routes->post('mesin/simpan_data', 'Mesin::simpan_data');
$routes->post('mesin/update_data', 'Mesin::update_data');
$routes->post('mesin/hapus_data', 'Mesin::hapus_data');

// Pegawai
$routes->get('pegawai', 'Pegawai::index');
$routes->post('pegawai/datatables', 'Pegawai::datatables');
$routes->post('pegawai/form_data', 'Pegawai::form_data');
$routes->post('pegawai/simpan_data', 'Pegawai::simpan_data');
$routes->post('pegawai/update_data', 'Pegawai::update_data');
$routes->post('pegawai/hapus_data', 'Pegawai::hapus_data');
$routes->post('pegawai/export_xls', 'Pegawai::export_xls');

// Produk
$routes->get('produk', 'Produk::index');
$routes->post('produk/datatables', 'Produk::datatables');
$routes->post('produk/form_data', 'Produk::form_data');
$routes->post('produk/simpan_data', 'Produk::simpan_data');
$routes->post('produk/update_data', 'Produk::update_data');
$routes->post('produk/hapus_data', 'Produk::hapus_data');

// Hasil Produksi
$routes->get('hasil_produksi', 'HasilProduksi::index');
$routes->post('hasil_produksi/datatables', 'HasilProduksi::datatables');
$routes->post('hasil_produksi/form_data', 'HasilProduksi::form_data');
$routes->post('hasil_produksi/cek_tgl_shift', 'HasilProduksi::cek_tgl_shift');
$routes->post('hasil_produksi/jam_shift', 'HasilProduksi::jam_shift');
$routes->post('hasil_produksi/simpan_data', 'HasilProduksi::simpan_data');
$routes->post('hasil_produksi/update_data', 'HasilProduksi::update_data');
$routes->post('hasil_produksi/hapus_data', 'HasilProduksi::hapus_data');
$routes->post('hasil_produksi/export_xls', 'HasilProduksi::export_xls');

// Speed & Temperatur
$routes->get('speed_temperatur', 'SpeedTemperatur::index');
$routes->post('speed_temperatur/datatables', 'SpeedTemperatur::datatables');
$routes->post('speed_temperatur/export_xls', 'SpeedTemperatur::export_xls');

// Laporan Produksi
$routes->get('laporan_hasil_produksi', 'LaporanProduksi::index');
$routes->post('laporan_hasil_produksi/pilih_format', 'LaporanProduksi::pilih_format');
$routes->post('laporan_hasil_produksi/view_data', 'LaporanProduksi::view_data');
$routes->post('laporan_hasil_produksi/view_data/(:any)', 'LaporanProduksi::view_data/xls');
$routes->post('laporan_hasil_produksi/detail_hasil_pertgl', 'LaporanProduksi::detail_hasil_pertgl');
$routes->post('laporan_hasil_produksi/detail_hasil_pertgl/(:any)', 'LaporanProduksi::detail_hasil_pertgl/$1');
$routes->post('laporan_hasil_produksi/detail_hasil_permesin', 'LaporanProduksi::detail_hasil_permesin');
$routes->post('laporan_hasil_produksi/detail_hasil_permesin/(:any)', 'LaporanProduksi::detail_hasil_permesin/$1');