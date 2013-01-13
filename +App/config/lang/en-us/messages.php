<?php return array
	(
		'pager.pages' => function (array $in)
			{
				return \strtr
					(
						'Page <strong>:currentpage</strong> of :pagecount',
						$in
					);
			}
	);
