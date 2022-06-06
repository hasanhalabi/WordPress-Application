<div class="modal fade" id="PrintingModal" role="dialog">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="modal-title">طباعة</h4>
            </div>
            <div class="modal-body">
                <iframe id="print_iframe_url" src=""></iframe>
            </div>
            <!--            <div class="modal-footer">
                            <button type="button" class="btn btn-xs btn-danger" data-dismiss="modal">إغلاق</button>
                        </div>-->
        </div>

    </div>
</div>
<div class="footer fixed hidden-print hidden-xs hidden-sm">
  <div class="pull-left">
    <div class="ltr-inline-content">
        Powered By <a href="<?php echo Powered_BY_URL?>" target="_blank"><?php echo Powered_BY_NAME?></a>
    </div>
      
  </div>
  <div>
	<?php if(defined('Footer_System_Name')):
			echo '<strong>'.Footer_System_Name.'</strong>';
		else: ?>
		<strong><?php bloginfo('name') ?></strong>
		<?php bloginfo('description') ?> 
	<?php endif; ?>
	&copy; <?php echo date("Y") ?>
  </div>
</div>

</div>
</div>
<?php wp_footer(); ?>



  </body>

  </html>
