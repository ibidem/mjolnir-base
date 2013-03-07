<?
	namespace app;
	
	$formats = isset($formats) ? $formats : ['webm'];
	
	$has_mp4 = false;
	$has_flash = false;
	
	$base = CFS::config('mjolnir/base');
	$baseurl = $base['protocol'].$base['domain'].$base['path'];
	
	if (\in_array('mp4', $formats))
	{
		$has_mp4 = true;
		$formats = \app\Arr::filter($formats, function ($i, $value) { return $value != 'mp4'; });
	}
	
	if (\in_array('flv', $formats))
	{
		$has_flash = true;
		$formats = \app\Arr::filter($formats, function ($i, $value) { return $value != 'flv'; });
	}
?>

<div class="video">
	<video <? if ($width !== null): ?>width="<?= $width ?>"<? endif; ?> <? if ($height !== null): ?>height="<?= $height ?>"<? endif; ?> controls>

		<? if ($has_mp4): ?>
			<!-- Safari / iOS video -->
			<source src="<?= "$baseurl$videokey" ?>.mp4" type="video/mp4" />
		<? endif; ?>

		<? foreach ($formats as $format): ?>
			<source src="<?= "$baseurl$videokey.$format" ?>" type="video/<?= $format ?>" />
		<? endforeach; ?>

		<? # flash not currently supported ?>

	</video>
</div>