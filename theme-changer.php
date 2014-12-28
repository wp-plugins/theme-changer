<?php
/*
Plugin Name: Theme Changer
Plugin URI: http://www.elegants.biz/products/theme-changer/
Description: Easy theme change in the get parameter. this to be a per-session only change, and one that everyone (all visitors) can use. I just enter the following URL. It's easy. e.g. http://wordpress_install_domain/?theme_changer=theme_folder_name
Version: 1.0
Author: momen2009
Author URI: http://www.elegants.biz/
License: GPLv2 or later
 */
 
/*  Copyright 2014 –Ø–È‚Ì—D‰ë‚Èˆê“ú (email : momen.yutaka@gmail.com)

This program is free software; you can redistribute it and/or modify
it under the terms of the GNU General Public License, version 2, as
published by the Free Software Foundation.

This program is distributed in the hope that it will be useful,
but WITHOUT ANY WARRANTY; without even the implied warranty of
MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
GNU General Public License for more details.

You should have received a copy of the GNU General Public License
along with this program; if not, write to the Free Software
Foundation, Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301  USA
 */

$theme_changer_theme;

function add_meta_query_vars( $public_query_vars ) {
    if(is_admin()) return;
    $public_query_vars[] = "theme_changer";
    return $public_query_vars;
}

function theme_changer(){
    if(is_admin()) return;
    global $wpdb;
    if (!isset($_SESSION)) {
        session_start();
    }
    $theme_changer = $wpdb->escape($_GET["theme_changer"]);
    if(isset($theme_changer) && $theme_changer != ""){
        $theme_changer = $wpdb->escape($_GET["theme_changer"]);
    }elseif(isset($_SESSION["theme_changer"])){
        $theme_changer = $_SESSION["theme_changer"];
    }
    if($value = exist_search_theme($theme_changer)){
        global $theme_changer_theme;
        $theme_changer_theme = $value -> get_stylesheet();
        $_SESSION["theme_changer"]=$theme_changer;
    }
}

function exist_search_theme($stylesheet){
    foreach(get_themes() as $value){
        if($value->get_stylesheet() == $stylesheet) return $value;
    }
    return false;
}

function my_theme_switcher($theme){
    global $theme_changer_theme;
    if(exist_search_theme($theme_changer_theme)){
        $overrideTheme = wp_get_theme($theme_changer_theme);
        if ($overrideTheme->exists()) {
            return $overrideTheme['Template'];
        } else {
            return $theme;
        }
    }else{
        return $theme;
    }
}

if(!is_admin()){
    add_filter("query_vars","add_meta_query_vars");
    add_filter("setup_theme","theme_changer");
    add_filter('stylesheet', 'my_theme_switcher');
    add_filter('template', 'my_theme_switcher');
}
?>