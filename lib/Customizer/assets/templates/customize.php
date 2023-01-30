<?php //phpcs:disable
namespace NetworkPortfolio;

$logo   = NETWORKPORTFOLIO_URL . 'lib/Customizer/assets/img/networkportfolio-logo.svg';
$width  = Helper::get_option( 'networkportfolio[width]', '0' );
$height = Helper::get_option( 'networkportfolio[height]', '0' );

$border_width  = Helper::get_option( 'networkportfolio[border_width]', 0 );
$border_color  = Helper::get_option( 'networkportfolio[border_color]', '#000000' );
$border_radius = Helper::get_option( 'networkportfolio[border_radius]', '0' );
?>
<body>
<style>
body {
	width: 100%;
	height: 100%;
	background-color: #ddd;
	display: flex;
	align-items: center;
	justify-content: center;
}
#networkportfolio {
	width: <?php echo $width; ?>px;
	height: <?php echo $height; ?>px;
	background-color: #3399FF;
	background-clip: content-box;
	border-style: solid;
	border: 0;
	border-radius: <?php echo $border_radius; ?>px;
	box-shadow: 0 0 0 <?php printf( '%spx %s', $border_width, $border_color ); ?>;
}
</style>
<img  id="networkportfolio" src="<?php echo $logo; ?>" />
</body>
