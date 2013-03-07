<?
	namespace app;
?>

<iframe src="http://player.vimeo.com/video/<?= $identifier ?>" 
		<? if ($width !== null): ?>width="<?= $width ?>"<? endif; ?> 
		<? if ($height !== null): ?>height="<?= $height ?>"<? endif; ?> 
		frameborder="0"
		webkitAllowFullScreen 
		mozallowfullscreen
		allowfullscreen>

</iframe>