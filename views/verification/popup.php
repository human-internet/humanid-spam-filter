<?php
namespace humanid_spam_filter;

$client_secret     = get_option( 'hidsf_client_secret', '' );
$client_id         = get_option( 'hidsf_client_id', '' );
$link_to_dashboard = admin_url( 'admin.php' ) . '?page=humanid-spam-filter';
$structure         = get_option( 'permalink_structure' );

?>

<div class="hid-modal hid-modal-large" id="human-id-verification-modal" style="display: none">
    <div class="hid-modal-content text-center text-main-light d-flex justify-content-center align-items-center flex-column text-main">
        <div class="fs-24 lh-28">
            <h4 class="text-main fs-36 margin-top-0 margin-bottom-0"><?php esc_html_e( "Thanks for your message", 'humanid-spam-filter' ) ?></h4>
            <div class="margin-top-20">
                <?php _e( "To finish submitting your message,", 'humanid-spam-filter' ) ?>
                <br>
                <?php _e( "please take a few seconds to anonymously", 'humanid-spam-filter' ) ?>
                <br>
                <?php _e( "verify that you're not a bot.", 'humanid-spam-filter' ) ?>
            </div>
        </div>
        <div class="margin-bottom-20 margin-top-20 text-center">
            <div>
                <?php if ( trim( $client_secret ) == '' || trim( $client_id ) == '' ): ?>
                    <a class="hid-alert alert-danger text-danger" href="<?php echo esc_html( $link_to_dashboard ) ?>">
                        <?php esc_html_e( "Please set your client id and client secret", 'humanid-spam-filter' ) ?>
                    </a>
                <?php elseif ( trim( $structure ) == '' ): ?>
                    <a class="hid-alert alert-danger text-danger" href="<?php echo esc_html( $link_to_dashboard ) ?>">
                        <?php esc_html_e( "Please complete the plugin setup", 'humanid-spam-filter' ) ?>
                    </a>
                <?php else: ?>
                    <img src="<?php echo HIDSF_ASSET_URL . '/images/anonymous_login.png' ?>" alt=""
                         class="pointer margin-top-40"
                         id="start-human-id-verification"/>
                <?php endif; ?>
            </div>
            <div class="hid-alert alert-info margin-top-10" id="hid-verification-pending"
                 style="display:none; width: 100%">
                <?php esc_html_e( "Verifying your humanity..", 'humanid-spam-filter' ) ?>
            </div>
            <div class="hid-alert alert-danger text-danger margin-top-10 " id="hid-verification-error-message"
                 style="display: none; width: 100%">
                <?php esc_html_e( "An error occurred. Please try again", 'humanid-spam-filter' ) ?>
            </div>
        </div>
        <div class="margin-top-10 fs-18">
            <div class="fs-18">
                <?php _e( "Learn more about the", 'humanid-spam-filter' ) ?>
                <a href='https://human-internet.org' target='_blank' class='text-main text-underline'>Foundation for a
                    Human Internet</a>'s humanID
                <br>
                <?php _e( "and the mission to restore privacy online", 'humanid-spam-filter' ) ?>
            </div>
            <div class="text-underline text-main pointer margin-top-20 fs-18"
                 id="close-human-id-verification"><?php esc_html_e( "Cancel", 'humanid-spam-filter' ) ?></div>
        </div>
    </div>
</div>
