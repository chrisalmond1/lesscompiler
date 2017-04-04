<?php
/*
Plugin Name: AAP Less Compiler
Plugin URI: #
Description: Simple plugin that loads bootstrap and the required jquery into your theme.
Version: 1.0.0
Author: Chris Almond
Author URI: https://github.com/chrisalmond1/
Text Domain: lesscompiler
*/

// Setup Less
require("lib/lessc.inc.php");
$inputFile = realpath(dirname(__FILE__)).'/css/style.less';
$outputFile = realpath(dirname(__FILE__)).'/css/style.css';
$less = new lessc;

$primary_color = (get_option('primary_color')) ? get_option('primary_color') : '#eee';

$less->setVariables(array(
    "primary-color" => $primary_color,
    "secondary-color" => '#EDEAE6'
));

$cache = $less->cachedCompile($inputFile);
file_put_contents($outputFile, $cache["compiled"]);
$last_updated = $cache["updated"];
$cache = $less->cachedCompile($cache);

if ($cache["updated"] > $last_updated) {
    file_put_contents($outputFile, $cache["compiled"]);
}

function add_app_less_css() {
    wp_enqueue_style( 'app_less_css', plugins_url('/css/style.css', __FILE__) );
}
add_action( 'wp_enqueue_scripts', 'add_app_less_css', 3);
