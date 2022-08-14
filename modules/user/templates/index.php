<?php
namespace humanid_spam_filter;

$link_to_dashboard     = admin_url( 'admin.php' ) . '?page=humanid-spam-filter';
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
                <li>
                    <a href="<?php echo $link_to_dashboard ?>"><?php _e( "Home", HIDSF_TEXT_DOMAIN ) ?></a>
                </li>
                <li class="active">
                    <a href="#" class="active"><?php _e( 'Users', HIDSF_TEXT_DOMAIN ) ?></a>
                </li>
            </ul>
        </div>
    </div>
    <div class="pl-20 margin-top-20">

        <h1><?php _e( 'Human ID Account Configuration', HIDSF_TEXT_DOMAIN ) ?> </h1>
        <strong><?php _e( "You need to create a HumanID account. If you don't have one, you can create it <a href='https://developers.human-id.org/' target='_blank'>here</a>", HIDSF_TEXT_DOMAIN ) ?></strong>

    </div>

</div>
