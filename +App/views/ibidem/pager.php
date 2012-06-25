<? namespace app; ?>

<nav class="pager">
	
	<? if ($pagecount > 0 && $show_pageindex): ?>
		<div class="pager-currentnav">
			<?= Lang::msg
				(
					'pager.pages', 
					array
						(
							':currentpage' => $currentpage != null ? $currentpage : 1,
							':pagecount'   => $pagecount
						)
				) ?>
		</div>
	<? endif; ?>
	
	<div class="pager-pagenav">
	
		<? if ($currentpage != 1): ?>
			<div class="previous-page enabled">
				<a href="<?= $url_base.$querie ?>page=<?= $currentpage - 1 ?>" rel="prev"><span><?= $buttons['prev'] ?></span></a>
			</div>
		<? else: # current page is 1 ?>
			<div class="previous-page disabled">
				<a><span><?= $buttons['prev'] ?></span></a>
			</div>	
		<? endif; ?>

<? # ---- Pager ----------------------------------------------------------- # ?>
		
		<? 
		/* [!!] extremly sensitive to whitespace! html doesn't completely ignore
			* whitespace, and even 1 white space can thorw things off by a few 
			* pixels when working on a very tight pager design.
			*/
		?>

		<? if ($pagecount >= 1): ?>
		<span class="pager<?= ($has_ruler ? ' has-ruler' : '') ?>">

			<? if ( ! empty($currentpage)): ?>
				<span class="ruler">
			<? endif ?>

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
							?><span class="target pager-page"><?
								?><a href="<?= $url_base.$querie ?>page=<?= $i ?><?= '#'.$bookmark['entry'] ?>" <?
								?>title="<?= Lang::tr($title, array(':number' => $number, ':number_end' => $number_end, ':bookmark' => $bookmark)) ?>"><?= $i ?></a><?
							?></span><?
						endif;
						?><span class="ellipsis pager-page">&#8230;</span><?

					elseif ($i == $bookmark['page']):

						?><span class="target pager-page"><?
							?><a href="<?= $url_base.$querie ?>page=<?= $i ?><?= '#'.$bookmark['entry'] ?>" <?
							?>title="<?= Lang::tr($title, array(':number' => $number, ':number_end' => $number_end, ':bookmark' => $bookmark)) ?>"><?= $i ?></a><?
						?></span><?

					else: # standard page

						// check if current page
						if ($i == $currentpage):
							?><span class="this<?= ($bookmark['page'] == $i ? ' target' : '') ?> pager-page"><?
								?><a href="<?= $url_base.$querie ?>page=<?= $i ?><?= ($bookmark['page'] == $i ? '#'.$bookmark['entry'] : '') ?>" <?
								?>title="<?= Lang::tr($title, array(':number' => $number, ':number_end' => $number_end, ':bookmark' => $bookmark)) ?>"><?= $i ?></a><?
							?></span></span><?
						else: # $i != $currentpage
							?><span class="pager-page<?= ($bookmark['page'] == $i ? ' target' : '') ?>"><?
								?><a href="<?= $url_base.$querie ?>page=<?= $i ?><?= ($bookmark['page'] == $i ? '#'.$bookmark['entry'] : '') ?>" <?
								?>title="<?= Lang::tr($title, array(':number' => $number, ':number_end' => $number_end, ':bookmark' => $bookmark)) ?>"><?= $i ?></a><?
							?></span><?
						endif;

					endif;

				endif;
			endforeach; # targets 
			?>

		</span>
		<? endif; ?>
		
<? # ---- /Pager ---------------------------------------------------------- # ?>		
	
		<? if ($currentpage != $pagecount): ?>
			<div class="next-page enabled">
				<a href="<?= $url_base.$querie ?>page=<?= $currentpage + 1 ?>" rel="next"><span><?= $buttons['next'] ?></span></a>
			</div>	
		<? else: # last page ?>
			<div class="next-page disabled">
				<a><span><?= $buttons['next'] ?></span></a>
			</div>
		<? endif; ?>

	</div>

	<div class="pager-jumpto">
		<? if ($pagecount < 1000 && $show_jumpto): ?>

			<?= $form = Form::instance()
				->action($url_base)
				->field_template(':field')
				->method('GET')
				->insecure() ?>

				<div>
					<label>
						<span>Goto </span>
						<select name="page" onchange="this.form.submit()">
							<? for ($i = 1; $i <= $pagecount; ++$i): ?>
								<? if ($i != $currentpage): ?>
									<option value="<?= $i ?>"><?= $i ?></option>
								<? else: ?>
									<option value="<?= $i ?>" selected><?= $i ?></option>
								<? endif; ?>
							<? endfor; ?>
						</select>
					</label>
				</div>

				<?= $form->submit(Lang::tr('Go'))->classes(array('noscript')) ?>
			<?= $form->close() ?>

		<? endif; ?>
	</div>
	
</nav>
