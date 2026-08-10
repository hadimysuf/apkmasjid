<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');
//echo "Test"; die();
//$config['base_url'] = (isset($_SERVER['HTTPS']) ? "https://" : "http://").$_SERVER['HTTP_HOST'];

//$config['base_url'] 			= "http://".$_SERVER['HTTP_HOST'];
//&& !isset($_SERVER['HTTPS'])
/*
if($_SERVER['SERVER_NAME'] == 'tjsl.peruri.co.id' ){
    //$config['base_url'] 			= "https://".$_SERVER['HTTP_HOST'];
    //header("location: https://tjslaskrindo.id");
    header("location: https://tjsl.peruri.co.id");
}
*/
if($_SERVER['SERVER_NAME'] == 'tjsl.peruri.co.id' && !isset($_SERVER['HTTPS'])){
    //$config['base_url'] 			= "https://".$_SERVER['HTTP_HOST'];
    header("location: https://tjsl.peruri.co.id");
}else{
    $config['base_url'] = (isset($_SERVER['HTTPS']) ? "https://" : "http://").$_SERVER['HTTP_HOST'];
}


$config['base_url'] 			.= preg_replace('@/+$@','',dirname($_SERVER['SCRIPT_NAME'])).'/';
$config['app_name']				= 'SISTEM PELAPORAN TJSL';
$config['vendor']				= 'PT Efea Inovasi Solusi';
$config['app_name_complete1']	= 'Sistem Pelaporan TJSL';
$config['company']				= 'EFEA';
$config['company_link']			= 'https://www.efea.id/';
$config['index_page'] 			= "";
$config['uri_protocol']			= "AUTO";
$config['url_suffix'] 			= "";
$config['language']				= "english";
$config['charset'] 				= "UTF-8";
$config['enable_hooks'] 		= FALSE;
$config['subclass_prefix'] 		= 'MY_';
$config['permitted_uri_chars'] 	= 'a-z 0-9~%.:_\-\=';
$config['enable_query_strings'] = TRUE;
$config['controller_trigger'] 	= 'c';
$config['function_trigger'] 	= 'm';
$config['directory_trigger'] 	= 'd'; // experimental not currently in use
$config['log_threshold'] 		= 4;
$config['log_path'] 			= '';
$config['log_date_format'] 		= 'Y-m-d H:i:s';
$config['cache_path'] 			= '';
$config['encryption_key'] 		= "dfALfpwMG98smd764JfpdfCVB00prr";
$config['sess_cookie_name']		= 'cisession_prr';
$config['sess_expiration']		= 180000;// 3000 menit sess timeout di server
$config['sess_encrypt_cookie']	= TRUE;
$config['sess_use_database']	= FALSE;
$config['sess_table_name']		= 'cisessions_ask';
$config['sess_match_ip']		= FALSE;
$config['sess_match_useragent']	= TRUE;
$config['sess_time_to_update'] 	= 300;
$config['cookie_prefix']		= "";
$config['cookie_domain']		= "";
$config['cookie_path']			= "/";
$config['global_xss_filtering'] = TRUE;
$config['compress_output'] 		= FALSE;
$config['time_reference'] 		= 'local';
$config['rewrite_short_tags'] 	= FALSE;
$config['proxy_ips'] 			= '';
$config['estimateaging_year']	= 2021;
$config['upload_path']          = 'assets/docs/va/';
$config['tokenapistatic']       = 'sayasukasatekambing';
$config['cookie_httponly'] = TRUE;
$config['cookie_secure'] = TRUE;
//echo $_SERVER['SERVER_NAME']; die();
if($_SERVER['SERVER_NAME'] == 'localhost'){
    //$config['base_url'] 			= "https://".$_SERVER['HTTP_HOST'];
    $config['backup_dir']	= '/Users/anggasaputra/Sites/tjsl_askrindo/assets/backup_db/';
   
}elseif($_SERVER['SERVER_NAME'] == 'tjsl.efea.id'){
    $config['backup_dir']	= '/var/www/html/dev_tjsl_askrindo/assets/backup_db/';
}elseif($_SERVER['SERVER_NAME'] == 'tjs.peruri.co.id'){
    
    $config['backup_dir']	= '/var/www/html/assets/backup_db/';
}else{
    $config['backup_dir']	= '/var/www/html/assets/backup_db/';
}


/* End of file config.php */

