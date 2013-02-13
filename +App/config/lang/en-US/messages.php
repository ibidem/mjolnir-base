<?php return array
	(
		'mjolnir:pager-pages' => function (array $in)
			{
				return \strtr
					(
						'Page <strong>:currentpage</strong> of :pagecount',
						$in
					);
			}
	);
