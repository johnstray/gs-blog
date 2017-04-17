<?php
/**
 * @file: uploader.php
 * @package: GetSimple Blog [plugin]
 * @action: Post Image/Thumbnail Upload Manager
 * @author: John Stray [https://www.johnstray.id.au/]
 */

require ( '../../admin/inc/common.php' );

if ( !isset( $_POST[ 'sessionHash' ] ) )
{
    $_POST[ 'sessionHash' ] = null;
}
    
if ( $_POST[ 'sessionHash' ] === $SESSIONHASH )
{
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
        die ( json_encode( array( 'code' => 1, 'error' => i18n_r( 'ERROR_UPLOAD' ) . ' - ' . i18n_r( 'MISSING_FILE' ) ) ) );
    }
}
else
{
    die ( i18n_r( 'ERROR_UPLOAD' ) . ' - ' . i18n_r( 'API_ERR_AUTHFAILED' ) );
}
