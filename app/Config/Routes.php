<?php

use CodeIgniter\Router\RouteCollection;

/**
 * @var RouteCollection $routes
 */
$routes->get('/', 'Peserta::index');
$routes->get('peserta', 'Peserta::index');
$routes->post('peserta/simpan', 'Peserta::simpan');
$routes->get('peserta/edit/(:num)', 'Peserta::edit/$1');
$routes->post('peserta/update', 'Peserta::update');
$routes->get('peserta/hapus/(:num)', 'Peserta::hapus/$1');
$routes->post('peserta/hapus_js', 'Peserta::hapus_js');
$routes->post('peserta/get_kabkota', 'Peserta::get_kabkota');