<?php
add_action( 'after_setup_theme', 'blankslate_setup' );
function blankslate_setup() {
load_theme_textdomain( 'blankslate', get_template_directory() . '/languages' );
add_theme_support( 'title-tag' );
add_theme_support( 'post-thumbnails' );
add_theme_support( 'responsive-embeds' );
add_theme_support( 'automatic-feed-links' );
add_theme_support( 'html5', array( 'search-form', 'navigation-widgets' ) );
add_theme_support( 'appearance-tools' );
add_theme_support( 'woocommerce' );
global $content_width;
if ( !isset( $content_width ) ) { $content_width = 1920; }
register_nav_menus( array( 'main-menu' => esc_html__( 'Main Menu', 'blankslate' ) ) );
}
add_action( 'admin_notices', 'blankslate_notice' );
function blankslate_notice() {
$user_id = get_current_user_id();
$admin_url = ( isset( $_SERVER['HTTPS'] ) && $_SERVER['HTTPS'] === 'on' ? 'https' : 'http' ) . "://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]";
$param = ( count( $_GET ) ) ? '&' : '?';
if ( !get_user_meta( $user_id, 'blankslate_notice_dismissed_11' ) && current_user_can( 'manage_options' ) )
echo '<div class="notice notice-info"><p><a href="' . esc_url( $admin_url ), esc_html( $param ) . 'dismiss" class="alignright" style="text-decoration:none"><big>' . esc_html__( 'Ⓧ', 'blankslate' ) . '</big></a>' . wp_kses_post( __( '<big><strong>🏆 Thank you for using BlankSlate!</strong></big>', 'blankslate' ) ) . '<p>' . esc_html__( 'Powering over 10k websites! Buy me a sandwich! 🥪', 'blankslate' ) . '</p><a href="https://github.com/bhadaway/blankslate/issues/57" class="button-primary" target="_blank"><strong>' . esc_html__( 'How do you use BlankSlate?', 'blankslate' ) . '</strong></a> <a href="https://opencollective.com/blankslate" class="button-primary" style="background-color:green;border-color:green" target="_blank"><strong>' . esc_html__( 'Donate', 'blankslate' ) . '</strong></a> <a href="https://wordpress.org/support/theme/blankslate/reviews/#new-post" class="button-primary" style="background-color:purple;border-color:purple" target="_blank"><strong>' . esc_html__( 'Review', 'blankslate' ) . '</strong></a> <a href="https://github.com/bhadaway/blankslate/issues" class="button-primary" style="background-color:orange;border-color:orange" target="_blank"><strong>' . esc_html__( 'Support', 'blankslate' ) . '</strong></a></p></div>';
}
add_action( 'admin_init', 'blankslate_notice_dismissed' );
function blankslate_notice_dismissed() {
$user_id = get_current_user_id();
if ( isset( $_GET['dismiss'] ) )
add_user_meta( $user_id, 'blankslate_notice_dismissed_11', 'true', true );
}
add_action( 'wp_enqueue_scripts', 'blankslate_enqueue' );
function blankslate_enqueue() {
wp_enqueue_style( 'blankslate-style', get_stylesheet_uri() );
wp_enqueue_script( 'jquery' );
}
add_action( 'wp_footer', 'blankslate_footer' );
function blankslate_footer() {
?>
<script>
jQuery(document).ready(function($) {
var deviceAgent = navigator.userAgent.toLowerCase();
if (deviceAgent.match(/(iphone|ipod|ipad)/)) {
$("html").addClass("ios");
$("html").addClass("mobile");
}
if (deviceAgent.match(/(Android)/)) {
$("html").addClass("android");
$("html").addClass("mobile");
}
if (navigator.userAgent.search("MSIE") >= 0) {
$("html").addClass("ie");
}
else if (navigator.userAgent.search("Chrome") >= 0) {
$("html").addClass("chrome");
}
else if (navigator.userAgent.search("Firefox") >= 0) {
$("html").addClass("firefox");
}
else if (navigator.userAgent.search("Safari") >= 0 && navigator.userAgent.search("Chrome") < 0) {
$("html").addClass("safari");
}
else if (navigator.userAgent.search("Opera") >= 0) {
$("html").addClass("opera");
}
});
</script>
<?php
}
add_filter( 'document_title_separator', 'blankslate_document_title_separator' );
function blankslate_document_title_separator( $sep ) {
$sep = esc_html( '|' );
return $sep;
}
add_filter( 'the_title', 'blankslate_title' );
function blankslate_title( $title ) {
if ( $title == '' ) {
return esc_html( '...' );
} else {
return wp_kses_post( $title );
}
}
function blankslate_schema_type() {
$schema = 'https://schema.org/';
if ( is_single() ) {
$type = "Article";
} elseif ( is_author() ) {
$type = 'ProfilePage';
} elseif ( is_search() ) {
$type = 'SearchResultsPage';
} else {
$type = 'WebPage';
}
echo 'itemscope itemtype="' . esc_url( $schema ) . esc_attr( $type ) . '"';
}
add_filter( 'nav_menu_link_attributes', 'blankslate_schema_url', 10 );
function blankslate_schema_url( $atts ) {
$atts['itemprop'] = 'url';
return $atts;
}
if ( !function_exists( 'blankslate_wp_body_open' ) ) {
function blankslate_wp_body_open() {
do_action( 'wp_body_open' );
}
}
add_action( 'wp_body_open', 'blankslate_skip_link', 5 );
function blankslate_skip_link() {
echo '<a href="#content" class="skip-link screen-reader-text">' . esc_html__( 'Skip to the content', 'blankslate' ) . '</a>';
}
add_filter( 'the_content_more_link', 'blankslate_read_more_link' );
function blankslate_read_more_link() {
if ( !is_admin() ) {
return ' <a href="' . esc_url( get_permalink() ) . '" class="more-link">' . sprintf( __( '...%s', 'blankslate' ), '<span class="screen-reader-text">  ' . esc_html( get_the_title() ) . '</span>' ) . '</a>';
}
}
add_filter( 'excerpt_more', 'blankslate_excerpt_read_more_link' );
function blankslate_excerpt_read_more_link( $more ) {
if ( !is_admin() ) {
global $post;
return ' <a href="' . esc_url( get_permalink( $post->ID ) ) . '" class="more-link">' . sprintf( __( '...%s', 'blankslate' ), '<span class="screen-reader-text">  ' . esc_html( get_the_title() ) . '</span>' ) . '</a>';
}
}
add_filter( 'big_image_size_threshold', '__return_false' );
add_filter( 'intermediate_image_sizes_advanced', 'blankslate_image_insert_override' );
function blankslate_image_insert_override( $sizes ) {
unset( $sizes['medium_large'] );
unset( $sizes['1536x1536'] );
unset( $sizes['2048x2048'] );
return $sizes;
}
add_action( 'widgets_init', 'blankslate_widgets_init' );
function blankslate_widgets_init() {
register_sidebar( array(
'name' => esc_html__( 'Sidebar Widget Area', 'blankslate' ),
'id' => 'primary-widget-area',
'before_widget' => '<li id="%1$s" class="widget-container %2$s">',
'after_widget' => '</li>',
'before_title' => '<h3 class="widget-title">',
'after_title' => '</h3>',
) );
}
add_action( 'wp_head', 'blankslate_pingback_header' );
function blankslate_pingback_header() {
if ( is_singular() && pings_open() ) {
printf( '<link rel="pingback" href="%s">' . "\n", esc_url( get_bloginfo( 'pingback_url' ) ) );
}
}
add_action( 'comment_form_before', 'blankslate_enqueue_comment_reply_script' );
function blankslate_enqueue_comment_reply_script() {
if ( get_option( 'thread_comments' ) ) {
wp_enqueue_script( 'comment-reply' );
}
}
function blankslate_custom_pings( $comment ) {
?>
<li <?php comment_class(); ?> id="li-comment-<?php comment_ID(); ?>"><?php echo esc_url( comment_author_link() ); ?></li>
<?php
}
add_filter( 'get_comments_number', 'blankslate_comment_count', 0 );
function blankslate_comment_count( $count ) {
if ( !is_admin() ) {
global $id;
$get_comments = get_comments( 'status=approve&post_id=' . $id );
$comments_by_type = separate_comments( $get_comments );
return count( $comments_by_type['comment'] );
} else {
return $count;
}
}
// off xml
add_filter('xmlrpc_enabled', '__return_false');
//custom style
function asc_scripts() {
	wp_enqueue_style('asc-global', get_stylesheet_directory_uri() . '/assets/css/global.css' );
	wp_enqueue_script('asc-mask', get_stylesheet_directory_uri() . '/assets/js/inputmask.js', array('jquery'), false, true);
	wp_enqueue_script('asc-custom', get_stylesheet_directory_uri() . '/assets/js/custom.js', array('jquery'), false, true);
}
add_action( 'wp_enqueue_scripts', 'asc_scripts' );
//clasic widget
add_filter( 'gutenberg_use_widgets_block_editor', '__return_false' );
add_filter( 'use_widgets_block_editor', '__return_false' );
//thumbnails
add_theme_support( 'post-thumbnails' );
add_image_size( 'product-small', 300, 300, true );
add_image_size( 'blog-catalog', 350, 250, true );
add_image_size( 'post-prew', 250, 250, true );
add_image_size( 'contact-img', 450, 300, true );
add_image_size( 'doc-img', 350, 500, true );
add_image_size( 'post-img', 1000, 450, true );
add_image_size( 'related-img', 250, 150, true );
//custom admin
function alter_login_headerurl() {
	return '/'; 
	 }
add_action('login_headerurl','alter_login_headerurl');
function login_logo() { ?>
<style type="text/css">
	.login form, .login #login_error, .login .message, .login .success {
	border-radius: 10px;
	font-family: inherit!important;
	}
	#loginform input[type=text] {
	font-size: 12px;
	}
	#loginform #wp-submit {
	background: #D82532;
	width: 100%;
	margin-top: 10px;
	border-radius: 5px;
	height: 50px;
	border-color: #D82532;
	border-width: 2px;
	}
	#nav, #backtoblog, .privacy-policy-page-link {
	font-family: inherit!important;
	}
	body.login div#login h1 a {
	background-image: url(/wp-content/uploads/screenshot_1-2x.svg) !important;
	background-size: contain;
	width: 280px;
	outline:none;
  }
	#loginform #wp-submit:focus,
	body.login div#login h1 a:focus {
	box-shadow: none;	
	}
	body {
	background: #F7F8FC!important;
	}
	.login #backtoblog a, .login #nav a {
	padding: 2px;
	border-radius: 0px;
	}
	#nav a, #backtoblog a {
	color: #fff!important;	
	}
</style>
<?php }
add_action( 'login_enqueue_scripts', 'login_logo' );
// Top 
function insertfooter(){
	?><a href="#top" class="scroll-top"><svg width="20" height="25" viewBox="0 0 20 25" fill="none" xmlns="http://www.w3.org/2000/svg"><path d="M10.5998 24.2694C10.7522 24.1169 10.8485 23.9083 10.8485 23.6676L10.8503 1.20328C10.8504 0.737952 10.4653 0.35288 9.99997 0.352917C9.53464 0.352955 9.14951 0.738087 9.14947 1.20342L9.14766 23.6678C9.14762 24.1331 9.53269 24.5182 9.99802 24.5181C10.2387 24.5181 10.4473 24.4218 10.5998 24.2694Z" fill=""></path><path d="M18.8405 10.0441C19.1695 9.71509 19.1695 9.16953 18.8406 8.84061L10.6017 0.601675C10.2728 0.272759 9.7272 0.272803 9.39823 0.601772L1.15796 8.84204C0.828992 9.17101 0.828948 9.71657 1.15786 10.0455C1.48678 10.3744 2.03234 10.3744 2.36131 10.0454L9.99981 2.40689L17.6371 10.0442C17.966 10.3731 18.5115 10.373 18.8405 10.0441Z" fill=""></path></svg></a>	<?php
}
add_action( 'wp_footer', 'insertfooter', 99 );
// Cache control
function add_cache_headers() {
  header('Cache-Control: public, max-age=3600'); 
}
add_action('init', 'add_cache_headers');
// form popup
function vipbyte_elementor_popup_cf7_fix() {
	?>
	<script type='text/javascript'>
	jQuery( document ).on( 'elementor/popup/show', () => {
			for(var i = 0; i < jQuery(".wpcf7-form").length ; i++)    {
			 wpcf7.init(jQuery(".wpcf7-form")[i]);  
			 jQuery('input[type="tel"]').inputmask('+38 (999) 999-99-99'); 
			}
	});
	</script>
	<?php
	}
add_action( 'wp_footer',  'vipbyte_elementor_popup_cf7_fix' );