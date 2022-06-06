<?php

class jomiz_attachments {

    public static function saveAttachments($module, $object_type, $object_id, $attachment_id, $attachment_name, $is_image) {

        global $wpdb;

        $attachmentsTableName = self::getHeapAttachmentsTableName();

        $insert_data = array(
            'module' => $module,
            'object_type' => $object_type,
            'object_id' => $object_id,
            'attachment_id' => $attachment_id,
            'attachment_name' => $attachment_name,
            'is_image' => $is_image
        );

        $insert_data ['author_id'] = get_current_user_id();
        $insert_data ['created'] = date("Y-m-d H:i:s");
        $insert_data ['modified'] = $insert_data ['created'];

        $wpdb->insert($attachmentsTableName, $insert_data);

        return $wpdb->insert_id;
    }

    public static function getAttachments($module, $object_type, $object_id) {
        global $wpdb;

        $attachmentsTableName = self::getHeapAttachmentsTableName();

        $results = $wpdb->get_results($wpdb->prepare("
					SELECT * FROM $attachmentsTableName WHERE module = %s  AND object_type = %s AND object_id = %d 
				", array(
                    $module,
                    $object_type,
                    $object_id
        )));

        foreach ($results as &$result) {
            $result->attachment_link = wp_get_attachment_url($result->attachment_id);
        }

        return $results;
    }

    private static function getHeapAttachmentsTableName() {
        global $wpdb;
        return sprintf('%1$s%2$s_heap_attachments', $wpdb->prefix, 'jsys');
    }

    public static function mime2ext($mime) {
        $all_mimes = '{"png":["image\/png","image\/x-png"],"bmp":["image\/bmp","image\/x-bmp","image\/x-bitmap","image\/x-xbitmap","image\/x-win-bitmap","image\/x-windows-bmp","image\/ms-bmp","image\/x-ms-bmp","application\/bmp","application\/x-bmp","application\/x-win-bitmap"],"gif":["image\/gif"],"jpeg":["image\/jpeg","image\/pjpeg"],"xspf":["application\/xspf+xml"],"vlc":["application\/videolan"],"wmv":["video\/x-ms-wmv","video\/x-ms-asf"],"au":["audio\/x-au"],"ac3":["audio\/ac3"],"flac":["audio\/x-flac"],"ogg":["audio\/ogg","video\/ogg","application\/ogg"],"kmz":["application\/vnd.google-earth.kmz"],"kml":["application\/vnd.google-earth.kml+xml"],"rtx":["text\/richtext"],"rtf":["text\/rtf"],"jar":["application\/java-archive","application\/x-java-application","application\/x-jar"],"zip":["application\/x-zip","application\/zip","application\/x-zip-compressed","application\/s-compressed","multipart\/x-zip"],"7zip":["application\/x-compressed"],"xml":["application\/xml","text\/xml"],"svg":["image\/svg+xml"],"3g2":["video\/3gpp2"],"3gp":["video\/3gp","video\/3gpp"],"mp4":["video\/mp4"],"m4a":["audio\/x-m4a"],"f4v":["video\/x-f4v"],"flv":["video\/x-flv"],"webm":["video\/webm"],"aac":["audio\/x-acc"],"m4u":["application\/vnd.mpegurl"],"pdf":["application\/pdf","application\/octet-stream"],"pptx":["application\/vnd.openxmlformats-officedocument.presentationml.presentation"],"ppt":["application\/powerpoint","application\/vnd.ms-powerpoint","application\/vnd.ms-office","application\/msword"],"docx":["application\/vnd.openxmlformats-officedocument.wordprocessingml.document"],"xlsx":["application\/vnd.openxmlformats-officedocument.spreadsheetml.sheet","application\/vnd.ms-excel"],"xl":["application\/excel"],"xls":["application\/msexcel","application\/x-msexcel","application\/x-ms-excel","application\/x-excel","application\/x-dos_ms_excel","application\/xls","application\/x-xls"],"xsl":["text\/xsl"],"mpeg":["video\/mpeg"],"mov":["video\/quicktime"],"avi":["video\/x-msvideo","video\/msvideo","video\/avi","application\/x-troff-msvideo"],"movie":["video\/x-sgi-movie"],"log":["text\/x-log"],"txt":["text\/plain"],"css":["text\/css"],"html":["text\/html"],"wav":["audio\/x-wav","audio\/wave","audio\/wav"],"xhtml":["application\/xhtml+xml"],"tar":["application\/x-tar"],"tgz":["application\/x-gzip-compressed"],"psd":["application\/x-photoshop","image\/vnd.adobe.photoshop"],"exe":["application\/x-msdownload"],"js":["application\/x-javascript"],"mp3":["audio\/mpeg","audio\/mpg","audio\/mpeg3","audio\/mp3"],"rar":["application\/x-rar","application\/rar","application\/x-rar-compressed"],"gzip":["application\/x-gzip"],"hqx":["application\/mac-binhex40","application\/mac-binhex","application\/x-binhex40","application\/x-mac-binhex40"],"cpt":["application\/mac-compactpro"],"bin":["application\/macbinary","application\/mac-binary","application\/x-binary","application\/x-macbinary"],"oda":["application\/oda"],"ai":["application\/postscript"],"smil":["application\/smil"],"mif":["application\/vnd.mif"],"wbxml":["application\/wbxml"],"wmlc":["application\/wmlc"],"dcr":["application\/x-director"],"dvi":["application\/x-dvi"],"gtar":["application\/x-gtar"],"php":["application\/x-httpd-php","application\/php","application\/x-php","text\/php","text\/x-php","application\/x-httpd-php-source"],"swf":["application\/x-shockwave-flash"],"sit":["application\/x-stuffit"],"z":["application\/x-compress"],"mid":["audio\/midi"],"aif":["audio\/x-aiff","audio\/aiff"],"ram":["audio\/x-pn-realaudio"],"rpm":["audio\/x-pn-realaudio-plugin"],"ra":["audio\/x-realaudio"],"rv":["video\/vnd.rn-realvideo"],"jp2":["image\/jp2","video\/mj2","image\/jpx","image\/jpm"],"tiff":["image\/tiff"],"eml":["message\/rfc822"],"pem":["application\/x-x509-user-cert","application\/x-pem-file"],"p10":["application\/x-pkcs10","application\/pkcs10"],"p12":["application\/x-pkcs12"],"p7a":["application\/x-pkcs7-signature"],"p7c":["application\/pkcs7-mime","application\/x-pkcs7-mime"],"p7r":["application\/x-pkcs7-certreqresp"],"p7s":["application\/pkcs7-signature"],"crt":["application\/x-x509-ca-cert","application\/pkix-cert"],"crl":["application\/pkix-crl","application\/pkcs-crl"],"pgp":["application\/pgp"],"gpg":["application\/gpg-keys"],"rsa":["application\/x-pkcs7"],"ics":["text\/calendar"],"zsh":["text\/x-scriptzsh"],"cdr":["application\/cdr","application\/coreldraw","application\/x-cdr","application\/x-coreldraw","image\/cdr","image\/x-cdr","zz-application\/zz-winassoc-cdr"],"wma":["audio\/x-ms-wma"],"vcf":["text\/x-vcard"],"srt":["text\/srt"],"vtt":["text\/vtt"],"ico":["image\/x-icon","image\/x-ico","image\/vnd.microsoft.icon"],"csv":["text\/x-comma-separated-values","text\/comma-separated-values","application\/vnd.msexcel"],"json":["application\/json","text\/json"]}';
        $all_mimes = json_decode($all_mimes, true);
        foreach ($all_mimes as $key => $value) {
            if (array_search($mime, $value) !== false)
                return $key;
        }
        return false;
    }

    public static function upload_attachment_by_base64($filename, $base64_content) {
        $title = 'Attachment: ' . $filename;

        $my_post = array(
            'post_title' => $title,
            'post_status' => 'publish',
            'post_author' => 1,
            'post_type' => 'attachment'
        );

        $file_tokens = explode(';', $base64_content);
        $file_tokens[0] = str_replace('data:', '', $file_tokens[0]);
        $file_tokens[2] = str_replace(array('base64,',' '), array('','+'), $file_tokens[1]);
        $file_tokens[1] = self::mime2ext($file_tokens[0]);
        $file_tokens[3] = $base64_content;

        log_to_file($file_tokens);

        $upload_dir = wp_upload_dir();

        // @new
        $upload_path = str_replace('/', DIRECTORY_SEPARATOR, $upload_dir['path']) . DIRECTORY_SEPARATOR;



        $decoded = base64_decode($file_tokens[2]);

        $hashed_filename = md5($filename . $file_tokens[1] . microtime()) . '_' . $filename . '.' . $file_tokens[1];

        // @new
        $image_upload = file_put_contents($upload_path . $hashed_filename, $decoded);

        //HANDLE UPLOADED FILE
        if (!function_exists('wp_handle_sideload')) {

            require_once( ABSPATH . 'wp-admin/includes/file.php' );
        }

        // Without that I'm getting a debug error!?
        if (!function_exists('wp_get_current_user')) {

            require_once( ABSPATH . 'wp-includes/pluggable.php' );
        }

        // @new
        $file = array();
        $file['error'] = '';
        $file['tmp_name'] = $upload_path . $hashed_filename;
        $file['name'] = $hashed_filename;
        $file['type'] = $file_tokens[0];
        $file['size'] = filesize($upload_path . $hashed_filename);

        log_to_file($file);
        // upload file to server
        // @new use $file instead of $image_upload
        $file_return = wp_handle_sideload($file, array('test_form' => false));
        log_to_file($file_return);
        $filename = $file_return['file'];
        $attachment = array(
            'post_mime_type' => $file_return['type'],
            'post_title' => preg_replace('/\.[^.]+$/', '', basename($filename)),
            'post_content' => '',
            'post_status' => 'inherit',
            'guid' => $wp_upload_dir['url'] . '/' . basename($filename)
        );
        log_to_file('attachment');
        log_to_file($attachment);
        $attach_id = wp_insert_attachment($attachment, $filename, 289);
        require_once(ABSPATH . 'wp-admin/includes/image.php');
        $attach_data = wp_generate_attachment_metadata($attach_id, $filename);
        wp_update_attachment_metadata($attach_id, $attach_data);
        $jsonReturn = array(
            'Status' => 'Success'
        );

        // Insert the post into the database
        $tattoo_ID = wp_insert_post($my_post);

        if ($tattoo_ID) {
            add_post_meta($tattoo_ID, 'text', $_POST["tatooInput"]);
            add_post_meta($tattoo_ID, 'image', $_POST["imageData"]);
            add_post_meta($tattoo_ID, 'image_ID', $attach_id);
        }

        return wp_get_attachment_url($attach_id);
    }

	public static function deleteAttachments($module, $object_type, $object_id, $attachment_name) {

        global $wpdb;

        $attachmentsTableName = self::getHeapAttachmentsTableName();
        $wpdb->delete($attachmentsTableName, array('module' => $module, 'object_type' => $object_type,
            'attachment_name' => $attachment_name,
            'object_id' => $object_id,));

        //return $wpdb->insert_id;
    }
}
