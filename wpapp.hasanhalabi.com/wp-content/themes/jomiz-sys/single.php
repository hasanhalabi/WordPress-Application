<?php get_header(); 
the_post(); ?>
    <div class="row wrapper border-bottom white-bg page-heading">
        <div class="col-sm-12">
            <h1><?php the_title(); ?></h1>
            <ol class="breadcrumb">
                <li>
                    <a href="<?php echo home_url('/') ?>">
                        <?php _e('Home','jomizsystem')?>
                    </a>
                </li>
                <li>
                    <a href="<?php echo home_url('/help') ?>">
                        <?php _e('Help','jomizsystem')?>
                    </a>
                </li>
                <li class="active"><strong><?php the_title(); ?></strong>
                </li>
            </ol>
        </div>
    </div>
    <div class="wrapper wrapper-content  animated fadeInRight article">
        <div class="row">
            <div class="col-lg-10 col-lg-offset-1">
                <div class="ibox">
                    <div class="ibox-content jomiz-help-article">
                        <div class="text-center article-title">
                            <h1>
                                   <?php the_title(); ?>
                                </h1>
                        </div>
                        <div>
                            <?php the_content(); ?>
                        </div>

                    </div>
                </div>
            </div>
        </div>


    </div>
    <!-- #content -->

    <!-- #container -->


    <?php get_footer(); ?>
