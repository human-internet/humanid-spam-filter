<?php
namespace humanid_spam_filter;
if ( ! defined( 'ABSPATH' ) ) exit;

global $hidsf_comment_id;

$hidsf_human_id   = get_comment_meta( $hidsf_comment_id, 'human_id', true );
$hidsf_user       = User::where( 'human_id', '=', $hidsf_human_id )->get();
$hidsf_is_blocked = false;
if ( sizeof( $hidsf_user ) > 0 ) {
	$hidsf_is_blocked = $hidsf_user[0]->blocked;
}
?>
<?php if ( $hidsf_human_id ): ?>
    <!--	--><?php //echo $human_id ?><!-- <br>-->
    <!--    <input type="hidden" name="human_id" class="human_id" value="--><?php //echo $human_id ?><!--">-->
    <button class="button <?php echo esc_html( $hidsf_human_id ) ?>-block <?php echo $hidsf_is_blocked ? '' : 'hidden' ?>">
		<?php esc_html_e( "Allow user to comment", 'humanid-spam-filter' ) ?>
    </button>
    <button class="button border-danger text-danger <?php echo esc_html( $hidsf_human_id ) ?>-unblock <?php echo $hidsf_is_blocked ? 'hidden' : '' ?>">
		<?php esc_html_e( "Block user from commenting", 'humanid-spam-filter' ) ?>
    </button>
<?php endif; ?>

<script>
    jQuery(function ($) {
        $(document).ready(function () {
            $(".<?php echo esc_html( $hidsf_human_id )?>-block").click(async function (e) {
                e.preventDefault()
                try {
                    $(this).html('<?php esc_html_e( "please wait...", 'humanid-spam-filter' ) ?>')
                    await updateUser('<?php echo esc_html( $hidsf_human_id )?>', false)
                    $(".<?php echo esc_html( $hidsf_human_id )?>-unblock").show()
                    $(".<?php echo esc_html( $hidsf_human_id )?>-block").hide()
                } catch (e) {
                    alert('<?php esc_html_e( "An error occurred. Please try again", 'humanid-spam-filter' )?>');
                } finally {
                    $(".<?php echo esc_html( $hidsf_human_id )?>-unblock").html('<?php esc_html_e( "Block user from commenting", 'humanid-spam-filter' ) ?>')
                }
            })

            $(".<?php echo esc_html($hidsf_human_id)?>-unblock").click(async function (e) {
                e.preventDefault()
                try {
                    $(this).html('<?php esc_html_e( "please wait...", 'humanid-spam-filter' ) ?>')
                    await updateUser('<?php echo esc_html( $hidsf_human_id )?>', true)
                    $(".<?php echo esc_html( $hidsf_human_id )?>-unblock").hide()
                    $(".<?php echo esc_html( $hidsf_human_id )?>-block").show()
                } catch (e) {
                    alert('<?php esc_html_e( "An error occurred. Please try again", 'humanid-spam-filter' )?>');
                } finally {
                    $(".<?php echo esc_html( $hidsf_human_id )?>-block").html('<?php esc_html_e( "Allow user to comment", 'humanid-spam-filter' ) ?>')
                }
            })
        })
    })
</script>