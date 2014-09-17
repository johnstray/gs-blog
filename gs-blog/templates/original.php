<div class="blog_post_container">

  <!-- HTML5 Scoped StyleSheet
       Add any css styles that you require to this style declaration. A scoped
       stylesheet prevents your styles from contaminating the website's theme.
       Scoped stylesheets are virtually backwards compatible with most browsers.
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
  
  <!-- Advertising Code - Top
       Insert your advertising code here, such as Google AdSense, etc. This was
       kept purely for those that actually used it. Personally, I HATE ADVERTS!
  -->
  <div class="blog_all_posts_ad ad_top">
    <?php echo $blogSettings["addata"]; ?>
  </div>
  
  <!-- Blog post title
       Only show the title of the post when not on the post itself as there is a
       function in place to add the post's title to the page's title.
  -->
  <?php if(!isset($_GET['post'])) { ?>
    <h3 class="blog_post_title"><a href="<?php echo $p['posturl']; ?>">
      <?php echo $p['title']; ?>
    </a></h3>
  <?php } ?>
  
  <!-- Blog post information
       This is where all the information about the post is displayed, such as the
       author, date and categories. Add or remove these from here as required.
       The relevant settings have now been removed from the blog admin.
  -->
  <p class="blog_post_info">
    <span class="blog_post_author"><?php i18n(BLOGFILE.'/BY'); ?> <?php echo $p['author']; ?></span>
    <span class="blog_post_date"><?php i18n(BLOGFILE.'/ON'); ?> <?php echo date('F jS, Y', $p['date']); ?></span>
    <span class="blog_post_category"><?php i18n(BLOGFILE.'/IN'); ?> <?php echo $p['categories'][0]; ?></span>
  </p>
  
  <!-- Post thumbnail
       This is the thumbnail for the post. It should be wrapped in an <a> linking to the full post.
       Images are currently (v3.3.0) stored in the '/data/uploads' folder.
  -->
  <a href="<?php echo $p['posturl']; ?>">
    <img src="<?php echo $p['thumburl']; ?>" class="blog_post_thumbnail" />
  </a>
  
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
    <?php echo $p['tags'][0]; ?>
  </p>
  
  <!-- The stupid Go-Back link
       This is that stupid Go-Back link that a few people have complained about. I think that it
       should probably be removed, but I've left it in this template for historical reasons.
  -->
  <p class="blog_go_back"><a href="javascript:history.back()">&laquo; <?php i18n(BLOGFILE.'/GO_BACK'); ?></a></p>
  
  <!-- Advertising Code - Bottom
       Insert your advertising code here, such as Google AdSense, etc. This was
       kept purely for those that actually used it. Personally, I HATE ADVERTS!
  -->
  <div class="blog_all_posts_ad ad_bottom">
    <?php echo $blogSettings["addata"]; ?>
  </div>
  
</div>
