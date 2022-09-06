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
                    <a href="#" class="active"><?php _e( "Home", HIDSF_TEXT_DOMAIN ) ?></a>
                </li>
<!--                <li>-->
<!--                    <a href="--><?php //echo $link_to_users_page ?><!--">--><?php //_e( 'Users', HIDSF_TEXT_DOMAIN ) ?><!--</a>-->
<!--                </li>-->
            </ul>
        </div>
    </div>
    <div class="pl-20 margin-top-20">
		<?php
		if ( $is_permalinks_updated ) : ?>
			<?php if ( trim( $structure ) == '' ): ?>
                <div class="hid-alert alert-danger">
                    <h1><?php _e( "Update Permalinks", HIDSF_TEXT_DOMAIN ) ?></h1>
					<?php _e( "humanID spam filter will not work with your current permalink structure. Please select one of the structures below:", HIDSF_TEXT_DOMAIN ) ?>
                    <ol>
                        <li>Day and name</li>
                        <li>Month and name</li>
                        <li>Numeric</li>
                        <li>Post name</li>
                    </ol>

                    <a href="<?php echo $link_to_permalinks ?>"
                       class="button button-primary"><?php _e( "Go to permalinks", HIDSF_TEXT_DOMAIN ) ?>
                    </a>
                </div>
			<?php else: ?>
                <div class="hid-alert alert-info">
                    <h1><?php _e( "humanID Redirect Urls", HIDSF_TEXT_DOMAIN ) ?></h1>
					<?php _e( "Use the urls below as the success and failure redirect urls:", HIDSF_TEXT_DOMAIN ) ?>
                    <ol>
                        <li>
							<?php _e( "Success link:", HIDSF_TEXT_DOMAIN ) ?>
                            <code><?php echo $link_to_success_page ?></code>
                        </li>
                        <li>
							<?php _e( "Failure link:", HIDSF_TEXT_DOMAIN ) ?>
                            <code><?php echo $link_to_failure_page ?> </code>
                        </li>
                    </ol>
                </div>
			<?php endif; ?>
		<?php else: ?>
            <div class="hid-alert alert-info">
                <h1><?php _e( "Update Permalinks", HIDSF_TEXT_DOMAIN ) ?></h1>
				<?php _e( "WordPress permalink update is required for this plugin to work. Please follow the steps below:", HIDSF_TEXT_DOMAIN ) ?>
                <ol>
                    <li><?php _e( "Click on the button below.", HIDSF_TEXT_DOMAIN ) ?> <br>
                        <a href="<?php echo $link_to_permalinks ?>"
                           class="button button-primary"><?php _e( "Go to permalinks", HIDSF_TEXT_DOMAIN ) ?></a>
                    </li>
                    <li><?php _e( "Click the Save Changes button.", HIDSF_TEXT_DOMAIN ) ?></li>
                </ol>

                <a href="<?php echo $link_to_dashboard . '&updatePermalink=yes' ?>" class="button button-primary">
					<?php _e( "Yes, I have updated the permalinks", HIDSF_TEXT_DOMAIN ) ?>
                </a>
            </div>
		<?php endif; ?>

        <h1><?php _e( 'Human ID Account Configuration', HIDSF_TEXT_DOMAIN ) ?> </h1>
        <strong><?php _e( "You need to create a humanID account. If you don't have one, you can create it <a href='https://developers.human-id.org/' target='_blank'>here</a>", HIDSF_TEXT_DOMAIN ) ?></strong>
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
