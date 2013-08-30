<?php return array
	(
		'mjolnir:participants-missing-email-addresses' => function ($in)
			{
				// this message is intentionally a little vague and doesn't
				// mention email addresses in case email addresses in question
				// are private in the given context

				return 'Email could not be sent due to one of the participants not having an email address.';
			},

	); # config
