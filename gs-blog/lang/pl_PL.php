<?php if(!defined('IN_GS')){die('You cannot load this file directly!');} // Security Check
/**************************************************************************************************\
* Polish (Język Polski) Language File for GetSimple Blog                                           *
* ------------------------------------------------------------------------------------------------ *
* Last Modified: 01 April 2015                                                                     *
* Compiled By: Every0ne (? ?)                                                                      *
\**************************************************************************************************/

$i18n = array(

	# Language Configuration
	'LANGUAGE_CODE'             =>  'pl_PL',
	'DATE_FORMAT'               =>  'd.m.Y G:i:s',
	'DATE_DISPLAY'              =>  'd.m.Y',
	'DATE_ARCHIVE'              =>  'Y.m',

	# Plugin Information
	'PLUGIN_TITLE'              =>  ($plugin = 'GetSimple Blog'),
	'PLUGIN_DESC'               =>  'Prosty i łatwy w użyciu blog/menedżer aktualności',

	# Tab/Sidebar Actions (Administration)
	'BLOG_TAB_BUTTON'           =>  'B<em>l</em>og',
	'MANAGE_POSTS_BUTTON'       =>  'Wpisy',
	'CATEGORIES_BUTTON'         =>  'Kategorie',
	'AUTOIMPORTER_BUTTON'       =>  'Autoimporter RSS',
	'SETTINGS_BUTTON'           =>  'Ustawienia',
	'UPDATE_BUTTON'             =>  'Sprawdź aktualizacje',
	'HELP_BUTTON'               =>  'Pomoc',

	# Generic Strings
	'WRITE_OK'                  =>  'Plik zapisany',
	'EDIT_OK'                   =>  'Plik zmnieniony',
	'DATA_FILE_ERROR'           =>  'Nie udało się zapisać pliku!',
	'CANCEL'                    =>  'Anuluj',
	'DELETE'                    =>  'Usuń',
	'SAVE'                      =>  'Zapisz',
	'OR'                        =>  'lub',
	'YES'                       =>  'Tak',
	'NO'                        =>  'Nie',
	'ADDED'                     =>  'Dodano',
	'MANAGE'                    =>  'Zarządzanie',
	'LANGUAGE'                  =>  'Język',
	'DATE'                      =>  'Data',

	# Class Constructor
	'DATA_BLOG_DIR'             =>  'Folder "<i>data/blog</i>" utworzony',
	'DATA_BLOG_DIR_ERR'         =>  'Nie udało się utworzyć folderu "<i>data/blog_posts</i>" !',
	'DATA_BLOG_DIR_ERR_HINT'    =>  'Musisz utworzyć ten folder samodzielnie, by wtyczka działała poprawnie',
	'DATA_BLOG_CATEGORIES'      =>  'Plik "<i>data/other/blog_categories.xml</i>" utworzony',
	'DATA_BLOG_CATEGORIES_ERR'  =>  'Nie udało się utworzyć pliku "<i>data/other/blog_categories.xml</i>" !',
	'DATA_BLOG_RSS'             =>  'Plik "<i>data/other/blog_rss.xml</i>" utworzony',
	'DATA_BLOG_RSS_ERR'         =>  'Nie udało się utworzyć pliku "<i>data/other/blog_rss.xml</i>" !',
	'BLOG_SETTINGS'             =>  'Ustawienia bloga',

	# 'Post Management' Strings
	'POST_ADDED'                =>  'Wpis dodany',
	'POST_ERROR'                =>  'Nie udało się dodać wpisu!',
	'POST_DELETED'              =>  'Wpis usunięty',
	'POST_DELETE_ERROR'         =>  'Nie udało się usunąć wpisu!',
	'BLOG_CREATE_EDIT_NO_TITLE' =>  'Tytuł wpisu jest wymagany, by go dodać do bloga',
	'BLOG_RETURN_TO_PREV_PAGE'  =>  'Wróć do wpisu',
	'ADD_NEW_POST'              =>  'Nowy wpis',
	'EDIT_EXISTING_POST'        =>  'Edycja wpisu',
	'VIEW_POST'                 =>  'Zobacz wpis',
	'POST_OPTIONS'              =>  'Opcje wpisu',
	'UPLOAD_THUMBNAIL'          =>  'Wrzuć obrazek',
	'UPLOAD_ENABLE_JAVASCRIPT'  =>  'Do wrzucania plików potrzebna jest obsługa JavaScript!',
	'SAVE_POST'                 =>  'Zapisz wpis',
	'MANAGE_POSTS'              =>  'Wpisy',
	'CUSTOM_FIELDS_BUTTON'      =>  'Dodatkowe pola',
	'NEW_POST_BUTTON'           =>  'Utwórz wpis',
	'MANAGE_POSTS_DESC'         =>  'Twórz nowe wpisy lub edytuj istniejące. Tabela poniżej przedstawia istniejące obecnie wpisy.',
	'NO_POSTS'                  =>  'Nie ma jeszcze żadnych wpisów.',
	'CLICK_TO_CREATE'           =>  'Kliknij tu, by utworzyć pierwszy',
	'PAGE_TITLE'                =>  'Tytuł strony',

	# 'Category Management' Strings
	'CATEGORY_ADDED'            =>  'Kategoria dodana',
	'CATEGORY_ERROR'            =>  'Nie udało się dodać kategorii!',
	'MANAGE_CATEGORIES'         =>  'Zarządzanie kategoriami',
	'ADD_CATEGORY'              =>  'Dodaj kategorię',
	'SETTINGS_CATEGORY_DESC'    =>  'Dodawaj lub edytuj kategorie do których można będzie dołączać wpisy. Dzięki temu możliwe będzie sortowanie wpisów na podstawie kategorii i wyświetlanie wpisów należących tylko do określonej kategorii.',
	'CATEGORY_NAME'             =>  'Nazwa kategorii',
	'CATEGORY_RSS_FEED'         =>  'Kanał RSS kategorii',

	# 'RSS Auto-Importer' Strings
	'FEED_ADDED'                =>  'Dodano kanał RSS',
	'FEED_ERROR'                =>  'Nie udało się dodać kanału RSS!',
	'FEED_DELETED'              =>  'Kanał RSS usunięty',
	'FEED_DELETE_ERROR'         =>  'Nie udało się usunąć kanału RSS!',
	'READ_FULL_ARTICLE'         =>  'Przeczytaj pełen artykuł',
	'RSS_FEED_NO_POSTS_DESC'    =>  'Brak wpisów dla tego kanału RSS. Skontaktuj się z administratorem witryny, by uzyskać więcej informacji.',
	'RSS_FILE_OPEN_FAIL'        =>  'Nie można otworzyć pliku <i>rss.rss</i> .',
	'RSS_FILE_WRITE_FAIL'       =>  'Nie udało się zapisać pliku <i>rss.rss</i> .',
	'RSS_HEADER'                =>  'Autoimporter RSS',
	'ADD_FEED'                  =>  'Dodaj kanał RSS',
	'SETTINGS_FEED_DESC'        =>  'Autoimporter RSS zaimportuje i utworzy wpisy z kanałów RSS innych stron. Przydaje się to jeśli chcesz dodawać do tego bloga treść innego bloga, który również prowadzisz.',
	'ADD_NEW_FEED'              =>  'Dodaj nowy kanał RSS',
	'BLOG_CATEGORY'             =>  'Kategoria bloga',
	'RSS_FEED'                  =>  'Kanał RSS',
	'FEED_CATEGORY'             =>  'Kategoria kanału RSS',
	'DELETE_FEED'               =>  'Usuń kanał RSS',
	'RSS_SETTINGS_HEADER'       =>  'Ustawienia autoimportera RSS',
	'SETTINGS_RSS_DESC'         =>  'Dostosuj ustawienia autoimportera RSS. Autoimporter może importować pełną treść kanałów lub podsumowanie z linkami do oryginalnych artykułów.', // Modified string in v3.4.1 ('Summary' changed to 'Excerpt') [not yet re-translated]
	'RSS_IMPORTER'              =>  'Włącz autoimporter RSS',
	'RSS_CONTENT_DESCRIPTION'   =>  'Dołączaj treść RSS lub podsumowanie', // Modified string in v3.4.1 ('Summary' changed to 'Excerpt') [not yet re-translated]
  'RSS_INCLUDE_CONTENT'       =>  'Full Content', // New String in 3.4.1
  'RSS_INCLUDE_EXCERPT'       =>  'Create Excerpt', // New String in v3.4.1
	'RSS_TITLE'                 =>  'Tytuł kanału RSS',
	'RSS_DESCRIPTION'           =>  'Opis kanału RSS',
	'RSS_IMPORTER_PASS'         =>  'Hasło autoimportera (cokolwiek)',
	'RSS_FEED_NUM_POSTS'        =>  '&num; wpisów w kanale RSS',

	# 'Settings' Strings
	'SETTINGS_SAVE_OK'          =>  'Ustawienia zapisane',
	'SETTINGS_SAVE_ERROR'       =>  'Nie udało się zapisać ustawień!',
	'BLOG_SETTINGS'             =>  'Ustawienia bloga',
	'SETTINGS_MAIN_DESC'        =>  'Zarządzaj ustawieniami twego bloga. Tu znajdują się główne ustawienia bloga.',
	'PAGE_URL'                  =>  'Strona służąca do wyświetlania wpisów bloga',
	'EXCERPT_OPTION'            =>  'Format wpisów na stronie',
	'FULL_TEXT'                 =>  'Pełny tekst',
	'EXCERPT'                   =>  'Fragment',
	'EXCERPT_LENGTH'            =>  'Długość fragmentu (ilość znaków)',
	'POSTS_PER_PAGE'            =>  '&num; wpisów na stronę',
	'RECENT_POSTS'              =>  '&num; ostatnich wpisów',
	'DISPLAY_POST_COUNT_ARCH'   =>  'Pokaż licznik wpisów w archiwum',
	'HTACCESS_HEADLINE'         =>  'Pretty URLs',
	'PRETTY_URLS'               =>  'Używaj ładnych adresów URL',
	'VIEW_HTACCESS'             =>  'Zobacz jak powinien wyglądać plik <i>.htaccess</i> twojej strony!',
	'PRETTY_URLS_PARA'          =>  'jeśli chcesz używać ładnych adresów URL, to po zapisaniu ustawień należy wprowadzić odpowiednie zmiany w konfiguracji serwera lub w pliku <i>.htaccess</i>',
	'HTACCESS_1'                =>  'Blokada bezpośredniego dostępu do plików XML - w nich znajdują się wszystkie dane!',
	'HTACCESS_2'                =>  'Zwykle dyrektywa <i>RewriteBase</i> zawiera tylko &apos;/&apos;, ale należy ją zmienić, jeśli zajdzie taka potrzeba.',
	'HTACCESS_3'                =>  'WAŻNE -> jeśli twoja strona znajduje się w podfolderze, należy odpowiednio zmienić to ustawienie (przykładowo: /podfolder/)',
	'BLOG_PRETTY_NOTICE'        =>  'Musisz włączyć ładne URLe w ustawieniach GetSimple by pojawiły się tu dalsze instrukcje',
	'SAVE_SETTINGS'             =>  'Zapisz ustawienia',
	'MAIN_SETTINGS_BUTTON'      =>  'Główne ustawienia',

	# 'Help' Strings
	'HELP'                      =>  'Pomoc',
	'FRONT_END_FUNCTIONS'       =>  'Funkcje Front End&apos;u',
	'HELP_CATEGORIES'           =>  'Pokaż kategorie bloga',
	'HELP_SEARCH'               =>  'Pokaż pole wyszukiwania w blogu',
	'HELP_ARCHIVES'             =>  'Pokaż archiwum bloga',
	'HELP_RECENT'               =>  'Pokaż najnowsze wpisy bloga',
	'HELP_RECENT_2'             =>  'Ta funkcja ma 4 <b>ocjonalne</b> parametry',
	'HELP_RECENT_3'             =>  'Przykładowe wywołanie funkcji z parametrami: pokaż fragmenty, domyślna długość fragmentu, pokaż obrazek, pokaż link "czytaj więcej"',
	'RSS_LOCATION'              =>  'Poniżej znajduje się kanał RSS twojego bloga',
	'DYNAMIC_RSS_LOCATION'      =>  'Dynamiczny adres kanału RSS (generuje kanał RSS na bieżąco)',
	'AUTO_IMPORTER_TITLE'       =>  'Ustawienia Cron autoimportera kanałów RSS',
	'AUTO_IMPORTER_DESC'        =>  'Zarządzanie zadaniami Cron powinno być dostępne z panelu obsługi strony udostępnionego przez twego hosta.<br>Przyjęto,&nbsp;że wiesz, jak to zrobić. Twoje zadanie Cron powinno wyglądać podobnie do poniższego wycinka.',
	'BLOG_PAGE_DESC_TITLE'      =>  'Pomoc niestandardowej strony bloga',
	'BLOG_PAGE_RECOM'           =>  'Zalecane jest sprawdzenie funkcji show_blog_post() w pliku"<i>plugins/blog/inc/frontEndFunctions.php</i>" by ocenić najlepszy sposób implementacji niestandardowego układu stron bloga.',
	'BLOG_PAGE_DESC_LINE_1'     =>  'W tym polu tekstowym możesz używać HTML, PHP, XML i JavaScript.',
	'BLOG_PAGE_DESC_LINE_2'     =>  'Zachowa się tak, jak by był zawarty w kodzie wtyczki. Dane wpisu są przekazywane przez obiekt.',
	'BLOG_PAGE_DESC_LINE_3'     =>  'Zatem by odczytać dane z dodatkowego pola możesz to zrobić w następujący sposób',
	'BLOG_PAGE_AVAILABLE_FUNC'  =>  'Dostępne narzędzia i funkcje pomocnicze:',
	'BLOG_PAGE_FRMT_DATE_LABEL' =>  'Formatowanie daty',
	'BLOG_PAGE_FRMT_DATA_DESC'  =>  'Funkcja przyjmuje datę wprost z obiektu wpisu i zwraca w odpowiednim formacie.',
	'BLOG_PAGE_GET_URL_TO_AREAS'=>  'Otrzymywanie adresu URL do stref bloga',
	'BLOG_PAGE_URL_EX_LABEL'    =>  'Przykład otrzymania URLa do wpisu',
	'BLOG_PAGE_AVAILABLE_AREAS' =>  'Dostępne strefy',
	'BLOG_PAGE_POST'            =>  'wpis: <i>post</i>',
	'BLOG_PAGE_TAG'             =>  'tag: <i>tag</i>',
	'BLOG_PAGE_PAGE'            =>  'strona: <i>page</i>',
	'BLOG_PAGE_ARCHIVE'         =>  'archiwum: <i>archive</i>',
	'BLOG_PAGE_CATEGORY'        =>  'kategoria: <i>category</i>',
	'BLOG_PAGE_CREATE_EXCERPT'  =>  'Tworzenie fragmentu',
	'BLOG_PAGE_EXCERPT_DESC'    =>  'To spowoduje utworzenie fragmentu wpisu o określonej długości. Zmienna <i>$excerpt_length</i> musi być liczbą całkowitą i określa maksymalną ilość znaków fragmentu.',
	'BLOG_PAGE_DECODE_CONTENT'  =>  'Dekodowanie treści',

	# 'Front-End' Strings
	'BY'                        =>  'przez',
	'ON'                        =>  'dnia',
	'IN'                        =>  'w kategorii',
	'TAGS'                      =>  'Tagi',
	'NO_CATEGORIES'             =>  'Brak dostępnych kategorii',
	'NO_POSTS'                  =>  'Brak wpisów',
	'NO_ARCHIVES'               =>  'Archiwum jest puste',
	'SEARCH'                    =>  'Szukaj',
	'FOUND'                     =>  'Znaleziono następujące wpisy:',
	'NOT_FOUND'                 =>  'Nie znaleziono żadnych wpisów.',
	'NEXT_PAGE'                 =>  '&larr; następna strona',
	'PREV_PAGE'                 =>  'poprzednia strona &rarr;',
	'ARCHIVE_PRETITLE'          =>  'Archiwum bloga: ',
	'CATEGORY_PRETITLE'         =>  'Kategoria bloga: ',
  'READ_MORE'                 =>  'Czytaj więcej', // <-- New in 3.4.2

	# Custom Fields Manager
	'CUSTOM_FIELDS'             =>  'Dodatkowe pola',
	'CUSTOMFIELDS_DESCR'        =>  'Ta wtyczka pozwala na dodawanie niestandardowych pól, które można wypełniać dowolną treścią podczas edycji strony.',
	'CUSTOM_FIELDS_OPTIONS_AREA'=>  'Pola opcji',
	'OPTIONS_AREA_DESCRP'       =>  '(te dodatkowe pola będą wyświetlone w opcjach wpisu)',
	'NAME'                      =>  'Nazwa',
	'LABEL'                     =>  'Etykieta',
	'TYPE'                      =>  'Typ',
	'DEFAULT_VALUE'             =>  'Domyślna wartość',
	'ADD'                       =>  'Dodaj nowe pole',
	'CUSTOM_FIELDS_MAIN_AREA'   =>  'Pola główne',
	'MAIN_AREA_DESCRP'          =>  '(te dodatkowe pola będą wyświetlone pod głównym edytorem wpisu)',
	'TEXT_FIELD'                =>  'Pole tekstowe',
	'LONG_TEXT_FIELD'           =>  'Długie pole tekstowe',
	'DROPDOWN_BOX'              =>  'Menu rozwijane',
	'CHECKBOX'                  =>  'Pole zaznaczenia',
	'WYSIWYG_EDITOR'            =>  'Edytor WYSIWYG',
	'TITLE'                     =>  'Tytuł',
	'HIDDEN_FIELD'              =>  'Pole ukryte',

	# VersionCheck Updater
	'VERSION_NOMESSAGE'         =>  'Nie ustawiono żadnej informacji błędu. To problem.',
	'VERSION_NORESPONSE'        =>  'Nie otrzymano odpowiedzi z serwera Extend API.',
	'VERSION_NOFUNCTION'        =>  'Twoje środowisko PHP nie jest skonfigurowane poprawnie.',
	'VERSION_UPDATEAVAILABLE'   =>  'Aktualizacja jest dostępna!',
	'VERSION_UPTODATE'          =>  $plugin.' jest w najnowszej wersji.',
	'VERSION_BETA'              =>  'Obecnie używasz wersji testowej '.$plugin.'.',
	'VERSION_FAILEDCOMPARE'     =>  'Nie udało się porównać wersji podczas sprawdzania aktualizacji.',
	'VERSION_APIFAIL'           =>  'Sprawdzenie przez Extend API nie powiodło się.',
	'VERSION_INTERNALERROR'     =>  'Wystąpił błąd wewnętrzny podczas sprawdzania wersji.',
	'VERSION_STATUS'            =>  'Aktualizacje wtyczki',
	'VERSION_STATUS_DESC'       =>  'Upewnij się, że używasz najnowszej dostępnej wersji wtyczki '.$plugin.', by móc korzystać z najnowszych funkcjonalności i poprawek.',
	'VERSION_UPDATESTATUS'      =>  'Status aktualizacji',
	'VERSION_CURRENTVER'        =>  'Zainstalowana wersja',
	'VERSION_LATESTVER'         =>  'Najnowsza wersja',

);
