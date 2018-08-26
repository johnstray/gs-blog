<?php
/**
 * @file: uploader.php
 * @package: GetSimple Blog [plugin]
 * @action: Post Image/Thumbnail Upload Manager
 * @author: John Stray [https://www.johnstray.id.au/]
 */

require ( '../../admin/inc/common.php' );

if ( !function_exists('toBytesShorthand') ) { // This function is part of GS 3.4.0
    function toBytesShorthand($str,$suffix = false){
        $val = trim($str, 'gmkGMK');
        $last = strtolower($str[strlen($str)-1]);
        switch($last) {
            case 'g': $val /= 1024;
            case 'm': $val /= 1024;
            case 'k': $val /= 1024;
        }
        return $val. ($suffix ? strtoupper($last.'B') : '');
    }
}

if ( !function_exists('getMaxUploadSize') ) { // This function is part of GS 3.4.0
    function getMaxUploadSize(){
        $max_upload   = toBytes(ini_get('upload_max_filesize'));
        $max_post     = toBytes(ini_get('post_max_size'));
        $memory_limit = toBytes(ini_get('memory_limit'));
        $upload_mb    = min($max_upload, $max_post, $memory_limit);
        return $upload_mb;
    }
}

if ( !isset( $_POST[ 'sessionHash' ] ) )
{
    $_POST[ 'sessionHash' ] = null;
}
    
if ( $_POST[ 'sessionHash' ] === $SESSIONHASH )
{
    if ( $_FILES['post-image']['error'] == 0 ) {
    
        if ( !empty( $_FILES ) )
        {
            
            $tempFile = $_FILES[ 'post-image' ][ 'tmp_name' ];
            $file = $_FILES[ 'post-image' ][ 'name' ];
            $extension = pathinfo( $file, PATHINFO_EXTENSION );
            $name = pathinfo( $file, PATHINFO_FILENAME );
            $name = clean_img_name( to7bit( $name ) );
            $filename = $name . '.' . $extension;
            if ( isset( $_POST[ 'path' ] ) )
            {
                $targetPath = GSDATAUPLOADPATH . $_POST[ 'path' ] . '/';
            }
            else
            {
                $targetPath = GSDATAUPLOADPATH;
            }
            
            $targetFile = str_replace( '//', '/', $targetPath ) . $filename;
            
            # Validate File
            if ( validate_safe_file( $tempFile, $_FILES[ 'post-image' ][ 'name' ] ) )
            {
                move_uploaded_file( $tempFile, $targetFile );
                if ( defined( 'GSCHMOD' ) )
                {
                    chmod( $targetFile, GSCHMOD );
                }
                else
                {
                    chmod( $targetFile, 0644 );
                }
            }
            else
            {
                die( i18n_r( 'ERROR_UPLOAD' ) . ' -  ' . i18n_r( 'BAD_FILE' ) );
            }
            
            # Generate Thumbnail
            if ( defined( 'GSIMAGEWIDTH' ) )
            {
                $width = GSIMAGEWIDTH;
            }
            else
            {
                $width = 200;
            }
            
            $path = '';
            
            if ( isset( $_POST[ 'path' ] ) )
            {
                $path = $_POST[ 'path' ] . '/';
            }
            
            require ( GSADMININCPATH . 'imagemanipulation.php' );
            
            genStdThumb( $path, $filename );
            
            # SUCCESS!!!
            die ( json_encode( array( 'code' => 0, 'url' => $path.$filename ) ) );
            
        }
        else
        {
            # No file sent with request!
            die ( json_encode( array( 'code' => 4, 'error' => i18n_r( 'ERROR_UPLOAD' ) . ' - ' . i18n_r( 'MISSING_FILE' ) ) ) );
        }
    } else {
        # There's a problem with the uploaded file:
        switch ( $_FILES['post-image']['error'] ) {
            case 1: die ( json_encode( array( 'code' => 1, 'error' => i18n_r( 'ERROR_UPLOAD' ) . ' - Uploaded file is too large. Max upload size is: ' . toBytesShorthand(getMaxUploadSize().'M',true) ) ) );
            case 2: die ( json_encode( array( 'code' => 2, 'error' => i18n_r( 'ERROR_UPLOAD' ) . ' - Uploaded file is too large. Max upload size is: ' . toBytesShorthand(getMaxUploadSize().'M',true) ) ) );
            case 3: die ( json_encode( array( 'code' => 3, 'error' => i18n_r( 'ERROR_UPLOAD' ) . ' - The file was only partially uploaded.' ) ) );
            case 4: die ( json_encode( array( 'code' => 4, 'error' => i18n_r( 'ERROR_UPLOAD' ) . ' - ' . i18n_r( 'MISSING_FILE' ) ) ) );
            case 6: die ( json_encode( array( 'code' => 6, 'error' => i18n_r( 'ERROR_UPLOAD' ) . ' - No TMP dir on server to store upload.' ) ) );
            case 7: die ( json_encode( array( 'code' => 7, 'error' => i18n_r( 'ERROR_UPLOAD' ) . ' - Failed to write the file to disk.' ) ) );
            case 8: die ( json_encode( array( 'code' => 8, 'error' => i18n_r( 'ERROR_UPLOAD' ) . ' - A PHP extension stopped the file upload.' ) ) );
            default: die ( json_encode( array( 'code' => $_FILES['post-image']['error'], 'error' => i18n_r( 'ERROR_UPLOAD' ) . ' - An unknown server error occurred.' ) ) );
        }
    }
}
else
{
    die ( i18n_r( 'ERROR_UPLOAD' ) . ' - ' . i18n_r( 'API_ERR_AUTHFAILED' ) );
}
