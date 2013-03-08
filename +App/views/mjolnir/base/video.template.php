<?
	namespace app;
	
	$formats = isset($formats) ? $formats : ['webm' => 'video/webm'];
	
	$has_mp4 = false;
	$has_flash = false;
	
	$base = CFS::config('mjolnir/base');
	$baseurl = $base['protocol'].$base['domain'].$base['path'];
	
	if (\in_array('mp4', \array_keys($formats)))
	{
		$has_mp4 = true;
		$formats = \app\Arr::filter($formats, function ($i, $value) { return $i != 'mp4'; });
	}
	
	if (\in_array('flv', \array_keys($formats)))
	{
		$has_flash = true;
		$formats = \app\Arr::filter($formats, function ($i, $value) { return $i != 'flv'; });
	}
?>

<div class="video">
	<video controls
		<? if ($width !== null): ?>width="<?= $width ?>"<? endif; ?> 
		<? if ($height !== null): ?>height="<?= $height ?>"<? endif; ?>>

		<? if ($has_mp4): ?>
			<!-- Safari / iOS video -->
			<source src="<?= "$baseurl$videokey" ?>.mp4" type="video/mp4" />
		<? endif; ?>

		<? foreach ($formats as $format => $mime): ?>
			<source src="<?= "$baseurl$videokey.$format" ?>" type="<?= $mime ?>" />
		<? endforeach; ?>

		<? # flash not currently supported ?>

	</video>
</div>