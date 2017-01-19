<div class="blog_list_container">

  <!-- Blog post title
       Only show the title of the post when not on the post itself as there is a
       function in place to add the post's title to the page's title.
  -->
  <?php if(!isset($_GET['post'])) { ?>
    <h3 class="blog_post_title"><a href="<?php echo $post['posturl']; ?>">
      <?php echo $post['title']; ?>
    </a></h3>
  <?php } ?>

  <!-- Blog post information
       This is where all the information about the post is displayed, such as the
       author, date and categories. Add or remove these from here as required.
       The relevant settings have now been removed from the blog admin.
  -->
  <p class="blog_post_info">
    <?php if(!empty($post['author'])){?><span class="blog_post_author"><?php i18n(BLOGFILE.'/BY'); ?> <?php echo $post['author']; ?></span><?php } ?>
    <?php if(!empty($post['date'])){ ?>
      <span class="blog_post_date"><?php i18n(BLOGFILE.'/ON'); ?>
        <a href="<?php echo $post['archiveurl'].$post['archivedate']; ?>" title="<?php echo i18n_r(BLOGFILE.'/ARCHIVE_PRETITLE').$post['archivetitle']; ?>">
          <?php echo $post['date']; ?>
        </a>
      </span>
    <?php } if(!empty($post['categories'][0])) { ?>
      <span class="blog_post_category"><?php i18n(BLOGFILE.'/IN'); ?>
        <a href="<?php echo $post['categoryurl'].$post['categories'][0]; ?>" title="<?php echo i18n_r(BLOGFILE.'/CATEGORY_PRETITLE').$post['categories'][0]; ?>">
          <?php echo $post['categories'][0]; ?>
        </a>
      </span>
    <?php } ?>
  </p>

  <!-- Post thumbnail
       This is the thumbnail for the post. It should be wrapped in an <a> linking to the full post.
       Images are currently (v3.3.0) stored in the '/data/uploads' folder.
  -->
  <?php if(!empty($post['thumbnail'])) { ?>
    <a href="<?php echo $post['posturl']; ?>">
      <img src="<?php echo $post['thumburl'].$post['thumbnail']; ?>" class="blog_post_thumbnail" />
    </a>
  <?php } ?>

  <!-- Post content
       This is where the content of the post will go. This will output the excerpt on the main page
       or the full post on the post's page. Only the output tag is required as the internal functions
       automatically determine showing of excerpt or full post.
  -->
  <div class="blog_post_content">
    <?php echo $post['content']; ?>
  </div>

  <!-- Post tags
       Show the tags that the post has been tagged with. This function outputs an array of tags where
       it is recommended that you use a foreach loop to create your list with a link in each one.
       $post['tagurl'] is available to give you the base url for the tags, just add the tag to the end
       of the final url.
  -->
  <?php if (!empty($post['tags'])) { ?>
    <p class="blog_tags">
      <b><?php i18n(BLOGFILE.'/TAGS'); ?> :</b>
      <?php
          foreach ($post['tags'] as $tag) {
            echo "<a href='".$post['tagsurl'].$tag."'>".$tag."</a>";
            if(end($post['tags']) !== $tag){
                echo ', &nbsp'; // comma if not the last element
            }
          }
      ?>
    </p>
  <?php } ?>

</div>
