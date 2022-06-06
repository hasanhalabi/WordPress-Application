<?php
/*
 * Template Name: Upload File
 */

if ( !empty( $_FILES )  ) {
	$uploaded_bits = wp_upload_bits(
			$_FILES['file']['name'],
			null, //deprecated
			file_get_contents( $_FILES['file']['tmp_name'] )
			);
	if ( false !== $uploaded_bits['error'] ) {
		$error = $uploaded_bits['error'];
		return add_action( 'admin_notices', function() use ( $error ) {
			$msg[] = '<div class="error"><p>';
			$msg[] = '<strong>DropzoneJS & WordPress</strong>: ';
			$msg[] = sprintf( __( 'wp_upload_bits failed,  error: "<strong>%s</strong>' ), $error );
			$msg[] = '</p></div>';
			echo implode( PHP_EOL, $msg );
		} );
	}
	$uploaded_file     = $uploaded_bits['file'];
	$uploaded_url      = $uploaded_bits['url'];
	$uploaded_filetype = wp_check_filetype( basename( $uploaded_bits['file'] ), null );
	/*
		etc ...
		*/
}