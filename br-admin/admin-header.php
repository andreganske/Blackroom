<?php

	$login = new Login();

	// ... ask if we are logged in here:
	if ($login->isUserLoggedIn() != true) {
		<?php http_redirect("index.php");?>
	}

?>
<!DOCTYPE html>
<html <?php language_attributes(); ?> class="no-js">
<head>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width">
	<!--[if lt IE 9]>
	<script src="<?php echo esc_url( get_template_directory_uri() ); ?>/js/html5.js"></script>
	<![endif]-->
	<?php wp_head(); ?>
</head>

<body <?php body_class(); ?>>
<div id="page" class="hfeed site">
	<a class="skip-link screen-reader-text" href="#content"><?php _e( 'Skip to content', 'twentyfifteen' ); ?></a>

	<div id="sidebar" class="sidebar">
		<header id="masthead" class="site-header" role="banner">
			<div class="site-branding">
				<h1 class="site-title"><?php echo $_SESSION['user_name']; ?></h1>
			</div>
		</header>

		<?php get_sidebar(); ?>
	</div>

	<div id="content" class="site-content">