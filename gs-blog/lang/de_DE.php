<?php if(!defined('IN_GS')){die('You cannot load this file directly!');} // Security Check
/**************************************************************************************************\
* German (Deutsch) Language File for GetSimple Blog                                                *
* ------------------------------------------------------------------------------------------------ *
* Last Modified: 13 February 2016                                                                  *
* Compiled By: gschintgen ()                                                                       *
\**************************************************************************************************/
 
$i18n = array(
  
  # Language Configuration
  'LANGUAGE_CODE'             =>  'de_DE',
  'DATE_FORMAT'               =>  'm/d/Y h:i:s a',
  'DATE_DISPLAY'              =>  'F jS, Y',
  'DATE_ARCHIVE'              =>  'F Y',
  
  # Plugin Information
  'PLUGIN_TITLE'              =>  ($plugin = 'GetSimple Blog'),
  'PLUGIN_DESC'               =>  'Blogge jetzt schnell und einfach mit GetSimple',
  
  # Tab/Sidebar Actions (Administration)
  'BLOG_TAB_BUTTON'           =>  'B<em>l</em>og',
  'MANAGE_POSTS_BUTTON'       =>  'Artikel verwalten',
  'CATEGORIES_BUTTON'         =>  'Kategorien',
  'AUTOIMPORTER_BUTTON'       =>  'RSS Auto-Importer',
  'SETTINGS_BUTTON'           =>  'Einstellungen',
  'UPDATE_BUTTON'             =>  'Updates suchen',
  'HELP_BUTTON'               =>  'Hilfe',
  
  # Generic Strings
  'WRITE_OK'                  =>  'Datei erfolgreich geschrieben',
  'EDIT_OK'                   =>  'Datei erfolgreich bearbeitet',
  'DATA_FILE_ERROR'           =>  'Die Datei konnte nicht geschrieben werden',
  'CANCEL'                    =>  'Zur&uuml;ck',
  'DELETE'                    =>  'L&ouml;schen',
  'SAVE'                      =>  'Benutzerdefinierte Felder speichern',
  'OR'                        =>  'oder',
  'YES'                       =>  'Ja',
  'NO'                        =>  'Nein',
  'ADDED'                     =>  'Hinzugefügt',
  'MANAGE'                    =>  'Verwaltung',
  'LANGUAGE'                  =>  'Sprache',
  'DATE'                      =>  'Datum',
  
  # Class Constructor
  'DATA_BLOG_DIR'             =>  'Blog-Ordner erfolgreich angelegt',
  'DATA_BLOG_DIR_ERR'         =>  'Der data/blog Ordner konnte nicht angelegt werden!',
  'DATA_BLOG_DIR_ERR_HINT'    =>  'Du musst den Ordner manuell anlegen, um das Plugin verwenden zu k&ouml;nnen',
  'DATA_BLOG_CATEGORIES'      =>  'Die Datei <em>data/other/blog_categories.xml</em> wurde erfolgreich angelegt',
  'DATA_BLOG_CATEGORIES_ERR'  =>  'Die Datei <em>data/other/blog_categories.xml</em> konnte nicht angelegt werden!',
  'DATA_BLOG_RSS'             =>  '<em>data/other/blog_rss.xml</em> erfolgreich angelegt',
  'DATA_BLOG_RSS_ERR'         =>  'Die Datei <em>blog_rss.xml</em> konnte nicht angelegt werden!',
  'BLOG_SETTINGS'             =>  'Blog-Einstellungen',
  
  # 'Post Management' Strings
  'POST_ADDED'                =>  'Artikel erfolgreich gespeichert.',
  'POST_ERROR'                =>  'Der Artikel konnte nicht gespeichert werden!',
  'POST_DELETED'              =>  'Artikel erfolgreich entfernt.',
  'POST_DELETE_ERROR'         =>  'Dieser Artikel konnte nicht entfernt werden!',
  'BLOG_CREATE_EDIT_NO_TITLE' =>  'Ein "Artikel-Titel" muss angegeben werden, um einen Artikel zu ver&ouml;ffentlichen.',
  'BLOG_RETURN_TO_PREV_PAGE'  =>  'zur&uml;ck zum Artikel',
  'ADD_NEW_POST'              =>  'Artikel erstellen',
  'EDIT_EXISTING_POST'        =>  'Artikel bearbeiten',
  'VIEW_POST'                 =>  'Artikel anzeigen',
  'POST_OPTIONS'              =>  'Artikel-Optionen',
  'UPLOAD_THUMBNAIL'          =>  'Vorschaubild hochladen',
  'UPLOAD_ENABLE_JAVASCRIPT'  =>  'JavaScript muss aktiviert sein, um den Datei-Uploader zu benutzen.',
  'SAVE_POST'                 =>  'Artikel speichern',
  'MANAGE_POSTS'              =>  'Artikel',
  'CUSTOM_FIELDS_BUTTON'      =>  'benutzerdefinierte Felder',
  'NEW_POST_BUTTON'           =>  'Artikel erstellen',
  'MANAGE_POSTS_DESC'         =>  'Bestehende Artikel bearbeiten oder neue erstellen. Die nachstehende Tabelle zeigt die derzeit bestehenden Artikel.',
  'NO_POSTS'                  =>  'Keine Artikel vorhanden',
  'CLICK_TO_CREATE'           =>  'Hier klicken zum Erstellen',
  'PAGE_TITLE'                =>  'Seitentitel',
  
  # 'Category Management' Strings
  'CATEGORY_ADDED'            =>  'Kategorie erfolgreich angelegt.',
  'CATEGORY_ERROR'            =>  'Die Kategorie konnte nicht gespeichert werden.',
  'MANAGE_CATEGORIES'         =>  'Kategorien verwalten',
  'ADD_CATEGORY'              =>  'Kategorie hinzuf&uuml;gen',
  'SETTINGS_CATEGORY_DESC'    =>  'Kategorien für Blog-Artikel hinzuf&uuml;gen oder bearbeiten. Dies erm&ouml;glicht es, die Artikel zu sortieren und nur die anzuzeigen, die einer bestimmten Kategorie angeh&ouml;ren.',
  'CATEGORY_NAME'             =>  'Kategorie-Name',
  'CATEGORY_RSS_FEED'         =>  'RSS-Feed der Kategorie',
  
  # 'RSS Auto-Importer' Strings
  'FEED_ADDED'                =>  'Der RSS-Feed wurde erfolgrreich hinzugef&uuml;gt.',
  'FEED_ERROR'                =>  'Leider konnte der RSS-Feed nicht gespeichert werden!',
  'FEED_DELETED'              =>  'RSS-Feed erfolgreich entfernt.',
  'FEED_DELETE_ERROR'         =>  'Der RSS-Feed konnte nicht entfernt werden.',
  'READ_FULL_ARTICLE'         =>  'Den ganzen Artikel ausgeben',
  'RSS_FEED_NO_POSTS_DESC'    =>  'Es sind keine weiteren Artikel f&uuml;r diesen RSS-Feed verf&uuml;gbar. Bitte kontaktiere den Administrator der Website f&uuml;r weitere Informationen.',
  'RSS_FILE_OPEN_FAIL'        =>  'Konnte die Datei &apos;rss.rss&apos; nicht öffnen.',
  'RSS_FILE_WRITE_FAIL'       =>  'Konnte die Datei &apos;rss.rss&apos; nicht schreiben!',
  'RSS_HEADER'                =>  'RSS Auto-Importer',
  'ADD_FEED'                  =>  'RSS-Feed hinzuf&uuml;gen',
  'SETTINGS_FEED_DESC'        =>  'Der RSS Auto-Importer importiert RSS-Feeds anderer Webseiten und erstellt Artikel auf deren Basis. Dies ist n&uuml;tzlich wenn Du diesen Blog mit Inhalten von anderen Blogs betreiben m&ouml;chtest.',
  'ADD_NEW_FEED'              =>  'Neuen RSS-Feed anlegen',
  'BLOG_CATEGORY'             =>  'Blog-Kategorie',
  'RSS_FEED'                  =>  'RSS-Feed',
  'FEED_CATEGORY'             =>  'RSS-Feed-Kategorie',
  'DELETE_FEED'               =>  'Feed l&ouml;schen',
  
  # 'Settings' Strings
  'SETTINGS_SAVE_OK'          =>  'Einstellungen erfolgreich gesichert.',
  'SETTINGS_SAVE_ERROR'       =>  'Einstellungen konnten nicht gesichert werden.',
  'BLOG_SETTINGS'             =>  'Blog-Einstellungen',
  'SETTINGS_MAIN_DESC'        =>  'Die Einstellungen des Blogs verwalten. Dies sind die Haupteinstellungen f&uuml;r deinen Blog.',
  'PAGE_URL'                  =>  'Seite auf der die Artikel angezeigt werden',
  'EXCERPT_OPTION'            =>  'Posts format on posts page',
  'FULL_TEXT'                 =>  'Volltext',
  'EXCERPT'                   =>  'Vorschau',
  'EXCERPT_LENGTH'            =>  'L&auml;nge der Artikelvorschau (Zeichen)',
  'POSTS_PER_PAGE'            =>  'Anzahl der Artikel auf einer Seite',
  'RECENT_POSTS'              =>  'Anzahl der letzten Artikel',
  'DISPLAY_POST_COUNT_ARCH'   =>  'Anzahl der Artikel im Archiv anzeigen',
  'HTACCESS_HEADLINE'         =>  'Lesbare URLs',
  'PRETTY_URLS'               =>  'Lesbare URLs verwenden',
  'VIEW_HTACCESS'             =>  'Anzeigen, wie der Inhalt der .htacess Datei sein sollte.',
  'PRETTY_URLS_PARA'          =>  'Wenn ja, aktualisiere bitte die .htaccess Datei nach dem Speichern.',
  'HTACCESS_1'                =>  'Sperrt den direkten Zugriff auf die XML-Dateien - diese beinhalten die gesamten Daten!',
  'HTACCESS_2'                =>  '&Uuml;blicherweise ist RewriteBase nur &apos;/&apos;, ersetze es durch Deinem Unterordnerpfad.',
  'HTACCESS_3'                =>  'WICHTIG -> Falls die Website in einem Unterordner angesiedelt ist, musst Du dies entsprechend anpassen (z.B. /unterordner/)',
  'BLOG_PRETTY_NOTICE'        =>  'Du musst lesbare URLs in GetSimple aktivieren, damit hier Anweisungen angezeigt werden.',
  'SAVE_SETTINGS'             =>  'Einstellungen sichern',
  
  # 'Help' Strings
  'HELP'                      =>  'Hilfe',
  'FRONT_END_FUNCTIONS'       =>  'Front-End-Funktionen',
  'HELP_CATEGORIES'           =>  'Blog-Kategorien anzeigen',
  'HELP_SEARCH'               =>  'Blog-Suche anzeigen',
  'HELP_ARCHIVES'             =>  'Blog-Archiv anzeigen',
  'HELP_RECENT'               =>  'Die neuesten Blog-Artikel anzeigen',
  'HELP_RECENT_2'             =>  'Die Funktion hat 4 <strong>optionale</strong> Parameter',
  'HELP_RECENT_3'             =>  'Verwendungsbeispiel (Auszug anzeigen, standard Auszugl&auml;nge, Thumbnail anzeigen und "weiterlesen"-Link ausgeben)',
  'RSS_LOCATION'              =>  'Dein Blog RSS Feed Link',
  'DYNAMIC_RSS_LOCATION'      =>  'Dynamische RSS-Feed-Datei (erzeugt automatisch einen aktuellen RSS Feed)',
  'AUTO_IMPORTER_TITLE'       =>  'RSS-Feed Cronjob-Einstellungen',
  'AUTO_IMPORTER_DESC'        =>  'Der Cronjob sollte &uuml;ber die Webserver-Verwaltung angelegt werden. (Dieses Plugin nimmt an, dass du wei&szlig;t wie.) Der untenstehende Code dient als Cronjob-Beispiel:',
  'BLOG_PAGE_DESC_TITLE'      =>  'Hilfe f&uuml;r benutzerdefinierte Blog-Seite',
  'BLOG_PAGE_RECOM'           =>  'Es wird empfohlen, die Funktion <code>show_blog_post()</code> in plugins/blog/inc/frontEndFunctions.php anzuschauen, um zu sehen, wie eine benutzerdefinierte Blog-Seite am Besten angelegt wird.',
  'BLOG_PAGE_DESC_LINE_1'     =>  'Du kannst html, php, xml und js in diesem Textfeld benutzen.',
  'BLOG_PAGE_DESC_LINE_2'     =>  'Es ist als ob der Code direkt ins Plugin eingef&uuml;gt w&uuml;rde. Die Artikel-Daten werden als Objekt &uuml;bergeben.',
  'BLOG_PAGE_DESC_LINE_3'     =>  'Um also auf ein benutzerdefiniertes Feld zuzugreifen, kannst Du wie folgt vorgehen:',
  'BLOG_PAGE_AVAILABLE_FUNC'  =>  'Verf&uuml;gbare Funktionen &amp; Hilfen:',
  'BLOG_PAGE_FRMT_DATE_LABEL' =>  'Datumsformat',
  'BLOG_PAGE_FRMT_DATA_DESC'  =>  'Du &uuml;bergibst die Daten direkt aus dem Objekt und die Funktion formatiert das Datum.',
  'BLOG_PAGE_GET_URL_TO_AREAS'=>  'URL des Blog-Bereichs',
  'BLOG_PAGE_URL_EX_LABEL'    =>  'ex (URL des Artikels)',
  'BLOG_PAGE_AVAILABLE_AREAS' =>  'Verf&uuml;gbare Bereiche',
  'BLOG_PAGE_POST'            =>  'Artikel',
  'BLOG_PAGE_TAG'             =>  'Tag',
  'BLOG_PAGE_PAGE'            =>  'Seite',
  'BLOG_PAGE_ARCHIVE'         =>  'Archiv',
  'BLOG_PAGE_CATEGORY'        =>  'Kategorie',
  'BLOG_PAGE_CREATE_EXCERPT'  =>  'Vorschautext anlegen',
  'BLOG_PAGE_EXCERPT_DESC'    =>  'Dies legt einen Vorschautext der festgelegten L&auml;nge an. Die Variable <i>$excerpt_length</i> muss ganzzahlig sein und legt die L&auml;nge des Vorschautextes fest.',
  'BLOG_PAGE_DECODE_CONTENT'  =>  'Inhalt dekodieren',
  
  # 'Front-End' Strings
  'BY'                        =>  'von',
  'ON'                        =>  'am',
  'IN'                        =>  'in',
  'TAGS'                      =>  'Tags',
  'NO_CATEGORIES'             =>  'Keine Kategorien verfügbar!',
  'NO_POSTS'                  =>  'Keine Artikel verfügbar!',
  'NO_ARCHIVES'               =>  'Das Archiv ist leer!',
  'SEARCH'                    =>  'Suche',
  'FOUND'                     =>  'Die folgenden Artikel wurden gefunden:',
  'NOT_FOUND'                 =>  'Leider wurden keine Artikel gefunden.',
  'NEXT_PAGE'                 =>  '&larr; nächste Seite',
  'PREV_PAGE'                 =>  'vorherige Seite &rarr;',
  'ARCHIVE_PRETITLE'          =>  'Blog-Archiv: ',
  'CATEGORY_PRETITLE'         =>  'Blog-Kategorie: ',
  
  # Custom Fields Manager
  'CUSTOM_FIELDS'             =>  'benutzerdefinierte Felder',
  'CUSTOMFIELDS_DESCR'        =>  'Diese Erweiterung erm&ouml;glicht es, benutzerdefinierte Felder beim Bearbeiten einer Seite anzuzeigen.',
  'CUSTOM_FIELDS_OPTIONS_AREA'=>  'Einstellungsbereich',
  'OPTIONS_AREA_DESCRP'       =>  '(Einstellungsbereich: benutzerdefinierte Felder werden <em>in</em> den "Artikel-Einstellungen" angezeigt.)',
  'NAME'                      =>  'Name',
  'LABEL'                     =>  'Label',
  'TYPE'                      =>  'Typ',
  'DEFAULT_VALUE'             =>  'Standardwert',
  'ADD'                       =>  'Neues Feld hinzuf&uuml;gen',
  'CUSTOM_FIELDS_MAIN_AREA'   =>  'Hauptbereich',
  'MAIN_AREA_DESCRP'          =>  '(Hauptbereich: benutzerdefinierte Felder werden unterhalb der "Artikel-Einstellungen" angezeigt.)',
  'TEXT_FIELD'                =>  'Textfeld',
  'LONG_TEXT_FIELD'           =>  'Gro&szlig;es Textfeld',
  'DROPDOWN_BOX'              =>  'Dropdown-Box',
  'CHECKBOX'                  =>  'Checkbox',
  'WYSIWYG_EDITOR'            =>  'WYSIWYG-Editor',
  'TITLE'                     =>  'Titel',
  'HIDDEN_FIELD'              =>  'Verstecktes Feld',
  
  # VersionCheck Updater
  'VERSION_NOMESSAGE'         =>  'Keine Fehlermeldung gesetzt! Dies ist ein Problem.',
  'VERSION_NORESPONSE'        =>  'Keine Antwort vom "Extend API"-Server erhalten.',
  'VERSION_NOFUNCTION'        =>  'Deine PHP-Umgebung ist nicht richtig konfiguriert!',
  'VERSION_UPDATEAVAILABLE'   =>  'Eine Aktualisierung ist verfügbar!',
  'VERSION_UPTODATE'          =>  $plugin.' ist aktuell!',
  'VERSION_BETA'              =>  'Du benutzt gerade eine Beta-Version von '.$plugin.'.',
  'VERSION_FAILEDCOMPARE'     =>  'Versionsvergleich bei Aktualisierungs&uuml;berpr&uuml;fung fehlgeschlagen.',
  'VERSION_APIFAIL'           =>  'Die &Uuml;berpr&uuml;fung &uuml;ber die Extend API ist fehlgeschlagen.',
  'VERSION_INTERNALERROR'     =>  'Ein interner Fehler mit VersionCheck ist aufgetreten.',
  'VERSION_STATUS'            =>  'Plugin-Aktualisierungen',
  'VERSION_STATUS_DESC'       =>  'Stelle sicher, dass du die neueste Version von '.$plugin.' benutzt, um somit von den neuesten Fehlerbereinigungen und Features profitieren kannst.',
  'VERSION_UPDATESTATUS'      =>  'Aktualisierungsstatus',
  'VERSION_CURRENTVER'        =>  'Aktuelle Version',
  'VERSION_LATESTVER'         =>  'Neueste Version',
  
);
