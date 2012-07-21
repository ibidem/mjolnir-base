<? namespace app; 
	/**
	 * This is a twitter bootstrap friendly version of the pager.
	 * It can also be considred an extremely simplified version.
	 * 
	 * While not standard, bookmark functionality is maintained.
	 * 
	 * [!!] Class structure is not compatible and conforms to Twitter Pagination
	 * [!!] Not all features of the default are supported.
	 */
?>

<nav class="pagination">
	
	<ul>
		
		<? if ($currentpage != 1): ?>
			<li>
				<a href="<?= $url_base.$querie ?>page=<?= $currentpage - 1 ?>" rel="prev"><span><?= $buttons['prev'] ?></span></a>
			</li>
		<? else: # current page is 1 ?>
			<li class="disabled">
				<a href="#"><span><?= $buttons['prev'] ?></span></a>
			</li>	
		<? endif; ?>

<? # ---- Pager ----------------------------------------------------------- # ?>

		<? 
		/* In the case where 1 item is displayed at a time (like some cases of 
			* pictures in a gallery) the page count can go over a million. It's 
			* very inneficient to iterate 1mil+ times so we pre-compute the 
			* targets.
			*/
		$targets = array();
		// inject first pages
		for ($i = 1; $i <= $startpoint; ++$i) $targets[] = $i;
		// inject last pages
		for ($i = $pagecount; $i > $endpoint; --$i) $targets[] = $i;
		// inject pages near current
		for ($i = $currentpage - $pagediff + 1; $i <= $currentpage + $pagediff - 1; ++$i):
			$targets[] = $i;
		endfor;
		// inject other targets
		$targets[] = $startellipsis;
		$targets[] = $endellipsis;
		$targets[] = $bookmark['page'];
		// tidy up everything and guarantee integrity
		$targets = array_unique($targets);
		\sort($targets);

		foreach ($targets as $i):
			if (1 <= $i && $i <= $pagecount):

				// compute title
				if ($pagelimit > 1):
					if ($order === \ibidem\types\Pager::ascending):
						$number = ($i * $pagelimit - $pagelimit + 1);
						$number_end = $i * $pagelimit > $totalitems ? $totalitems : $i * $pagelimit;
					else: # Pager::ascending
						$number = ($pagecount - $i + 1) * $pagelimit > $totalitems ? $totalitems : ($pagecount - $i + 1) * $pagelimit;
						$number_end = ($pagecount - $i + 1) * $pagelimit - $pagelimit + 1;
					endif;
					$title = Lang::tr($lang['entries_to_entries']);
					if ($i == $bookmark['page']):
						$title .= ' '.Lang::tr($lang['bookmark_at_entry']);
					endif;
				else: # single entry pages
					$number = $i;
					if ($i == $bookmark['page']):
						$title = Lang::tr($lang['page_x_bookmarked']);
					else:
						$title = Lang::tr($lang['page_x']);
					endif;
				endif;

				if ($startellipsis == $i || $endellipsis == $i): 

					if ($i == $bookmark['page']):
						?><li class="bookmarked"><?
							?><a href="<?= $url_base.$querie ?>page=<?= $i ?><?= '#'.$bookmark['entry'] ?>" <?
							?>title="<?= Lang::tr($title, array(':number' => $number, ':number_end' => $number_end, ':bookmark' => $bookmark)) ?>"><?= $i ?></a><?
						?></li><?
					endif;
					?><li class="ellipsis"><a href="#">&#8230;</a></li><?

				elseif ($i == $bookmark['page']):

					?><li class="bookmarked"><?
						?><a href="<?= $url_base.$querie ?>page=<?= $i ?><?= '#'.$bookmark['entry'] ?>" <?
						?>title="<?= Lang::tr($title, array(':number' => $number, ':number_end' => $number_end, ':bookmark' => $bookmark)) ?>"><?= $i ?></a><?
					?></li><?

				else: # standard page

					// check if current page
					if ($i == $currentpage):
						?><li class="active<?= ($bookmark['page'] == $i ? ' bookmarked' : '') ?>"><?
							?><a href="<?= $url_base.$querie ?>page=<?= $i ?><?= ($bookmark['page'] == $i ? '#'.$bookmark['entry'] : '') ?>" <?
							?>title="<?= Lang::tr($title, array(':number' => $number, ':number_end' => $number_end, ':bookmark' => $bookmark)) ?>"><?= $i ?></a><?
						?></li><?
					else: # $i != $currentpage
						?><li class="<?= ($bookmark['page'] == $i ? ' bookmarked' : '') ?>"><?
							?><a href="<?= $url_base.$querie ?>page=<?= $i ?><?= ($bookmark['page'] == $i ? '#'.$bookmark['entry'] : '') ?>" <?
							?>title="<?= Lang::tr($title, array(':number' => $number, ':number_end' => $number_end, ':bookmark' => $bookmark)) ?>"><?= $i ?></a><?
						?></li><?
					endif;

				endif;

			endif;
		endforeach; # targets 
		?>	
		
<? # ---- /Pager ---------------------------------------------------------- # ?>		
	
		<? if ($currentpage != $pagecount): ?>
			<li>
				<a href="<?= $url_base.$querie ?>page=<?= $currentpage + 1 ?>" rel="next"><span><?= $buttons['next'] ?></span></a>
			</li>	
		<? else: # last page ?>
			<li class="disabled">
				<a><span><?= $buttons['next'] ?></span></a>
			</li>
		<? endif; ?>

	</ul>
	
</nav>
