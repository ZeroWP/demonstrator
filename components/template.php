<!DOCTYPE html>
<html <?php language_attributes(); ?>>
<head>
	<meta charset="<?php bloginfo( 'charset' ); ?>" />
	<meta http-equiv="X-UA-Compatible" content="IE=edge">

	<!-- Allow responsive -->
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<link rel="profile" href="http://gmpg.org/xfn/11">

	<!-- Set the adress bar color in chrome 39+ on mobile devices  -->
	<meta name="theme-color" content="#262F35">

	<!-- Make the website fullscreen when the site is added on homepage -->
	<meta name="mobile-web-app-capable" content="yes">
	<meta name="apple-mobile-web-app-capable" content="yes">

<!-- 
|=============================================================================|
|                                                                             |
|                                                                             |
|          ___  ____ _  _ ____ _  _ ____ ___ ____ ____ ___ ____ ____          | 
|          |  \ |___ |\/| |  | |\ | [__   |  |__/ |__|  |  |  | |__/          | 
|          |__/ |___ |  | |__| | \| ___]  |  |  \ |  |  |  |__| |  \          | 
|                                                                             |
|                        http://zerowp.com/demonstrator                       |
|                  https://wordpress.org/plugins/demonstrator                 |
|                                                                             |
|                                                                             |
|=============================================================================|
-->
<?php 
$themes = demonstrator_themes();
?>

	<title><?php echo get_option( 'blogname' ); ?></title>

	<script type="text/javascript">
		<?php 
			// JSON Pretty print is required only for development
			// if ( version_compare( PHP_VERSION, '5.4', '>' ) ) {
			// 	echo 'var demonstrator_themes = '. json_encode( $themes, JSON_PRETTY_PRINT );
			// }
			// else{
				echo 'var demonstrator_themes = '. json_encode( $themes );
			// }
			
		?>
	</script>

	<?php do_action( 'demonstrator_header' ); ?>
</head>
<body>

<?php 

	$loaded_theme        = !empty( $_GET['theme'] ) ? sanitize_key( $_GET['theme'] ) : false;
	$loaded_style        = !empty( $_GET['style'] ) ? sanitize_key( $_GET['style'] ) : false;
	$loaded_theme_exists = $loaded_theme && array_key_exists($loaded_theme, $themes);
	$iframe_url          = 'about:blank';
	$hidden_styles_menu  = 'hidden';

	if( $loaded_theme_exists ){
		if( is_string( $themes[ $loaded_theme ]['demo_url'] ) ){
			$iframe_url = esc_url_raw( $themes[ $loaded_theme ]['demo_url'] );
		}
		elseif( is_array( $themes[ $loaded_theme ]['demo_url'] ) ){
			$hidden_styles_menu = '';
		}
	}

	$open_themes = '';
	if( empty( $loaded_theme_exists ) ){
		$open_themes = ' active';
	}

	$open_styles = '';
	if( empty( $hidden_styles_menu ) ){
		$open_styles = ' active';
	}

	// if we have a style url in query, load it and ignore everything else
	if( !empty( $loaded_style ) ){
		$iframe_url = esc_url_raw( $themes[ $loaded_theme ]['demo_url'][ $loaded_style ]['url'] );
		$open_themes = '';
		$open_styles = '';
	}

?>

<div class="demonstrator-container">
	<div id="demonstrator-bar" class="top-bar">
		<div class="bar-left">
			<?php 
				$logo_url = dts_get_option( 'demonstrator_options',  'brand_logo' );
				$site_url = dts_get_option( 'demonstrator_options',  'brand_site_url', '' );
				$open_tag = !empty( $site_url ) ? 'a href="'. esc_url_raw($site_url) .'" target="_blank" ' : 'span ';
				$close_tag = !empty( $site_url ) ? 'a' : 'span';
				if( !empty( $logo_url ) ) {
					echo '<'. $open_tag .' class="logo"><img src="'. esc_url_raw( $logo_url ) .'" alt="" /></'. $close_tag .'>';
				} 
			?>
			<div id="menu-themes" class="menu-selector menu-themes <?php echo $open_themes; ?>">
				<span class="the-icon flaticon-shapes"></span>
				<span class="placeholder"><?php _e( 'Choose theme', 'demonstrator' ) ?></span>
			</div>
			<div id="menu-styles" class="menu-selector menu-styles <?php echo $hidden_styles_menu . $open_styles; ?>">
				<span class="the-icon flaticon-web-1"></span>
				<span class="placeholder"><?php _e( 'Choose style', 'demonstrator' ) ?></span>
			</div>
		</div>
		<div class="bar-right">
			<span id="toggle-bar" class="toggle-bar">
				<span class="the-icon up flaticon-up-arrow"></span>
				<span class="the-icon down flaticon-down-arrow"></span>
			</span>
		</div>
		<div class="bar-right">
			<a id="purchase" href="#" target="_blank" class="btn-buy">
				<span class="the-icon flaticon-commerce"></span>
				<span class="placeholder"><?php _e( 'Purchase', 'demonstrator' ) ?></span>
			</a>
		</div>
	</div>

	<div class="preview-frame">
		<iframe src="<?php echo esc_url_raw( $iframe_url ); ?>" id="preview"></iframe>
	</div>
	
</div>

<?php 
	$theme_columns = dts_get_option( 'demonstrator_options',  'theme_columns', 4 );
	$style_columns = dts_get_option( 'demonstrator_options',  'style_columns', 4 );
?>

<?php if( !empty( $themes ) ) : ?> 

<div class="demonstrator-dropdown themes <?php echo $open_themes; ?>">
	<div class="dd-container">
		<div class="zg <?php echo demonstrator_get_columns_class( $theme_columns ); ?>">
			<?php 
			foreach ($themes as $theme_id => $theme) {
				if( empty( $theme['demo_url'] ) )
					continue;

				$url = is_string( $theme['demo_url'] ) ? $theme['demo_url'] : '#';
				$active = ( $loaded_theme === $theme_id ) ? ' active' : '';
				$total_styles = is_array( $theme['demo_url'] ) ? count( $theme['demo_url'] ) : 1;
				
				echo '<div class="theme-item '. $theme_id . $active .'">
					<a data-theme-id="'. $theme_id .'" href="'. esc_url_raw( $url ) .'" class="a-demo-item-link theme">
						<div class="item-img"><img src="'. $theme[ 'img' ] .'" /></div>
						<div class="label">
							<div class="zg zg-2 zg-nowrap">
								<div class="text-left">
									<span class="l-badge l-title">'. $theme[ 'label' ] . '</span>
									<span class="l-badge l-category '. sanitize_html_class(  strtolower($theme[ 'category' ]) ) .'">'. $theme[ 'category' ] . '</span>
								</div>
								<div class="text-right">
									<span class="l-badge l-styles-nr">'. 
										sprintf( 
											_n( '%s style', '%d styles', $total_styles, 'demonstrator' ), 
											$total_styles 
										) . '
									</span>
								</div>
							</div>'
						.'</div>
					</a>
				</div>';	
			}
			?>
		</div>
	</div>
</div>


<div class="demonstrator-dropdown styles <?php echo $open_styles; ?>">
	<div class="dd-container">
		<div class="zg <?php echo demonstrator_get_columns_class( $style_columns ); ?>">
			<?php 
			foreach ($themes as $theme_id => $theme) {
				if( empty( $theme['demo_url'] ) )
					continue;

				if( ! is_array( $theme['demo_url'] ) )
					continue;

				foreach ($theme['demo_url'] as $style_id => $style) {
					$url = is_string( $style['url'] ) ? $style['url'] : '#';
					$hidden = ( $loaded_theme !== $theme_id ) ? ' hidden' : '';
				
					echo '<div class="style-item '. $theme_id . $hidden .'">
						<a data-theme-id="'. $theme_id .'" data-style-id="'. $style_id .'" href="'. $url .'" class="a-demo-item-link style">
							<div class="item-img"><img src="'. $style[ 'img' ] .'" /></div>
							<div class="label">'. $style[ 'label' ] .'</div>
						</a>
					</div>';
				}
			}
			?>
		</div>
	</div>
</div>

<?php endif; ?>

<?php do_action( 'demonstrator_footer' ); ?>
</body>
</html>