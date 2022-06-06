<?php
get_header ();

if (have_posts ()) :
	if (is_home () && ! is_front_page ()) :
		?>

	<div class="row wrapper border-bottom white-bg page-heading">
		<div class="col-sm-12">
			<h1><?php _e('Help','jomizsystem') ?></h1>
			<ol class="breadcrumb">
				<li>
					<a href="<?php echo home_url('/') ?>">
						<?php _e('Home','jomizsystem')?>
					</a>
				</li>
				<li class="active"><strong><?php _e('Help','jomizsystem') ?></strong>
				</li>
			</ol>
		</div>
	</div>




	<?php
	endif;?>
		<div class="row">
			<div class="col-lg-12">
				<div class="wrapper wrapper-content animated fadeInRight">

					<?php	
						
		
						while ( have_posts () ) :
		the_post ();
						
		?>

						<div class="vote-item">
							<div class="row">
								<div class="col-md-12">

									<a href="<?php the_permalink();?>" class="vote-title">
										<?php the_title();?>
									</a>
									<div class="vote-info">
										<?php the_excerpt();?>
									</div>
								</div>

							</div>
						</div>

						<?php
	endwhile
	;
	// Previous/next page navigation.
	the_posts_pagination ( array (
			'prev_text' => __ ( 'Previous page', 'twentyfifteen' ),
			'next_text' => __ ( 'Next page', 'twentyfifteen' ),
			'before_page_number' => '<span class="meta-nav screen-reader-text">' . __ ( 'Page', 'twentyfifteen' ) . ' </span>' 
	) );
 // If no content, include the "No posts found" template.
					?>
				</div>
			</div>
		</div>
		<?php
else :
	get_template_part ( 'content', 'none' );
endif;
get_footer ();
?>
