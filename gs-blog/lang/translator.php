<?php define("IN_GS", true);
echo "Translating language files : This might take a while...\n";

include('en_US.php');

$sourceArray = $i18n;
unset($i18n);

$files = glob("*.php");
$skipKeys = array('LANGUAGE_CODE','DATE_FORMAT','DATE_DISPLAY','DATE_ARCHIVE');

$full = array();

function getTranslation($value,$lang="en") {
    $api = "https://www.googleapis.com/language/translate/v2?key=AIzaSyDeXwALn-YTKB2Bh0VshafRMrb-gDOGCE4&source=en&target=".$lang."&q=".rawurlencode($value);
    $data = file_get_contents($api);
    $result = json_decode($data, true);
    return $result['data']['translations'][0]['translatedText'];
}

foreach ($files as $file) {

    if ( $file != "translator.php" && $file != "en_US.php" ) {
    
        $lang = substr($file,0,2);
        echo "\nProcessing file: ".$file."...\n";
        echo "  * Language: ".$lang." *\n\n";
        include($file);
        
        foreach ( $sourceArray as $key => $value ) {
            if ( !array_key_exists($key, $i18n) ) {
                if(!in_array($key, $skipKeys)) {
                    echo " - Adding: " . $key ."\n";
                    $i18n[$key] = getTranslation($value, $lang);
                } else {
                    echo " - Skipping: " . $key ."\n";
                }
            }
        }
        
        $fp = fopen($file, 'w');
        fwrite($fp,'<?php # '.substr($file,0,-4).' Language File for GetSimple Blog - Automatically generated with the Google Translate API'."\n\n");
        fwrite($fp,'if ( !defined( \'IN_GS\' ) ) { die( \'You cannot load this file directly!\' ); } // Security Check'."\n\n".'$i18n = array('."\n");
        foreach ($i18n as $key => $value) {
            if (is_array ( $value ) ) {
                fwrite( $fp, str_pad("    '".$key."'",32," ")." => array( " );
                foreach($value as $item) {
                    fwrite( $fp, "'".$item."', " );
                }
                fwrite( $fp, " ),\n" );
            } else {
                fwrite($fp,str_pad("    '".$key."'",32," ")." => '".$value."',\n");
            }
        }
        fwrite($fp,");\n\n");
        fclose($fp);
    
    }

}

