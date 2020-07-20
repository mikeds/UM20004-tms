<?php
function force_ssl() {

    $host_arr = array(
        array(
            DEV_URL, true 
        ),
        array(
            STAG_URL, true 
        ),
        array(
            PROD_URL, true 
        ),
    );

    foreach ($host_arr as $i => $host) {
        if (preg_match("/\b{$host[0]}\b/", $_SERVER['HTTP_HOST'])) {
            if ($host[1] == false) break;
           
            $CI =& get_instance();
            $CI->config->config['base_url'] = str_replace('http://', 'https://', $CI->config->config['base_url']);
            if ($_SERVER['SERVER_PORT'] != 443) redirect($CI->uri->uri_string());
            break;
        }
    }
}
?>