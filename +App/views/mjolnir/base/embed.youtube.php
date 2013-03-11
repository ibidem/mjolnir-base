<?
	namespace app;
	
	$media_id = Media::id();
	$id = "youtube-$identifier--$media_id";
?>

<div class="video">
	<iframe id="<?= $id ?>" 
			src="http://www.youtube.com/embed/<?= $identifier ?>?rel=0" 
			data-src="http://www.youtube.com/embed/<?= $identifier ?>?rel=0" 
			rel="external"
			<? if ($width !== null): ?>width="<?= $width ?>"<? endif; ?> 
			<? if ($height !== null): ?>height="<?= $height ?>"<? endif; ?> 
			frameborder="0"
			allowfullscreen>

	</iframe>
	
	<script type="text/javascript">
		// chrome fix
		var id = '<?= $id ?>',
			videoframe = document.getElementById(id),
			src = videoframe.getAttribute('src');
			
		if (src == '' || typeof src == 'undefined') {
			var realsrc = videoframe.getAttribute('data-src');
			videoframe.setAttribute('src', realsrc);
		}
	</script>
</div>