<?php
namespace humanid_spam_filter;

$link_to_permalinks    = admin_url() . 'options-permalink.php';
$link_to_dashboard     = admin_url( 'admin.php' ) . '?page=humanid-spam-filter';
$link_to_users_page    = admin_url( 'admin.php' ) . '?page=humanid-spam-filter-users';
$link_to_success_page  = home_url() . '/hid-verification-successful';
$link_to_failure_page  = home_url() . '/hid-verification-failed';
$is_permalinks_updated = get_option( 'hidsf_is_permalink_updated', 0 );
$structure             = get_option( 'permalink_structure' );
?>
<style>
    #wpcontent {
        padding: 0 !important;
    }
</style>

<div id="wrapper">

    <div class="hid-header">
        <img src="<?php echo HIDSF_ASSET_URL . '/images/humanId.png' ?>" alt="">
        <div class="hid-header-menu">
            <ul>
                <li class="active">
                    <a href="#" class="active"><?php esc_html_e( "Home", 'humanid-spam-filter' ) ?></a>
                </li>
                <!--                <li>-->
                <!--                    <a href="--><?php //echo $link_to_users_page
				?><!--">--><?php //esc_html_e( 'Users', 'humanid-spam-filter' )
				?><!--</a>-->
                <!--                </li>-->
            </ul>
        </div>
    </div>
    <div class="pl-20 margin-top-20">
		<?php
		if ( $is_permalinks_updated ) : ?>
			<?php if ( trim( $structure ) == '' ): ?>
                <div class="hid-alert alert-danger">
                    <h1><?php esc_html_e( "Update Permalinks", 'humanid-spam-filter' ) ?></h1>
					<?php esc_html_e( "humanID spam filter will not work with your current permalink structure. Please select one of the structures below:", 'humanid-spam-filter' ) ?>
                    <ol>
                        <li>Day and name</li>
                        <li>Month and name</li>
                        <li>Numeric</li>
                        <li>Post name</li>
                    </ol>

                    <a href="<?php echo esc_url( $link_to_permalinks ) ?>"
                       class="button button-primary"><?php esc_html_e( "Go to permalinks", 'humanid-spam-filter' ) ?>
                    </a>
                </div>
			<?php else: ?>
                <div class="hid-alert alert-info">
                    <h1><?php esc_html_e( "humanID Redirect Urls", 'humanid-spam-filter' ) ?></h1>
					<?php esc_html_e( "Use the urls below as the success and failure redirect urls:", 'humanid-spam-filter' ) ?>
                    <ol>
                        <li>
							<?php esc_html_e( "Success link:", 'humanid-spam-filter' ) ?>
                            <code><?php echo esc_url( $link_to_success_page ) ?></code>
                        </li>
                        <li>
							<?php esc_html_e( "Failure link:", 'humanid-spam-filter' ) ?>
                            <code><?php echo esc_url( $link_to_failure_page ) ?> </code>
                        </li>
                    </ol>
                </div>
			<?php endif; ?>
		<?php else: ?>
            <div class="hid-alert alert-info">
                <h1><?php esc_html_e( "Update Permalinks", 'humanid-spam-filter' ) ?></h1>
				<?php esc_html_e( "WordPress permalink update is required for this plugin to work. Please follow the steps below:", 'humanid-spam-filter' ) ?>
                <ol>
                    <li><?php esc_html_e( "Click on the button below.", 'humanid-spam-filter' ) ?> <br>
                        <a href="<?php echo esc_url( $link_to_permalinks ) ?>"
                           class="button button-primary"><?php esc_html_e( "Go to permalinks", 'humanid-spam-filter' ) ?></a>
                    </li>
                    <li><?php esc_html_e( "Click the Save Changes button.", 'humanid-spam-filter' ) ?></li>
                </ol>

                <a href="<?php echo esc_url( $link_to_dashboard ) . '&updatePermalink=yes' ?>"
                   class="button button-primary">
					<?php esc_html_e( "Yes, I have updated the permalinks", 'humanid-spam-filter' ) ?>
                </a>
            </div>
		<?php endif; ?>

        <h1><?php esc_html_e( 'humanID Account Configuration', 'humanid-spam-filter' ) ?> </h1>
        <strong>
            <?php _e( "You need to create a humanID account. If you don't have one, you can create it", 'humanid-spam-filter' ) ?>
            <a href='https://developers.human-id.org/' target='_blank'><?php _e("here",'humanid-spam-filter')?></a>
        </strong>

		<?php settings_errors(); ?>
        <form method="post" action="options.php">
			<?php
			settings_fields( 'humanid-spam-filter' );
			do_settings_sections( 'humanid-spam-filter' );
			submit_button();
			?>
        </form>
    </div>

</div>
