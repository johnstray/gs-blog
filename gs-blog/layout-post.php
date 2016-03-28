<div class="blog_post_container">

  <!-- HTML5 Scoped StyleSheet
       Add any css styles that you require to this style declaration. A scoped
       style sheet prevents your styles from contaminating the website's theme.
       Scoped style sheets are virtually backwards compatible with most browsers.
  -->
  <style scoped>
    .blog_post_thumbnail {
      width:200px;
      height:auto;
      float:left;
      padding-right:10px;
      padding-bottom:10px;
    }
    .blog_post_container {
      clear:both;
    }	
  </style>
  
  <!-- Blog post information
       This is where all the information about the post is displayed, such as the
       author, date and categories. Add or remove these from here as required.
       The relevant settings have now been removed from the blog admin.
  -->
  <p class="blog_post_info">
    <?php if(!empty($p['author'])){?><span class="blog_post_author"><?php i18n(BLOGFILE.'/BY'); ?> <?php echo $p['author']; ?></span><?php } ?>
    <?php if(!empty($p['date'])){ ?>
      <span class="blog_post_date"><?php i18n(BLOGFILE.'/ON'); ?>
        <a href="<?php echo $p['archiveurl'].$p['archivedate']; ?>" title="<?php echo i18n_r(BLOGFILE.'/ARCHIVE_PRETITLE').$p['archivetitle']; ?>">
          <?php echo $p['date']; ?>
        </a>
      </span>
    <?php } if(!empty($p['categories'][0])) { ?>
      <span class="blog_post_category"><?php i18n(BLOGFILE.'/IN'); ?>
        <a href="<?php echo $p['categoryurl'].$p['categories'][0]; ?>" title="<?php echo i18n_r(BLOGFILE.'/CATEGORY_PRETITLE').$p['categories'][0]; ?>">
          <?php echo $p['categories'][0]; ?>
        </a>
      </span>
    <?php } ?>
  </p>
  
  <!-- Post thumbnail
       This is the thumbnail for the post. It should be wrapped in an <a> linking to the full post.
       Images are currently (v3.3.0+) stored in the '/data/uploads' folder.
       If you don't want to show a thumbnail on the full post, remove this code.
  -->
  <?php if(!empty($p['thumbnail'])) { ?>
    <a href="<?php echo $p['posturl']; ?>">
      <img src="<?php echo $p['thumburl'].$p['thumbnail']; ?>" class="blog_post_thumbnail" />
    </a>
  <?php } ?>
  
  <!-- Post content
       This is where the content of the post will go. This will output the excerpt on the main page
       or the full post on the post's page. Only the output tag is required as the internal functions
       automatically determine showing of excerpt or full post.
  -->
  <div class="blog_post_content">
    <?php echo $p['content']; ?>
  </div>
  
  <!-- Post tags
       Show the tags that the post has been tagged with. This function outputs an array of tags where
       it is recommended that you use a foreach loop to create your list with a link in each one.
       $p['tagurl'] is available to give you the base url for the tags, just add the tag to the end
       of the final url.
  -->
  <p class="blog_tags">
    <b><?php i18n(BLOGFILE.'/TAGS'); ?> :</b>
    <?php foreach ($p['tags'] as $tag) { ?>
      <a href="<?php echo $p['tagsurl'].$tag; ?>"><?php echo $tag; ?></a>,&nbsp;
    <?php } ?>
  </p>
  
</div>
