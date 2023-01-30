<?php //phpcs:disable ?>
<body>
<style>
body {
  width: 100%;
  height: 100%;
  background-color: #333333;
  display: flex;
  align-items: center;
  justify-content: center;
}
#info {
	width: 90%;
	height: 90%;
	background-color: #EEE;
	padding: 1em;
	font-family: 'Open Sans', 'Franklin Gothic', Arial, sans-serif;
	font-weight: 300;
}
img {
	width: 800px;
	max-width: 100%;
}
</style>
<div id="info">
<h1><?php _e( 'Settings', 'networkportfolio' ); ?></h1>
<p>
<?php _e( 'Enter your account details, you\'ll find them at:', 'networkportfolio' ); ?>  https://cloudinary.com/console
</p>
<p>
<?php _e( 'Cloudinary URL2PNG must be activated:', 'networkportfolio' ); ?>  https://cloudinary.com/console/lui/addons#url2png
</p>
</div>
</body>
