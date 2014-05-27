
<div id="content">
<?php include_once('sidebar.php'); ?>
  <div id="main-content"><!-- MAIN CONTENT -->
    <div id="main">
      <div class="item">
        <div class="item-title">
          <h2>
            <?= $page->PageTitle(); ?>
          </h2>
        </div>
        <div class="main-item">
          <?php $page->ContentInc(); ?>
        </div>
      </div>
    </div>
  </div>
  <!--END MAIN CONTENT -->
  <div style="clear:both"></div>
</div>
