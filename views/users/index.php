<?php
namespace humanid_spam_filter;

$link_to_dashboard = admin_url( 'admin.php' ) . '?page=humanid-spam-filter';
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
                    <a href="<?php echo esc_html($link_to_dashboard) ?>"><?php esc_html_e( "Home", 'humanid-spam-filter' ) ?></a>
                </li>
                <li class="active">
                    <a href="#" class="active"><?php esc_html_e( 'Users', 'humanid-spam-filter' ) ?></a>
                </li>
            </ul>
        </div>
    </div>
    <div class="pl-20 margin-top-20">


    </div>

</div>
