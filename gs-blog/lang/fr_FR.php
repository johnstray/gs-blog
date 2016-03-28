<?php if(!defined('IN_GS')){die('You cannot load this file directly!');} // Security Check
/**************************************************************************************************\
* French (Français) Language File for GetSimple Blog                                               *
* ------------------------------------------------------------------------------------------------ *
* Last Modified: 21 March 2016                                                                     *
* Compiled By: gschintgen                                                                          *
\**************************************************************************************************/
 
$i18n = array(
  
  # Language Configuration
  'LANGUAGE_CODE'             =>  'fr_FR',
  'DATE_FORMAT'               =>  'd/m/Y h:i:s a',
  'DATE_DISPLAY'              =>  'F jS, Y',
  'DATE_ARCHIVE'              =>  'F Y',
  
  # Plugin Information
  'PLUGIN_TITLE'              =>  ($plugin = 'GetSimple Blog'),
  'PLUGIN_DESC'               =>  'Une gestion de Blog simple et efficace pour le CMS GetSimple',
  
  # Tab/Sidebar Actions (Administration)
  'BLOG_TAB_BUTTON'           =>  'B<em>l</em>og',
  'MANAGE_POSTS_BUTTON'       =>  'Gérer les articles',
  'CATEGORIES_BUTTON'         =>  'Catégories',
  'AUTOIMPORTER_BUTTON'       =>  'RSS Auto-Importer',
  'SETTINGS_BUTTON'           =>  'Paramètres',
  'UPDATE_BUTTON'             =>  'Mise à jour',
  'HELP_BUTTON'               =>  'Aide',
  
  # Generic Strings
  'WRITE_OK'                  =>  'Fichier enregistré avec succès&nbsp;!',
  'EDIT_OK'                   =>  'Fichier modifié avec succès&nbsp;!',
  'DATA_FILE_ERROR'           =>  'Le fichier ne peut être enregistré&nbsp;!',
  'CANCEL'                    =>  'Annuler',
  'DELETE'                    =>  'Effacer',
  'SAVE'                      =>  'Enregistrer',
  'OR'                        =>  'Ou',
  'YES'                       =>  'Oui',
  'NO'                        =>  'Non',
  'ADDED'                     =>  'Ajouté',
  'MANAGE'                    =>  'Gestion',
  'LANGUAGE'                  =>  'Langue',
  'DATE'                      =>  'Date',
  
  # Class Constructor
  'DATA_BLOG_DIR'             =>  'Répertoire <em>data/blog</em> créé avec succès.',
  'DATA_BLOG_DIR_ERR'         =>  'Le répertoire <em>data/blog</em> ne peut être créé&nbsp;!',
  'DATA_BLOG_DIR_ERR_HINT'    =>  'Vous devez créer ce répertoire vous-même pour que le plugin fonctionne convenablement.',
  'DATA_BLOG_CATEGORIES'      =>  'Fichier <em>blog_categories.xml</em> créé avec succès&nbsp;!',
  'DATA_BLOG_CATEGORIES_ERR'  =>  'Le fichier <em>blog_categories.xml</em> ne peut être créé&nbsp;!',
  'DATA_BLOG_RSS'             =>  'Fichier <em>blog_rss.xml</em> créé avec succès&nbsp;!',
  'DATA_BLOG_RSS_ERR'         =>  'Le fichier <em>blog_rss.xml</em> ne peut être créé&nbsp;!',
  'BLOG_SETTINGS'             =>  'Paramètres du Blog',
  
  # 'Post Management' Strings
  'POST_ADDED'                =>  'Article sauvegardé avec succès.',
  'POST_ERROR'                =>  'L&apos;article ne peut être sauvegardé&nbsp;!',
  'POST_DELETED'              =>  'Article effacé avec succès.',
  'POST_DELETE_ERROR'         =>  'L&apos;article n&apos;a pu être effacé&nbsp;!',
  'BLOG_CREATE_EDIT_NO_TITLE' =>  'Un titre est requis afin de pouvoir sauvegarder l&apos;article !',
  'BLOG_RETURN_TO_PREV_PAGE'  =>  'Retour à l&apos;article',
  'ADD_NEW_POST'              =>  'Ajouter un article',
  'EDIT_EXISTING_POST'        =>  'Modifier l&apos;article',
  'VIEW_POST'                 =>  'Afficher l&apos;article',
  'POST_OPTIONS'              =>  'Options de l&apos;article',
  'UPLOAD_THUMBNAIL'          =>  'Charger miniature',
  'UPLOAD_ENABLE_JAVASCRIPT'  =>  'Vous devez activer JavaScript afin de pouvoir utiliser le téléchargeur&nbsp;!',
  'SAVE_POST'                 =>  'Enregistrer l&apos;article',
  'MANAGE_POSTS'              =>  'Articles',
  'CUSTOM_FIELDS_BUTTON'      =>  'Champs personnalisés',
  'NEW_POST_BUTTON'           =>  'Créer un article',
  'MANAGE_POSTS_DESC'         =>  'Modifier un article existant ou créer un nouvel article. La table ci-dessous montre les articles existants.',
  'NO_POSTS'                  =>  'Il n&apos;y a pas d&apos;articles à afficher&nbsp;!',
  'CLICK_TO_CREATE'           =>  'Cliquez ici pour en créer un',
  'PAGE_TITLE'                =>  'Titre de la page',
  
  # 'Category Management' Strings
  'CATEGORY_ADDED'            =>  'Catégorie créée avec succès.',
  'CATEGORY_ERROR'            =>  'La catégorie n&apos;a pu être enregistrée&nbsp;!',
  'MANAGE_CATEGORIES'         =>  'Gérer les catégories',
  'ADD_CATEGORY'              =>  'Ajouter une catégorie',
  'SETTINGS_CATEGORY_DESC'    =>  'Ajouter ou modifier des catégories pour organiser vos articles. Ceci permet de trier vos articles et de n&apos;afficher que ceux appartenant à une catégorie donnée.',
  'CATEGORY_NAME'             =>  'Nom de la catégorie',
  'CATEGORY_RSS_FEED'         =>  'Flux RSS de la catégorie',
  
  # 'RSS Auto-Importer' Strings
  'FEED_ADDED'                =>  'Flux RSS ajouté avec succès.',
  'FEED_ERROR'                =>  'Le flux RSS n&apos;a pu être enregistré&nbsp;!',
  'FEED_DELETED'              =>  'Flux RSS supprimé avec succès.',
  'FEED_DELETE_ERROR'         =>  'Le flux RSS n&apos;a pu être supprimé&nbsp;!',
  'READ_FULL_ARTICLE'         =>  'Lire l&apos;article entier',
  'RSS_FEED_NO_POSTS_DESC'    =>  'Il n&apos;y a pas d&apos;articles disponibles pour ce flux RSS. Veuillez contacter l&apos;administrateur du site web pour de plus amples renseignements.',
  'RSS_FILE_OPEN_FAIL'        =>  'Le fichier <em>rss.rss</em> n&apos;a pu être ouvert&nbsp;!',
  'RSS_FILE_WRITE_FAIL'       =>  'Le fichier <em>rss.rss</em> n&apos;a pu être enregistré&nbsp;!',
  'RSS_HEADER'                =>  'Importateur de flux RSS',
  'ADD_FEED'                  =>  'Ajouter un flux RSS',
  'SETTINGS_FEED_DESC'        =>  'L&apos;importateur automatique de flux RSS crée des articles basés sur des flux RSS d&apos;autres sites web. Ceci est utile si vous voulez gérer un blog avec du contenu issu d&apos;un autre blog.',
  'ADD_NEW_FEED'              =>  'Ajouter flux RSS',
  'BLOG_CATEGORY'             =>  'Catégorie du blog',
  'RSS_FEED'                  =>  'Flux RSS',
  'FEED_CATEGORY'             =>  'Catégorie du flux RSS',
  'DELETE_FEED'               =>  'Effacer le flux',
  'RSS_SETTINGS_HEADER'       =>  'Paramètres de l&apos;importateur RSS',
  'SETTINGS_RSS_DESC'         =>  'Configurez les paramètres de l&apos;importateur automatique de flux RSS. L&apos;importateur automatique peut importer ou bien le contenu entier des flux ou bien un extrait avec un lien vers l&apos;article original.',
  'RSS_IMPORTER'              =>  'Activer l&apos;importateur RSS',
  'RSS_CONTENT_DESCRIPTION'   =>  'Inclure contenu entier du RSS ou extrait',
  'RSS_INCLUDE_CONTENT'       =>  'Contenu entier',
  'RSS_INCLUDE_EXCERPT'       =>  'Extrait',
  'RSS_TITLE'                 =>  'Titre du flux RSS',
  'RSS_DESCRIPTION'           =>  'Description du flux RSS',
  'RSS_IMPORTER_PASS'         =>  'Mot de passe pour l&apos;importateur RSS',
  'RSS_FEED_NUM_POSTS'        =>  '&num; d&apos;articles dans le flux RSS',
  
  # 'Settings' Strings
  'SETTINGS_SAVE_OK'          =>  'Paramètres enregistrés avec succès&nbsp;!',
  'SETTINGS_SAVE_ERROR'       =>  'Impossible de sauvegarder vos paramètres&nbsp;!',
  'BLOG_SETTINGS'             =>  'Paramètres du Blog',
  'SETTINGS_MAIN_DESC'        =>  'Gérer les paramètres de votre blog. Il s&apos;agit des paramètres principaux du blog.',
  'PAGE_URL'                  =>  'Page utilisée pour afficher les articles du blog',
  'EXCERPT_OPTION'            =>  'Format des articles',
  'FULL_TEXT'                 =>  'Texte complet',
  'EXCERPT'                   =>  'Extrait',
  'EXCERPT_LENGTH'            =>  'Longueur de l&apos;extrait (caractères)&nbsp;:',
  'POSTS_PER_PAGE'            =>  '&num; d&apos;articles par page',
  'RECENT_POSTS'              =>  '&num; d&apos;articles récents',
  'DISPLAY_POST_COUNT_ARCH'   =>  'Afficher le nombre d&apos;articles dans les Archives',
  'HTACCESS_HEADLINE'         =>  'URLs simplifiées',
  'PRETTY_URLS'               =>  'Utiliser les URLs simplifiées',
  'VIEW_HTACCESS'             =>  'Montrer ce que le fichier .htaccess doit contenir.',
  'PRETTY_URLS_PARA'          =>  'Si vous acceptez, pensez à éditer votre .htaccess après avoir sauvegardé cette configuration.',
  'HTACCESS_1'                =>  'Bloque l&apos;accès direct aux fichiers XML (qui contiennent toutes les données!)',
  'HTACCESS_2'                =>  'En règle générale RewriteBase est simplement &apos;/&apos;; remplacez-le par le chemin d&apos;accès du sous-réportoire',
  'HTACCESS_3'                =>  'IMPORTANT -> si votre site est localisé dans un sous-répertoire, vous devez l&apos;indiquer ici (p.ex.: /sousrepertoire/)',
  'BLOG_PRETTY_NOTICE'        =>  'Vous devez au préalable activer les URLs simplifiées dans GetSimple afin d&apos;afficher des instructions ici',
  'SAVE_SETTINGS'             =>  'Enregistrer les paramètres',
  'MAIN_SETTINGS_BUTTON'      =>  'Paramètres principaux',
  
  # 'Help' Strings
  'HELP'                      =>  'Aide',
  'FRONT_END_FUNCTIONS'       =>  'Fonctions du front-end',
  'HELP_CATEGORIES'           =>  'Afficher les catégories du blog',
  'HELP_SEARCH'               =>  'Afficher la barre de recherche du blog',
  'HELP_ARCHIVES'             =>  'Afficher les archives du blog',
  'HELP_RECENT'               =>  'Afficher les articles les plus récents du blog',
  'HELP_RECENT_2'             =>  'Cette fonction prend 4 arguments <strong>optionnels</strong>',
  'HELP_RECENT_3'             =>  'Exemple d&apos;utilisation (afficher extrait, longueur par défaut de l&apos;extrait, afficher miniature et afficher lien pour lire la suite)',
  'RSS_LOCATION'              =>  'Ci-dessous vous trouvez le flux RSS de votre blog',
  'DYNAMIC_RSS_LOCATION'      =>  'Emplacement du flux RSS dynamique (Crée un flux RSS à la volée)',
  'AUTO_IMPORTER_TITLE'       =>  'Configuration d&apos;un cronjob pour l&apos;importateur RSS',
  'AUTO_IMPORTER_DESC'        =>  'Normalement vous avez la possibilité de configurer des cronjobs dans l&apos;interface d&apos;administration de votre hébergeur. Ce plugin suppose que vous savez comment faire. L&apos;extrait ci-dessous montre la commande à effectuer par cron.',
  'BLOG_PAGE_DESC_TITLE'      =>  'Personnaliser la page du blog',
  'BLOG_PAGE_RECOM'           =>  'Il est recommandé de consulter la fonction <code>show_blog_post()</code> dans plugins/blog/inc/frontEndFunctions.php pour voir comment personnaliser la page du blog.',
  'BLOG_PAGE_DESC_LINE_1'     =>  'Vous pouvez utiliser du code html, php, xml et js dans ce champ de texte.',
  'BLOG_PAGE_DESC_LINE_2'     =>  'C&apos;est comme si vous codiez directement dans le plugin. Les données de l&apos;article seront passées moyennant un objet.',
  'BLOG_PAGE_DESC_LINE_3'     =>  'Ainsi, pour accéder à un champ personnalisé, vous pouvez faire comme suit',
  'BLOG_PAGE_AVAILABLE_FUNC'  =>  'Fonctions disponibles&nbsp;:',
  'BLOG_PAGE_FRMT_DATE_LABEL' =>  'Formater une date',
  'BLOG_PAGE_FRMT_DATA_DESC'  =>  'Vous passez les données directement depuis l&apos;objet et la fonction formatera la date.',
  'BLOG_PAGE_GET_URL_TO_AREAS'=>  'Obtenir URL du blog',
  'BLOG_PAGE_URL_EX_LABEL'    =>  'Ex. (obtenir URL d&apos;un article)',
  'BLOG_PAGE_AVAILABLE_AREAS' =>  'Domaines disponibles',
  'BLOG_PAGE_POST'            =>  'article',
  'BLOG_PAGE_TAG'             =>  'tag',
  'BLOG_PAGE_PAGE'            =>  'page',
  'BLOG_PAGE_ARCHIVE'         =>  'archive',
  'BLOG_PAGE_CATEGORY'        =>  'catégorie',
  'BLOG_PAGE_CREATE_EXCERPT'  =>  'Créer extrait',
  'BLOG_PAGE_EXCERPT_DESC'    =>  'Ceci crée un extrait de longueur donnée. La variable $excerpt_length doit être un entier et indique la longueur de l&apos;extrait.',
  'BLOG_PAGE_DECODE_CONTENT'  =>  'Décoder contenu',
  
  # 'Front-End' Strings
  'BY'                        =>  'par',
  'ON'                        =>  'le',
  'IN'                        =>  'dans',
  'TAGS'                      =>  'Tags',
  'NO_CATEGORIES'             =>  'Il n&apos;y a pas de catégories à afficher&nbsp;!',
  'NO_POSTS'                  =>  'Aucun article trouvé',
  'NO_ARCHIVES'               =>  'Il n&apos;y a pas d&apos;archives à afficher&nbsp;!',
  'SEARCH'                    =>  'Recherche',
  'FOUND'                     =>  'Ces articles ont été trouvés&nbsp;:',
  'NOT_FOUND'                 =>  'Aucun article trouvé.',
  'NEXT_PAGE'                 =>  '&larr; Page suivante',
  'PREV_PAGE'                 =>  'Page précédente &rarr;',
  'ARCHIVE_PRETITLE'          =>  'Archives du blog&nbsp;: ',
  'CATEGORY_PRETITLE'         =>  'Catégorie du blog&nbsp;: ',
  'READ_MORE'                 =>  'Lire la suite', // <-- New in 3.4.2
  
  # Custom Fields Manager
  'CUSTOM_FIELDS'             =>  'Champs personnalisés',
  'CUSTOMFIELDS_DESCR'        =>  'Ce plugin permet de spécifier des champs personnalisés qui seront affichés quand vous modifiez une page.',
  'CUSTOM_FIELDS_OPTIONS_AREA'=>  'Zone des Options',
  'OPTIONS_AREA_DESCRP'       =>  '(Ces champs seront affichés dans la section "Options de l&apos;article".)',
  'NAME'                      =>  'Nom',
  'LABEL'                     =>  'Étiquette',
  'TYPE'                      =>  'Type',
  'DEFAULT_VALUE'             =>  'Valeur par défaut',
  'ADD'                       =>  'Ajouter un nouveau champ',
  'CUSTOM_FIELDS_MAIN_AREA'   =>  'Zone principale',
  'MAIN_AREA_DESCRP'          =>  '(Ces champs apparaîtront <em>en-dessous</em> des "Options d&apos;article".)',
  'TEXT_FIELD'                =>  'Champ de texte',
  'LONG_TEXT_FIELD'           =>  'Champ texte (long)',
  'DROPDOWN_BOX'              =>  'Liste déroulante',
  'CHECKBOX'                  =>  'Case à cocher',
  'WYSIWYG_EDITOR'            =>  'Éditeur WYSIWYG',
  'TITLE'                     =>  'Titre',
  'HIDDEN_FIELD'              =>  'Champ caché',
  
  # VersionCheck Updater
  'VERSION_NOMESSAGE'         =>  'Pas de message d&apos;erreur donné&nbsp;! Ceci est un problème.',
  'VERSION_NORESPONSE'        =>  'Pas de réponse du serveur de l&apos;Extend API.',
  'VERSION_NOFUNCTION'        =>  'Votre environnement PHP n&apos;est pas configuré correctement.',
  'VERSION_UPDATEAVAILABLE'   =>  'Une mise à jour est disponible&nbsp;!',
  'VERSION_UPTODATE'          =>  $plugin.' est à jour&nbsp;!',
  'VERSION_BETA'              =>  'Vous utilisez actuellement une version bêta de '.$plugin.'.',
  'VERSION_FAILEDCOMPARE'     =>  'Erreur lors de la comparaison de versions.',
  'VERSION_APIFAIL'           =>  'L&apos;appel de l&apos;Extend API a échoué.',
  'VERSION_INTERNALERROR'     =>  'Une erreur interne est apparue dans VersionCheck.',
  'VERSION_STATUS'            =>  'Mises à jour',
  'VERSION_STATUS_DESC'       =>  'Assurez-vous que vous utilisez la dernière version du plugin '.$plugin.' afin de bénéficier des dernières corrections et fonctionnalités',
  'VERSION_UPDATESTATUS'      =>  'État des mises à jour',
  'VERSION_CURRENTVER'        =>  'Version actuelle',
  'VERSION_LATESTVER'         =>  'Dernière version disponible',
  
);
