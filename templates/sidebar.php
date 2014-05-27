<?php $Link = new Link(); ?>
<div id="sidebar-wrap" class="sbar"><!-- Sidebar -->
  <div style="margin:10px 2px;font-size:18px;color:#06F;text-align:center;font-weight:bold;border:1px solid #0080FF;background-color:#CAE4FF">
    <?php if($user->getUsername()){echo $user->getUsername();}else{echo "Guest";} ?>
  </div>
  <div id="sidebar-nav">
    <ul id="s-nav">
      <li><a href="/mylink.php">My Link list</a></li>
      <li><a href="/profile.php">Profile</a></li>
    </ul>
  </div>
  
  <div id="widgets"><!-- Link for click -->
    <div class="title">
      <p><strong>Link to Click</strong></p>
    </div>
    <div class="widget_content">
      <ol id="link_suggestion_sidebar">
        <?php foreach($Link->getRandomLink() as $key=>$row) : ?>
        <li>
          <div><a title="<?= $row['title'] ?>" href="/<?= $row['short_code']?>" target="_blank" rel="noreferrer" rel="nofollow">
            <?= $row['short_code']?>
            </a></div>
        </li>
        <?php endforeach ?>
      </ol>
    </div>
  </div><!-- end Link for click -->
  
</div>
<!-- END SIDEBAR --> 