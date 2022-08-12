<?php
namespace hid_spam_filter;
$link_to_filters = admin_url( 'admin.php' ) . '?page=kmcf7-message-filter-options&tab=filters';
?>
<style>
    #wpcontent{
        padding:0 !important;
    }
</style>

<div id="wrapper">

    <div class="hid-header">
        <img src="<?php echo HIDSF_ASSET_URL.'/images/humanId.png'?>" alt="">
        <div class="hid-header-menu">
            <ul>
                <li class="active">
                    <a href="" class="active">Home</a>
                </li>
                <li>
                    <a href="">Users</a>
                </li>
            </ul>
        </div>
    </div>
    <div class="pl-20">
        <h1>Human ID Account Configuration </h1>
        <strong>You need to create a HumanID account. If you don't have one, you can create it <a href="" target="_blank">here</a></strong>
	    <?php settings_errors(); ?>
        <form method="post" action="options.php">
		    <?php

		    settings_fields( 'hid-spam-filter' );
		    do_settings_sections( 'hid-spam-filter' );

		    submit_button();
		    ?>
        </form>
    </div>

</div>


<!-- END wrapper -->