<?php return array
	(
		'DateFormatter:yesterday' => 'yesterday',
		'DateFormatter:today' => 'today',
		'DateFormatter:tomorrow' => 'tomorrow',
		'pager.pages' => function (array $terms)
			{
				return \strtr
					(
						'Page <strong>:currentpage</strong> of :pagecount', 
						$terms
					);
			}
	);
