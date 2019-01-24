<?php
class Slzexploore_Core_Pagination {
	private $theme_post_pagination_link = 'slzexploore_post_pagination_link';

	public static function paging_nav( $pages = '', $range = 2, $current_query = '' ) {
		global $paged;
		if( $current_query == '' ) {
			if( empty( $paged ) ) $paged = 1;
		} else {
			$paged = $current_query->query_vars['paged'];
		}
		$prev = $paged - 1;
		$next = $paged + 1;
		$range = 1; // only edit this if you want to show more page-links
		$showitems = ($range * 2);
		
		if( $pages == '' ) {
			global $wp_query;
			$pages = $wp_query->max_num_pages;
			if( ! $pages ) {
				$pages = 1;
			}
		}
		$method = "get_pagenum_link";
		if(is_single()) {
			$method = self::theme_post_pagination_link;
		}
		$output = $output_page = $showpages = $disable = '';
		$page_format = '<li><a href="%2$s" class="btn-pagination" >%1$s</a></li>';
		if( 1 != $pages ) {
			$output_page .= '<ul class="pagination">';
			// prev
			if( $paged == 1 ) {
				$disable = ' disable';
			}
			$output_page .= '<li class="'.$disable.'"><a href="'.$method($prev).'" rel="prev" class="btn-pagination previous"><span aria-hidden="true" class="fa fa-angle-left"></span></a></li>';
			// first pages
			if( $paged > $showitems ) {
				$output_page .= sprintf( $page_format, 1, $method(1) );
			}
			// show ...
			if( $paged - $range > $showitems && $paged - $range > 2 ) {
				$output_page .= sprintf( $page_format, '&bull;&bull;&bull;', $method($paged - $range - 1) );'<li><a href="'.$method($prev).'">&bull;&bull;&bull;</a></li>';
			}
			for ($i=1; $i <= $pages; $i++) {
				if (1 != $pages &&( !($i >= $paged+$showitems || $i <= $paged-$showitems) || $pages <= $showitems )) {
					if( $paged == $i ) {
						$output_page .= '<li><span class="btn-pagination active">'.$i.'</span></li>';
					} else {
						$output_page .= sprintf( $page_format, $i, $method($i) );
					}
					$showpages = $i;
				}
			}
			// show ...
			if( $paged < $pages-1 && $showpages < $pages -1 ){
				$showpages = $showpages + 1;
				$output_page .= sprintf( $page_format, '&bull;&bull;&bull;', $method($showpages) );
			}
			// end pages
			if( $paged < $pages && $showpages < $pages ) {
				$output_page .= sprintf( $page_format, $pages, $method($pages) );
			}
			//next
			$disable = '';
			if( $paged == $pages ) {
				$disable = ' disable';
			}
			$output_page .= '<li class="'.$disable.'"><a href="'.$method($next).'" rel="next" class="btn-pagination next"><span aria-hidden="true" class="fa fa-angle-right"></span></a></li>';
			$output_page .= '</ul>'."\n";
			$output = sprintf('<nav class="pagination-list margin-top70">%1$s</nav>', $output_page );
		}
		return $output;
	}
	
	public static function paging_ajax( $pages = '', $range = 2, $current_query = '', $base = '' ) {
		global $paged;
		if( $current_query == '' ) {
			if( empty( $paged ) ) $paged = 1;
		} else {
			$paged = $current_query->query_vars['paged'];
		}
		$prev = $paged - 1;
		$next = $paged + 1;
		$range = 1; // only edit this if you want to show more page-links
		$showitems = ($range * 2);
		
		if( $pages == '' ) {
			global $wp_query;
			$pages = $wp_query->max_num_pages;
			if( ! $pages ) {
				$pages = 1;
			}
		}
		if( empty( $base ) ){
			global $wp;
			$base = $wp->request;
		}
		$method = "slzexploore_core_get_pagenum_link";
		if(is_single()) {
			$method = self::theme_post_pagination_link;
		}
		$output = $output_page = $showpages = $disable = '';
		$page_format = '<li><a href="%2$s" class="btn-pagination" data-page="%3$s" >%1$s</a></li>';
		if( 1 != $pages ) {
			$output_page .= '<ul class="pagination">';
			// prev
			if( $paged == 1 ) {
				$disable = ' disable';
			}
			$output_page .= '<li class="'.$disable.'"><a href="'.$method($prev, $base).'" rel="prev" class="btn-pagination previous" data-page="'.$prev.'"><span aria-hidden="true" class="fa fa-angle-left"></span></a></li>';
			// first pages
			if( $paged > $showitems ) {
				$output_page .= sprintf( $page_format, 1, $method(1, $base), 1 );
			}
			// show ...
			if( $paged - $range > $showitems && $paged - $range > 2 ) {
				$output_page .= sprintf( $page_format, '&bull;&bull;&bull;', $method($paged - $range - 1, $base), $paged - $range - 1 );'<li><a href="'.$method($prev, $base).'">&bull;&bull;&bull;</a></li>';
			}
			for ($i=1; $i <= $pages; $i++) {
				if (1 != $pages &&( !($i >= $paged+$showitems || $i <= $paged-$showitems) || $pages <= $showitems )) {
					if( $paged == $i ) {
						$output_page .= '<li><span class="btn-pagination active">'.$i.'</span></li>';
					} else {
						$output_page .= sprintf( $page_format, $i, $method($i, $base), $i );
					}
					$showpages = $i;
				}
			}
			// show ...
			if( $paged < $pages-1 && $showpages < $pages -1 ){
				$showpages = $showpages + 1;
				$output_page .= sprintf( $page_format, '&bull;&bull;&bull;', $method($showpages, $base), $showpages );
			}
			// end pages
			if( $paged < $pages && $showpages < $pages ) {
				$output_page .= sprintf( $page_format, $pages, $method($pages, $base), $pages );
			}
			//next
			$disable = '';
			if( $paged == $pages ) {
				$disable = ' disable';
			}
			$output_page .= '<li class="'.$disable.'"><a href="'.$method($next, $base).'" rel="next" class="btn-pagination next" data-page="'.$next.'"><span aria-hidden="true" class="fa fa-angle-right"></span></a></li>';
			$output_page .= '</ul>'."\n";
			$output = sprintf('<nav class="paging-ajax pagination-list margin-top70">%1$s</nav>', $output_page );
		}
		$output .= sprintf('<div class="hide slz-pagination-base" data-base="%s"></div>', esc_attr($base) );
		return $output;
	}
}