<?php

global $wpdb;

define( 'HIDSF_URL', plugin_dir_url( __FILE__ ) );
define( 'HIDSF_DIR', plugin_dir_path( __FILE__ ) );
define( 'HIDSF_TABLE_PREFIX', $wpdb->prefix . 'hidsf_' );

const HIDSF_JS_URL  = HIDSF_URL . 'js';
const HIDSF_CSS_URL = HIDSF_URL . 'css';
const HIDSF_LIB_DIR    = HIDSF_DIR . 'lib';
const HIDSF_CORE_DIR   = HIDSF_DIR . 'core';
const HIDSF_MODELS_DIR = HIDSF_DIR . 'models';
const HIDSF_MIGRATIONS_DIR    = HIDSF_DIR . 'migrations';
const HIDSF_MODULE_DIR = HIDSF_DIR . 'modules';
const HIDSF_AJAX_DIR   = HIDSF_DIR . 'ajax';

