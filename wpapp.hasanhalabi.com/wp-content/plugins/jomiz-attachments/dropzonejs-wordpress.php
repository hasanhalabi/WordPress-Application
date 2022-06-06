<?php

/*
 * Plugin Name: DropzoneJS & WordPress
 * Version: 0.0.1
 * Description: Demos DropzoneJS in WordPress
 * Author: Per Soderlind
 * Author URI: https://soderlind.no
 * Plugin URI: https://gist.github.com/soderlind/f9e8b06cc205fb8c493d
 * License: GPL
 */
include_once 'jomiz.attachment.class.php';
define('DROPZONEJS_PLUGIN_URL', plugin_dir_url(__FILE__));
define('DROPZONEJS_PLUGIN_VERSION', '0.0.1');

add_action('plugins_loaded', 'dropzonejs_init');

function dropzonejs_init() {
    add_action('wp_enqueue_scripts', 'dropzonejs_enqueue_scripts');
    add_shortcode('dropzonejs', 'dropzonejs_shortcode');
}

function dropzonejs_enqueue_scripts() {

    // Load custom dropzone javascript
    wp_enqueue_script('customdropzonejs', DROPZONEJS_PLUGIN_URL . '/customize_dropzonejs.js', array(
        'dropzone'
            ), DROPZONEJS_PLUGIN_VERSION);
}

// Add Shortcode
function dropzonejs_shortcode($atts) {
    $atts = shortcode_atts(array(
        'module' => 'no-module',
        'objecttype' => 'no-object-type',
        'objectid' => '-1',
			'style' => 'thumbs'
            ), $atts, 'dropzonejs');

    $module = $atts ['module'];
    $objecttype = $atts ['objecttype'];
    $objectid = $atts ['objectid'];
	$style = $atts ['style'];

    $attachmets = jomiz_attachments::getAttachments($module, $objecttype, $objectid);
    $attachmets_html = '';
    $url = admin_url('admin-ajax.php');
    $nonce_delete_files = wp_nonce_field('delete_content', 'my_nonce_field');
	
	
	
	$attachment_template = '<div class="file-box hidden-print">
								<div class="can-delete">
									<form action="%6$s" class="needsclick" >
										<input type="hidden" name="objectid" value="%9$s">
										<input type="hidden" name="objecttype" value="%8$s">
										<input type="hidden" name="attachment_name" value="%3$s">
										<input type="hidden" name="module" value="%7$s">                                                        
										<input type="hidden" name="attachment_link" value="%1$s">
										<button type="submit" class="btn-danger btn btn-outline" name="action" value="delete_dropzonejs"><i class="fa fa-trash-o"></i></button>
										%10$s
									</form>
								</div>
                                <div class="file">
                                    <a href="%1$s" target="_blank">
                                        <span class="corner"></span>
                                        %2$s
                                        <div class="file-name">
											<div class="file-name-content">
	                                            %3$s
	                                            <br/>
	                                            <small>%4$s: %5$s</small>
											</div>
                                        </div>
                                    </a>
                                </div>
                            </div>';
	if($style == "list"){
		$attachment_template = '<tr">
								<td>
									<a href="%1$s" target="_blank">
                                        %2$s
                                    </a>
								</td>
                                <td>
                                    <a href="%1$s" target="_blank">
                                        %3$s
                                    </a>
                                </td>
								<td  class="can-delete">
									<form action="%6$s" class="needsclick" >
										<input type="hidden" name="objectid" value="%9$s">
										<input type="hidden" name="objecttype" value="%8$s">
										<input type="hidden" name="attachment_name" value="%3$s">
										<input type="hidden" name="module" value="%7$s">                                                        
										<input type="hidden" name="attachment_link" value="%1$s">
										<button type="submit" class="btn-danger btn btn-outline btn-sm" name="action" value="delete_dropzonejs"><i class="fa fa-trash-o"></i></button>
										%10$s
									</form>
								</td>
                            </tr>';
	}
	
    foreach ($attachmets as $attachmet) {
        $icon_html = '<div class="icon"><i class="fa fa-file"></i></div>';

        if ($attachmet->is_image && $style == 'thumbs') {
            $icon_html = sprintf('<div class="image">
                                            <img alt="%1$s" class="img-responsive" src="%2$s">
                                        </div>', $attachmet->attachment_name, wp_get_attachment_thumb_url($attachmet->attachment_id));
        }
        $attachmets_html .= vsprintf($attachment_template, array(
            $attachmet->attachment_link, //1
            $icon_html,//2
            $attachmet->attachment_name, //3
            __('Added', 'jomiz-attachments'), //4
            $attachmet->created, //5
            $url, //6
            $module, //7
            $objecttype, //8
            $objectid, //9
            $nonce_delete_files //10
        ));
    }



    $nonce_files = wp_nonce_field('protect_content', 'my_nonce_field');
    $attchment_title = __('Attachments', 'jomiz-attachments');

    $dropzone_col_class = "col-xs-12";
	$can_current_user_delete = "";
	if(!current_user_can('administrator')){
		$can_current_user_delete = '<style type="text/css">
.can-delete{
	display:none !important;
}
</style>';
	}
	
    if (!empty($attachmets_html)) {
		if ($style == 'thumbs') {
			$attachmets_html = $can_current_user_delete.sprintf('<div class="col-xs-12 col-sm-6 col-md-8 animated fadeInRight">%1$s</div>', $attachmets_html);
			$dropzone_col_class = "col-xs-12 col-sm-6 col-md-4";
		} else {
			$attachmets_html = $can_current_user_delete.sprintf('<div class="col-xs-12 animated fadeInRight"><div class="table-responsive"><table class="table table-striped"><tbody>%1$s</tbody></table></div></div>', $attachmets_html);
		}
    }

    return <<<ENDFORM
	<div class="ibox hidden-print">
                  <div class="ibox-title">
                    <h5>$attchment_title</h5>
                    <div class="ibox-tools">

                      
                      <a class="close-link">
                        <i class="fa fa-times"></i>
                      </a>
                    </div>
                  </div>
                  <div class="ibox-content">
                  <div class="row">$attachmets_html<div class="$dropzone_col_class">
					<div  id="dropzone-wordpress"><form action="$url?module=$module&objecttype=$objecttype&objectid=$objectid" class="dropzone dz-square needsclick dz-clickable" id="dropzone-wordpress-form">
	
            $nonce_files
	<div class="dropzone-previews">
            
            </div>
	<div class="dz-message needsclick dz-default">
  	</div>
	<input type='hidden' name='action' value='submit_dropzonejs'>
</form></div></div></div>
	</div>
	</div>
ENDFORM;
}

add_action('wp_ajax_nopriv_submit_dropzonejs', 'dropzonejs_upload');
add_action('wp_ajax_nopriv_delete_dropzonejs', 'dropzonejs_delete'); // allow on front-end
add_action('wp_ajax_submit_dropzonejs', 'dropzonejs_upload');
add_action('wp_ajax_delete_dropzonejs', 'dropzonejs_delete');

/**
 * dropzonejs_upload() handles the AJAX request, learn more about AJAX in Plugins at https://codex.wordpress.org/AJAX_in_Plugins
 *
 * @return [type] [description]
 */
function dropzonejs_delete() {


    $url_arry = explode('/', $_GET['_wp_http_referer']);

    $file_link = $_GET ['attachment_link'];
    $img_name = str_replace('uploads', '', (substr($file_link, strpos($file_link, "uploads"), strlen($file_link))));

    $path_to_delete = "";
    $upload_dir = wp_upload_dir();
    $path_to_delete = $upload_dir['basedir'] . $img_name;


    //unlink($fullPath);
    wp_delete_file($path_to_delete);

    jomiz_attachments::deleteAttachments($_GET ['module'], $_GET ['objecttype'], $_GET ['objectid'], $_GET ['attachment_name']);
    $url = home_url($url_arry[2] . "/" . $url_arry[3] . "?id=" . $_GET ['objectid']);
    wp_redirect($url);
    exit;
}

function dropzonejs_upload() {
    if (!empty($_FILES) && wp_verify_nonce($_REQUEST ['my_nonce_field'], 'protect_content')) {
        $filename = $_FILES ['file'] ['name'];
        $uploaded_bits = wp_upload_bits($filename, null, file_get_contents($_FILES ['file'] ['tmp_name']));
        
        if (false !== $uploaded_bits ['error']) {
            $error = $uploaded_bits ['error'];
            return add_action('admin_notices', function () use ($error) {
                $msg [] = '<div class="error"><p>';
                $msg [] = '<strong>DropzoneJS & WordPress</strong>: ';
                $msg [] = sprintf(__('wp_upload_bits failed,  error: "<strong>%s</strong>'), $error);
                $msg [] = '</p></div>';
                echo implode(PHP_EOL, $msg);
            });
        }

        $uploaded_file = $uploaded_bits ['file'];
        $uploaded_url = $uploaded_bits ['url'];
        $uploaded_filetype = wp_check_filetype(basename($uploaded_file), null);

        $wp_filetype = wp_check_filetype($filename, null);
        $post_title = preg_replace('/\.[^.]+$/', '', $filename);
        $attachment = array(
            'post_mime_type' => $wp_filetype ['type'],
            'post_parent' => $parent_post_id,
            'post_title' => $post_title,
            'post_content' => '',
            'post_status' => 'inherit'
        );
        $attachment_id = wp_insert_attachment($attachment, $uploaded_file, $parent_post_id);
        if (!is_wp_error($attachment_id)) {
            require_once (ABSPATH . "wp-admin" . '/includes/image.php');
            $attachment_data = wp_generate_attachment_metadata($attachment_id, $uploaded_file);
            
             wp_update_attachment_metadata($attachment_id, $attachment_data);
            
        }

        $is_image = 0;
        if (strpos($uploaded_filetype ['type'], 'image') !== false) {
            $is_image = 1;
        }
        $insert_id = jomiz_attachments::saveAttachments($_GET ['module'], $_GET ['objecttype'], $_GET ['objectid'], $attachment_id, sprintf('%1$s.%2$s', $post_title, $uploaded_filetype ['ext']), $is_image);
    }
    die();
}
