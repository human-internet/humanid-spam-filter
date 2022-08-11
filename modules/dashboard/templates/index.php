<?php
namespace hid_spam_filter;
$link_to_filters = admin_url( 'admin.php' ) . '?page=kmcf7-message-filter-options&tab=filters';
?>

<style>
    .card {
        max-width: 100%;
    }
</style>
<div id="wrapper">

    <!-- ============================================================== -->
    <!-- Start Page Content here -->
    <!-- ============================================================== -->

    <div class="content-page" style="margin-top:0; margin-left:0;">
        <div class="content">
                <div class="alert alert-danger alert-dismissible">
                    <p>Welcome</p>
                </div>
            <div class="container-fluid">
                <div class="row page-title align-items-center">
                    <div class="col-sm-4 col-xl-6">
                        <h4 class="mb-1 mt-0">Human ID Spam Filter
                            v.<?php echo HidSpamFilter::getVersion() ?></h4>
                    </div>

                </div>
            </div>
        </div> <!-- content -->

    </div>



</div>


<!-- END wrapper -->