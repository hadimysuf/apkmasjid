<?php
defined( 'BASEPATH' ) OR exit( 'No direct script access allowed' );
$active_group = 'default';
$active_record = TRUE;
$query_builder = TRUE;

$db['default']['dsn'] = '';
$db['default']['hostname'] = '192.168.100.146';// tjsl.efea.id tjsl2.efea.id
$db['default']['port'] = '9432';// 7400
$db['default']['username'] = 'pgtjsl';
$db['default']['database'] = 'tjsl_peruri';// dev_tjsl_peruri21022023dev_tjsl_peruri14022023
$db['default']['password'] = 'plokijuh890-MNB';// 
$db['default']['dbdriver'] = 'postgre';
$db['default']['dbprefix'] = '';
$db['default']['pconnect'] = FALSE;
$db['default']['db_debug'] = TRUE;
$db['default']['cache_on'] = FALSE;
$db['default']['cachedir'] = '';
$db['default']['char_set'] = 'utf8';
$db['default']['dbcollat'] = 'utf8_general_ci';
$db['default']['swap_pre'] = '';
$db['default']['encrypt'] = FALSE;
$db['default']['compress'] = FALSE;
$db['default']['stricton'] = FALSE;
$db['default']['failover'] = array();
$db['default']['save_queries'] = TRUE;



$db['dev']['dsn'] = '';
$db['dev']['hostname'] = 'tjsl.efea.id';// tjsl.efea.id tjsl2.efea.id
$db['dev']['port'] = '7400';// 7400
$db['dev']['username'] = 'pgtjsl';
$db['dev']['database'] = 'dev_tjsl_peruri_';// dev_tjsl_peruri21022023dev_tjsl_peruri14022023
$db['dev']['password'] = 'hujiko2002';// 
$db['dev']['dbdriver'] = 'postgre';
$db['dev']['dbprefix'] = '';
$db['dev']['pconnect'] = FALSE;
$db['dev']['db_debug'] = TRUE;
$db['dev']['cache_on'] = FALSE;
$db['dev']['cachedir'] = '';
$db['dev']['char_set'] = 'utf8';
$db['dev']['dbcollat'] = 'utf8_general_ci';
$db['dev']['swap_pre'] = '';
$db['dev']['encrypt'] = FALSE;
$db['dev']['compress'] = FALSE;
$db['dev']['stricton'] = FALSE;
$db['dev']['failover'] = array();
$db['dev']['save_queries'] = TRUE;
