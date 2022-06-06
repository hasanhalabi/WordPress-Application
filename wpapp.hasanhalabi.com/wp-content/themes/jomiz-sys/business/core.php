<?php
/*
 * function jomiz_include_directory($path) {
 *
 * $files = glob(sprintf('%s/*.php', $path));
 *
 * foreach ($files as $filename) {
 *
 * include_once $filename;
 * }
 * }
 */
//function log_to_file($content) {
//	$content = var_export ( $content, TRUE );
//	
//	file_put_contents ( 'e:\\irada-log.txt', "\n--------------\n$content", FILE_APPEND );
//}


/**
 * JoMiz Core utilities
 */
class core_utilities {
	public static function get_current_page_url() {
		$pageURL = 'http';
		if (isset ( $_SERVER ["HTTPS"] )) {
			if ($_SERVER ["HTTPS"] == "on") {
				$pageURL .= "s";
			}
		}
		$pageURL .= "://";
		if ($_SERVER ["SERVER_PORT"] != "80") {
			$pageURL .= $_SERVER ["SERVER_NAME"] . ":" . $_SERVER ["SERVER_PORT"] . $_SERVER ["REQUEST_URI"];
		} else {
			$pageURL .= $_SERVER ["SERVER_NAME"] . $_SERVER ["REQUEST_URI"];
		}
		return $pageURL;
	}
	static public function get_login_url() {
		return wp_login_url ( get_home_url () );
	}
	static public function get_default_page_size() {
		return 25;
	}
	static public function get_error_message($message = "Default Error Message") {
		return '<div class="alert boxed alert-error fade in">
								<div class="alert-body">
									<span>Error:</span>
									<p>
										' . $message . '
									</p>
								</div>
								<span class="alert-label"></span>
								<a href="#" class="close" data-dismiss="alert" hidefocus="true" style="outline: none;">close</a>
							</div>';
	}
	static public function register_extra_js_files($filesInfo) {
		global $pageJsFiles;
		$pageJsFiles = array ();
		if (! isset ( $filesInfo )) {
			return;
		}
		
		foreach ( $filesInfo as $key => $value ) {
			wp_register_script ( $key, $value ['location'], $value ['dependencies'], $value ['version'], true );
			$pageJsFiles [] = $key;
		}
	}
	static public function get_javascript_header_object() {
		$obj = array ();
		
		$obj ['baseurl'] = home_url ( '/' );
		$obj ['protocol'] = is_ssl () ? 'https' : 'http';
		
		$obj = ( object ) $obj;
		
		$json = json_encode ( $obj );
		
		return $json;
	}
	static public function insert_attachment($file_handler, $post_id = 0) {
		// check to make sure its a successful upload
		$result = array (
				'message' => 'ok',
				'attachment_id' => - 1 
		);
		
		if (is_array ( $_FILES [$file_handler] ['error'] )) {
			if (sizeof ( $_FILES [$file_handler] ['error'] ) > 1) {
				$result ['message'] = 'F1'; // $_FILES [$file_handler] ['error'];
				
				return ( object ) $result;
			} elseif ($_FILES [$file_handler] ['error'] [0] !== UPLOAD_ERR_OK) {
				$result ['message'] = 'F2'; // $_FILES [$file_handler] ['error'];
				
				return ( object ) $result;
			}
		} elseif ($_FILES [$file_handler] ['error'] !== UPLOAD_ERR_OK) {
			$result ['message'] = 'F3'; // $_FILES [$file_handler] ['error'];
			
			return ( object ) $result;
		}
		
		require_once (ABSPATH . "wp-admin" . '/includes/image.php');
		require_once (ABSPATH . "wp-admin" . '/includes/file.php');
		require_once (ABSPATH . "wp-admin" . '/includes/media.php');
		
		$attach_id = media_handle_upload ( $file_handler, $post_id );
		
		if (is_wp_error ( $attach_id )) {
			$error_string = $attach_id->get_error_message ();
			$result ['message'] = sprintf ( 'WP Error in uploading (%s)', $error_string );
		} else {
			
			$result ['message'] = 'ok';
			$result ['attachment_id'] = $attach_id;
		}
		
		return ( object ) $result;
	}
}