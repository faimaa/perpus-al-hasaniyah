<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * User Helper Functions
 * 
 * This helper contains functions for handling user-related operations
 * including photo management and display
 */

/**
 * Get user photo with fallback
 * 
 * @param string $foto Photo filename
 * @param string $nama User name for alt text
 * @param string $size Size class (sm, md, lg)
 * @return string HTML for photo display
 */
function get_user_photo($foto, $nama = '', $size = 'md') {
    $ci =& get_instance();
    
    // Size configurations
    $sizes = [
        'sm' => ['width' => '60px', 'height' => '60px', 'font' => '24px'],
        'md' => ['width' => '80px', 'height' => '80px', 'font' => '32px'],
        'lg' => ['width' => '120px', 'height' => '120px', 'font' => '48px'],
        'xl' => ['width' => '200px', 'height' => '150px', 'font' => '64px']
    ];
    
    $config = $sizes[$size] ?? $sizes['md'];
    
    // Check if photo exists and is valid
    if (!empty($foto) && $foto != "0" && file_exists('./assets_style/image/'.$foto)) {
        $alt = !empty($nama) ? "Foto $nama" : "Foto User";
        return '<img src="'.base_url('assets_style/image/'.$foto).'" 
                     alt="'.$alt.'" 
                     class="img-responsive img-thumbnail" 
                     style="width:'.$config['width'].';height:'.$config['height'].';object-fit:cover;border:2px solid #ddd;box-shadow:0 2px 4px rgba(0,0,0,0.1);">';
    } else {
        // Return placeholder avatar
        return '<div class="user-avatar-placeholder" 
                     style="display:inline-block;width:'.$config['width'].';height:'.$config['height'].';background:linear-gradient(135deg, #667eea 0%, #764ba2 100%);border-radius:50%;border:2px solid #ddd;box-shadow:0 2px 4px rgba(0,0,0,0.1);">
                    <i class="fa fa-user" style="color:#fff;font-size:'.$config['font'].';line-height:'.$config['height'].';text-align:center;display:block;"></i>
                </div>';
    }
}

/**
 * Get user level badge
 * 
 * @param string $level User level
 * @return string HTML for level badge
 */
function get_user_level_badge($level) {
    $badge_class = 'label-info';
    
    switch (strtolower($level)) {
        case 'admin':
            $badge_class = 'label-danger';
            break;
        case 'petugas':
            $badge_class = 'label-warning';
            break;
        case 'anggota':
            $badge_class = 'label-info';
            break;
        default:
            $badge_class = 'label-default';
    }
    
    return '<span class="label '.$badge_class.'">'.$level.'</span>';
}

/**
 * Validate photo file
 * 
 * @param string $foto Photo filename
 * @return bool True if photo is valid
 */
function is_valid_user_photo($foto) {
    if (empty($foto) || $foto == "0") {
        return false;
    }
    
    $file_path = './assets_style/image/'.$foto;
    return file_exists($file_path);
}

/**
 * Get photo file size in human readable format
 * 
 * @param string $foto Photo filename
 * @return string File size or "N/A"
 */
function get_photo_file_size($foto) {
    if (!is_valid_user_photo($foto)) {
        return "N/A";
    }
    
    $file_path = './assets_style/image/'.$foto;
    $size = filesize($file_path);
    
    $units = ['B', 'KB', 'MB', 'GB'];
    $i = 0;
    
    while ($size >= 1024 && $i < count($units) - 1) {
        $size /= 1024;
        $i++;
    }
    
    return round($size, 2) . ' ' . $units[$i];
} 