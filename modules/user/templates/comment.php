<?php

namespace humanid_spam_filter;
global $comment_id;

$human_id   = get_comment_meta( $comment_id, 'human_id', true );
$user       = User::where( 'human_id', '=', $human_id )->get();
$is_blocked = false;
if ( sizeof( $user ) > 0 ) {
	$is_blocked = $user[0]->blocked;
}
?>
<?php if ( $human_id ): ?>
    <!--	--><?php //echo $human_id ?><!-- <br>-->
    <input type="hidden" name="human_id" class="human_id" value="<?php echo $human_id ?>">
    <button class="button <?php echo $is_blocked ? '' : 'hidden' ?>" id="<?php echo $human_id ?>-block">
		<?php _e( "Unblock User", HIDSF_TEXT_DOMAIN ) ?>
    </button>
    <button class="button border-danger text-danger <?php echo $is_blocked ? 'hidden' : '' ?>"
            id="<?php echo $human_id ?>-unblock">
		<?php _e( "Block User", HIDSF_TEXT_DOMAIN ) ?>
    </button>
<?php endif; ?>

<script>
    jQuery(function ($) {
        $(document).ready(function () {
            $("#<?php echo $human_id?>-block").click(async function (e) {
                e.preventDefault()
                try {
                    await updateUser('<?php echo $human_id?>', false)
                    $(this).hide();
                    $("#<?php echo $human_id?>-unblock").show()
                } catch (e) {
                    alert('<?php _e( "An error occurred. Please try again", HIDSF_TEXT_DOMAIN )?>');
                }
            })

            $("#<?php echo $human_id?>-unblock").click(async function (e) {
                e.preventDefault()
                try {
                    await updateUser('<?php echo $human_id?>', true)
                    $(this).hide();
                    $("#<?php echo $human_id?>-block").show()
                } catch (e) {
                    alert('<?php _e( "An error occurred. Please try again", HIDSF_TEXT_DOMAIN )?>');
                }
            })
        })
    })
</script>