<?php return array
	(
		'today' => function (\DateTime $datetime)
			{
				$now = new \DateTime('now');
				if ($datetime->format('Y-m-d') == $now->format('Y-m-d'))
				{
					return true;
				}
				else # not same day
				{
					return false;
				}
			},
		'yesterday' => function (\DateTime $datetime)
			{
				$then = new \DateTime('-1 day');
				if ($datetime->format('Y-m-d') == $then->format('Y-m-d'))
				{
					return true;
				}
				else # not same day
				{
					return false;
				}
			},
		'tomorrow' => function (\DateTime $datetime)
			{
				$then = new \DateTime('+1 day');
				if ($datetime->format('Y-m-d') == $then->format('Y-m-d'))
				{
					return true;
				}
				else # not same day
				{
					return false;
				}
			},
	);