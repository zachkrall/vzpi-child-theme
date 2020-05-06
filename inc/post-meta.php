<?php

add_filter('wp_kses_allowed_html', function ($tags) {
	$tags['noscript'] = true;
	return $tags;
});

if ( ! function_exists( 'blocksy_post_meta' ) ) {
	/**
	 * array(
	 *   'author' => 'yes' | 'no' | true | false,
	 *   'comments' => 'yes' | 'no' | true | false,
	 *   'post_date' => 'yes' | 'no' | true | false,
	 *   'categories' => 'yes' | 'no' | true | false,
	 * )
	 */
	function blocksy_post_meta( $post_meta_descriptor = [], $args = [] ) {
		$post_meta_descriptor = array_merge(
			[
				'author' => false,
				'comments' => false,
				'post_date' => false,
				'categories' => false,
				'author_avatar' => false,
				'tags' => false,
			],
			$post_meta_descriptor
		);

		$args = wp_parse_args(
			$args,
			[
				'class' => '',
				'avatar_size' => '35px',
				'labels' => true,
				'category_style' => 'simple',
				'plain' => false,
				'meta_type' => 'simple',
				'force_icons' => false,
				'date_format' => 'M j, Y'
			]
		);

		if ( ! empty( $args['class'] ) ) {
			$args['class'] = ' ' . $args['class'];
		}

		if ($args['meta_type'] === 'icons') {
			$args['labels'] = false;
		}

		if ($args['meta_type'] === 'icons') {
			$args['category_style'] = 'simple';
		}

		global $post;

		if ( get_post_type( $post ) === 'page' ) {
			$post_meta_descriptor['comments'] = false;
			$post_meta_descriptor['categories'] = false;
			$post_meta_descriptor['tags'] = false;
		}

		if ( ! in_array( true, array_values( $post_meta_descriptor ), true ) ) {
			return '';
		}

		// Author ID
		global $post;
		$user_id = $post->post_author;

		$avatar = '';

		if ( $post_meta_descriptor['author_avatar'] ) {
			$avatar = ' has-avatar';
		}

		ob_start();

		?>

			<?php if ( $post_meta_descriptor['author'] && get_the_author() ) { ?>
				<li class="ct-meta-author" <?php blocksy_schema_org_definitions_e('author_name') ?>>

					<?php if ($args['meta_type'] === 'icons' || $args['force_icons']) { ?>
						<span class="ct-meta-icon">
							<svg width="13" height="13" viewBox="0 0 15 15">
								<path d="M13.6,1.4c-1.9-1.9-4.9-1.9-6.8,0L2.2,6C2.1,6.1,2,6.3,2,6.5V12l-1.8,1.8c-0.3,0.3-0.3,0.7,0,1C0.3,14.9,0.5,15,0.7,15s0.3-0.1,0.5-0.2L3,13h5.5c0.2,0,0.4-0.1,0.5-0.2l2.7-2.7c0,0,0,0,0,0l1.9-1.9C15.5,6.3,15.5,3.3,13.6,1.4z M8.2,11.6H4.4l1.4-1.4h3.9L8.2,11.6z M12.6,7.2L11,8.9H7.1l3.6-3.6c0.3-0.3,0.3-0.7,0-1C10.4,4,10,4,9.7,4.3L5,9.1c0,0,0,0,0,0l-1.6,1.6V6.8l4.4-4.4c1.3-1.3,3.5-1.3,4.8,0C14,3.7,14,5.9,12.6,7.2C12.6,7.2,12.6,7.2,12.6,7.2z"/>
							</svg>
						</span>
					<?php } ?>

					<?php if ($args['labels']) { ?>
						<span class="ct-meta-label">
							<?php echo esc_html(__( 'By', 'blocksy' )); ?>
						</span>
					<?php } ?>

					<span class="ct-meta-element" <?php blocksy_schema_org_definitions_e('author_link') ?>>
						<?php //echo wp_kses_post(get_the_author_posts_link()); ?>
					</span>
				</li>
			<?php } ?>

			<?php if ( $post_meta_descriptor['post_date'] ) { ?>
				<li
					class="ct-meta-date"
					<?php blocksy_schema_org_definitions_e('publish_date') ?>>

					<?php if ($args['meta_type'] === 'icons' || $args['force_icons']) { ?>
						<span class="ct-meta-icon">
							<svg width="13" height="13" viewBox="0 0 15 15">
								<path d="M7.5,15C3.4,15,0,11.6,0,7.5S3.4,0,7.5,0S15,3.4,15,7.5S11.6,15,7.5,15z M7.5,1.4c-3.4,0-6.1,2.8-6.1,6.1c0,3.4,2.8,6.1,6.1,6.1c3.4,0,6.1-2.8,6.1-6.1C13.6,4.1,10.9,1.4,7.5,1.4z M10.8,9.2c0.2-0.3,0-0.7-0.3-0.9L8.2,7.1V3.4c0-0.4-0.3-0.7-0.7-0.7C7.1,2.7,6.8,3,6.8,3.4v4.1C6.8,7.8,7,8,7.2,8.1l2.7,1.4c0.1,0,0.2,0.1,0.3,0.1C10.5,9.5,10.7,9.4,10.8,9.2z"/>
							</svg>
						</span>
					<?php } ?>

					<?php if ($args['labels']) { ?>
						<span class="ct-meta-label">
							<?php echo esc_html(__( 'On', 'blocksy' )); ?>
						</span>
					<?php } ?>

					<span
						class="ct-meta-element"
						<?php echo ($args['force_icons'] ? 'data-date="' . get_the_date('c') . '"' : '') ?>>
						<?php echo esc_html(get_the_date( $args['date_format'] )); ?>
					</span>
				</li>
			<?php } ?>

			<?php if ( $post_meta_descriptor['comments'] && get_comments_number() > 0 ) { ?>
				<li class="ct-meta-comments">
					<?php if ($args['meta_type'] === 'icons' || $args['force_icons']) { ?>
						<span class="ct-meta-icon">
							<svg width="13" height="13" viewBox="0 0 15 15">
								<path d="M13.7,14.8L10.9,12H2.2C1,12,0,11,0,9.8l0-7.5C0,1,1,0,2.2,0l10.5,0C14,0,15,1,15,2.2v12c0,0.3-0.2,0.6-0.5,0.7c-0.1,0-0.2,0.1-0.3,0.1C14.1,15,13.9,14.9,13.7,14.8zM2.2,1.5c-0.4,0-0.8,0.3-0.8,0.8v7.5c0,0.4,0.3,0.8,0.8,0.8h9c0.2,0,0.4,0.1,0.5,0.2l1.7,1.7V2.2c0-0.4-0.3-0.8-0.8-0.8H2.2z"/>
							</svg>
						</span>
					<?php } ?>

					<a class="ct-meta-element" href="<?php echo esc_attr(get_permalink()); ?>#ct-comments">
						<?php


							$singular_text = ' Comment';
							$plural_text = ' Comments';

							if ( get_post_type() === 'product' ) {
								$singular_text = ' Review';
								$plural_text = ' Reviews';
							}

							if ($args['meta_type'] === 'icons' && !$args['force_icons']) {
								$singular_text = '';
								$plural_text = '';
							}

							echo esc_html(get_comments_number_text(
								'',
								'1' . $singular_text,
								'%' . $plural_text
							));

						?>
					</a>
				</li>
			<?php } ?>

			<?php
			// if ( $post_meta_descriptor['categories'] && blocksy_get_categories_list() ) {
			// 	if (!$args['plain']) {
			// 		echo '<li class="ct-meta-categories" data-type="' . esc_attr($args['category_style']) . '">';
			//
			// 		if ($args['meta_type'] === 'icons' || $args['force_icons']) {
			// 			echo '<span class="ct-meta-icon">';
			// 			echo '<svg width="13" height="13" viewBox="0 0 15 15"><path d="M13,14.3H2c-1.1,0-2-0.9-2-2V2.7c0-1.1,0.9-2,2-2h3.4C5.7,0.7,5.9,0.8,6,1l1.2,1.7H13c1.1,0,2,0.9,2,2v7.5C15,13.4,14.1,14.3,13,14.3z M2,2C1.7,2,1.4,2.4,1.4,2.7v9.5C1.4,12.6,1.7,13,2,13H13c0.4,0,0.7-0.3,0.7-0.7V4.8c0-0.4-0.3-0.7-0.7-0.7H6.8C6.6,4.1,6.4,4,6.3,3.8L5.1,2H2z"/></svg>';
			// 			echo '</span>';
			// 		}
			//
			// 		if ($args['labels']) {
			// 			echo '<span class="ct-meta-label">';
			// 			echo esc_html(__( 'In ', 'blocksy' ));
			// 			echo '</span>';
			// 		}
			//
			// 		echo '<span class="ct-meta-element">';
			// 		echo wp_kses_post(blocksy_get_categories_list( ' </span><span class="ct-meta-element">' ));
			// 		echo '</span>';
			// 		echo '</li>';
			// 	} else {
			// 		echo '<li>';
			// 		echo wp_kses_post(blocksy_get_categories_list( '</li><li>' ));
			// 		echo '</li>';
			// 	}
			//
			// }
			//
			// if ( $post_meta_descriptor['tags'] && blocksy_get_categories_list( '', false ) ) {
			// 	echo '<li>';
			// 	echo wp_kses_post(blocksy_get_categories_list( '</li><li>', false ));
			// 	echo '</li>';
			// }

			?>
		<?php

		$to_return = ob_get_contents();
		ob_end_clean();

		if ( empty( trim( $to_return ) ) ) {
			return '';
		}

		$label_output = '';

		if ($args['labels'] && !$args['plain']) {
			$label_output = 'data-label';
		}

		$class_output = '';

		if ($args['plain']) {
			$class_output = 'class="product-categories"';
		}

		ob_start();

		?>

		<?php if (!$args['plain']) { ?>
		<div class="entry-meta<?php echo esc_attr($avatar); ?><?php echo esc_attr($args['class']); ?>" <?php echo wp_kses_post($label_output) ?> data-type="<?php echo esc_attr($args['meta_type']) ?>">
		<?php } ?>

			<?php if ( $post_meta_descriptor['author'] && get_the_author() ) { ?>
				<?php if ( $post_meta_descriptor['author_avatar'] ) {
					echo blocksy_simple_image(
						get_avatar_url(
							get_the_author_meta('ID'),
							[
								'size' => intval($args['avatar_size']) * 2
							]
						),
						[
							'tag_name' => 'a',

							'html_atts' => [
								'class' => 'avatar-container',
								'href' => get_author_posts_url(get_the_author_meta('ID')),
							],
							'img_atts' => [
								'width' => intval($args['avatar_size']),
								'height' => intval($args['avatar_size'])
							],
						]
					);
				} ?>
			<?php } ?>

			<ul <?php echo wp_kses_post($class_output) ?>>
				<?php
					/**
					 * Note to code reviewers: This line doesn't need to be escaped.
					 * Var $to_return used here has the value escaped properly.
					 */
					echo $to_return;
				?>
			</ul>
		<?php if (!$args['plain']) { ?>
		</div>
		<?php } ?>

		<?php

		return ob_get_clean();
	}
}

function blocksy_get_categories_list( $between = '', $is_category = true ) {
	global $post;

	$category = $is_category ? 'category' : 'post_tag';

	if ( get_post_type( $post ) === 'fw-portfolio' ) {
		$category = 'fw-portfolio-category';
	}

	if ( get_post_type( $post ) === 'product' ) {
		$category = $is_category ? 'product_cat' : 'product_tag';
	}

	return get_the_term_list( $post, $category, '', $between );
}
