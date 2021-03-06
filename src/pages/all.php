<?php
  include_once('../includes/session.php');
  include_once('../database/db_story.php');
  include_once('../database/db_post.php');
  include_once('../database/db_channel.php');
  include_once('../templates/tpl_common.php');
  include_once('../templates/tpl_stories.php');
  include_once('../database/db_comment.php');
  include_once('../database/db_user.php');

  $stories = getAllStories();

  if(isset($_SESSION['username']))
    draw_header($_SESSION['username']);
  else
    draw_header(null);

  foreach ($stories as $k => $story) {
      $stories[$k]['story_comments'] = getChildComments($story['post_id']);
  }

  draw_stories($stories);
  draw_footer();
?>