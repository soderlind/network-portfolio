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
}
img {
	width: 800px;
	max-width: 100%;
}
</style>
<div id="info">
<h1><?php _e( 'Settings', 'networkportfolio' ); ?></h1>
<p>
<?php _e( 'Enter your account details, you\'ll find them at' ,'networkportfolio' ); ?>  https://cloudinary.com/console
</p>
<p>
	<img src="<?php echo NETWORKPORTFOLIO_URL;?>lib/Customizer/assets/img/cloudinary-dashboard.png" />
</p>
</div>
</body>
