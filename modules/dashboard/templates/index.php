<?php
namespace humanid_spam_filter;
$link_to_filters = admin_url( 'admin.php' ) . '?page=kmcf7-message-filter-options&tab=filters';
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
                    <a href="" class="active">Home</a>
                </li>
                <li>
                    <a href=""><?php _e( 'Users', HIDSF_TEXT_DOMAIN ) ?></a>
                </li>
            </ul>
        </div>
    </div>
    <div class="pl-20">
        <h1><?php _e( 'Human ID Account Configuration', HIDSF_TEXT_DOMAIN ) ?> </h1>
        <strong><?php _e( "You need to create a HumanID account. If you don't have one, you can create it <a href='' target='_blank'>here</a>", HIDSF_TEXT_DOMAIN ) ?></strong>
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
