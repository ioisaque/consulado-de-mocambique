<?php
/**
 * The template for displaying comments.
 * 
 * The area of the page that contains both current comments
 * and the comment form.
 * 
 * @author Swlabs
 * @package Exploore
 * @since 1.0
 */

/*
 * If the current post is protected by a password and
 * the visitor has not yet entered the password we will
 * return early without loading the comments.
 */
if ( post_password_required() ) {
	return;
}

$queried_object = get_queried_object();
$post_type = $queried_object->post_type;
$single_comment_title = esc_html__( 'Comment', 'exploore' );
$comment_title = esc_html__( 'Comments', 'exploore' );
$reply_title = esc_html__( 'Leave your comment', 'exploore' );
$rating = '';
$is_review = false;
if($post_type == 'slzexploore_hotel' || $post_type == 'slzexploore_tour' || $post_type == 'slzexploore_car' || $post_type == 'slzexploore_cruise'){
	$rating = sprintf(
		'<div class="col-md-12">
			<div class="form-group comment-form-rating">
				<p class="stars-rating"><span>
					<a href="#" class="star-1">1</a>
					<a href="#" class="star-2">2</a>
					<a href="#" class="star-3">3</a>
					<a href="#" class="star-4">4</a>
					<a href="#" class="star-5">5</a>
				</span></p>
				<input type="hidden" name="rating" value=""/>
			</div>
		</div>'
	);
	$single_comment_title = esc_html__( 'Review', 'exploore' );
	$comment_title = esc_html__( 'Reviews', 'exploore' );
	$reply_title = esc_html__( 'Add your review', 'exploore' );
	$is_review = true;
}
?>

<div class="blog-comment">
	<?php if ( have_comments() ) : ?>
		<div class="comment-count blog-comment-title sideline">
		<?php
			printf( _nx( '%1$s %2$s', '%1$s %3$s', get_comments_number(), 'comments title', 'exploore' ),
					number_format_i18n( get_comments_number() ),
					esc_attr( $single_comment_title ),
					esc_attr( $comment_title )
				);
		?>
		</div>
		<ul class="comment-list list-unstyled">
		<?php
			$commemts_arg = array(
				'max_depth'   => '2',
				'type'        => 'comment',
				'per_page'    => get_option( 'page_comments' ) ? get_option( 'comments_per_page' ) : '',
				'callback'    => 'slzexploore_display_comments'
			);
			wp_list_comments( $commemts_arg );
		?>
		</ul>

		<?php 
		if ( get_comment_pages_count() > 1 && get_option( 'page_comments' ) ) : // Are there comments to navigate through? ?>
		<div class="paginate-com">
			<?php
				//Create pagination links for the comments on the current post, with single arrow heads for previous/next
				$defaults = array(
					'add_fragment' => '#comments',
					'prev_text' => esc_html__( 'Previous', 'exploore' ), 
					'next_text' => esc_html__( 'Next', 'exploore' ),
				);
				paginate_comments_links( $defaults );
			?>
		</div>
		<?php endif; // Check for comment navigation. ?>

	<?php endif; // Check for have_comments(). ?>

	<?php
	// If comments are closed and there are comments, let's leave a little note, shall we?
	if ( ! comments_open() && '0' != get_comments_number() && post_type_supports( get_post_type(), 'comments' ) ) :
	?>
	<p class="no-comments"><?php esc_html_e( 'Comments are closed', 'exploore' ); ?>.</p>
	<?php endif; ?>
	
	<?php
	// Check login review
	if( ! $is_review || ( $is_review && is_user_logged_in() ) ):
		//Comment Form
		$commenter = wp_get_current_commenter();
		$req       = get_option( 'require_name_email' );
		$aria_req  = ( $req ? " aria-required='true'" : '' );
		$html_req  = ( $req ? " required='required'" : '' );
		$format    = 'xhtml';//The comment form format. Default 'xhtml'. Accepts 'xhtml', 'html5'.
		$html5     = 'html5' === $format;
		$author_field = sprintf(
			'<input class="form-control form-input required" placeholder="%1$s" id="author" name="author" type="text" value="%2$s" %3$s />
			<div id="author-err-required" class="input-error-msg hide">%4$s</div>',
			esc_html__( 'Your Name', 'exploore' ),//placeholder
			esc_attr( $commenter['comment_author'] ),//value
			$aria_req . $html_req, 
			esc_html__( 'Please enter your name.', 'exploore' )//error message
	
		);
		$email_field = sprintf(
			'<input class="form-control form-input required" placeholder="%1$s" id="email" name="email" %6$s value="%2$s" size="30" %3$s />
			<div class="input-error-msg hide" id="email-err-required">%4$s</div>
			<div class="input-error-msg hide" id="email-err-valid">%5$s</div>',
			esc_html__( 'Your Email', 'exploore' ),//placeholder
			esc_attr( $commenter['comment_author_email'] ),//value
			$aria_req . $html_req, 
			esc_html__( 'Please enter your email address.', 'exploore' ),//error message
			esc_html__( 'Please enter a valid email address.', 'exploore' ),//error message
			( $html5 ? 'type="email"' : 'type="text"' )
	
		);
		
		$comment_field = sprintf(
			'<textarea id="comment" name="comment" required="required" class="form-control form-input" placeholder="%s"></textarea>
			<div class="input-error-msg hide" id="comment-err-required">%s</div>',
			esc_html__( 'Your Message', 'exploore' ),//placeholder
			esc_html__( 'Please enter comment.', 'exploore' )//error message
		);
		$comment_field .= $rating;
		
		$comments_args = array(
			'cancel_reply_link'   => esc_html__( 'Cancel', 'exploore' ),
			'comment_notes_before'=> '',
			'format'              => $format,
			'fields'              => array( 'author' => $author_field, 'email' => $email_field),
			'logged_in_as'        => '',
			'comment_field'       => $comment_field,
			'class_submit'        => 'btn btn-slide',
			'label_submit'        => esc_html__( 'Send Message', 'exploore' ),
			'title_reply'         => $reply_title,
			'submit_button'        => '<div class="contact-submit"></div><button name="%1$s" id="%2$s" type="submit" data-hover="'.esc_html__( 'SEND NOW', 'exploore' ).'" class="%3$s"><span class="text">%4$s</span><span class="icons fa fa-long-arrow-right">  </span></button>',
			'submit_field'        => '%1$s%2$s',
		);
		ob_start();
		comment_form($comments_args);
		echo str_replace('class="comment-reply-title"', 'class="blog-comment-title underline sideline"', ob_get_clean());
	endif;// end check login
	?>
</div><!-- /comment-form -->