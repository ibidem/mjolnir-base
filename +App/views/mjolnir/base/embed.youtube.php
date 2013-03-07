<?
	namespace app;
?>

<iframe src="http://www.youtube.com/embed/<?= $identifier ?>?rel=0" 
		<? if ($width !== null): ?>width="<?= $width ?>"<? endif; ?> 
		<? if ($height !== null): ?>height="<?= $height ?>"<? endif; ?> 
		frameborder="0"
		allowfullscreen>

</iframe>