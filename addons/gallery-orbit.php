<?php 
/**
NextGEN Gallery Template Page for Zurb's Orbit gallery

Peeter Marvet / pets@tehnokratt.net 18.11.2012

usage:
place into plugins/nextgen-gallery/view
uncomment Orbit callery call in app.js
call with shortcode like: [nggallery id=1 template=orbit]

**/
?>
<?php if (!defined ('ABSPATH')) die ('No direct access allowed'); ?><?php if (!empty ($gallery)) : ?>

<div id="orbit">

		<!-- Thumbnail list -->
		<?php foreach ( $images as $image ) : ?>
		<?php if ( $image->hidden ) continue; ?> 
		
		<img title="<?php echo $image->alttext ?>" alt="<?php echo $image->alttext ?>" src="<?php echo $image->url ?>" />

	 	<?php endforeach; ?>
	 	 	
</div>

<?php endif; ?>