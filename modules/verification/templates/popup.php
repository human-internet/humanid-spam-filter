<?php
namespace humanid_spam_filter;

$client_secret     = get_option( 'hidsf_client_secret', '' );
$client_id         = get_option( 'hidsf_client_id', '' );
$link_to_dashboard = admin_url( 'admin.php' ) . '?page=humanid-spam-filter';

?>

<div class="hid-modal hid-modal-large" id="human-id-verification-modal" style="display: none">
    <div class="hid-modal-content text-center text-main-light d-flex justify-content-center align-items-center flex-column">
        <div>
            <h4 class="text-main"><?php _e( "Hello, <br>Thanks for writing a <br/>comment", HIDSF_TEXT_DOMAIN ) ?></h4>
			<?php _e( "Before your comment is posted, <br/> please take a few seconds to anonymously <br/>verify your humanity :", HIDSF_TEXT_DOMAIN ) ?>
        </div>
        <div class="margin-bottom-20 margin-top-20 text-center">
            <div>
				<?php if ( trim( $client_secret ) == '' && trim( $client_id ) == '' ): ?>
                    <a class="hid-alert alert-danger text-danger" href="<?php echo $link_to_dashboard ?>">
						<?php _e( "Please set your client id and client secret", HIDSF_TEXT_DOMAIN ) ?>
                    </a>
				<?php else: ?>
                    <img src="<?php echo HIDSF_ASSET_URL . '/images/anonymous_login.png' ?>" alt="" class="pointer"
                         id="start-human-id-verification"/>
				<?php endif; ?>
            </div>
            <div class="hid-alert alert-info margin-top-10" id="hid-verification-pending" style="display:none">
				<?php _e( "Verifying your humanity..", HIDSF_TEXT_DOMAIN ) ?>
            </div>
            <div class="hid-alert alert-danger text-danger margin-top-10" id="hid-verification-error-message"
                 style="display: none">
				<?php _e( "An error occurred. Please try again", HIDSF_TEXT_DOMAIN ) ?>
            </div>
        </div>
		<?php _e( "P.S Your phone number will not be stored", HIDSF_TEXT_DOMAIN ) ?>
        <div class="margin-bottom-20 margin-top-20">
            <span class="text-underline text-danger pointer"
                  id="close-human-id-verification"><?php _e( "Cancel", HIDSF_TEXT_DOMAIN ) ?></span>
        </div>
    </div>
</div>