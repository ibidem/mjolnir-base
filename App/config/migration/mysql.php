<?php return array
	(
	// common
		':key_primary' => "bigint(20) unsigned NOT NULL AUTO_INCREMENT",
		':key_foreign' => "bigint(20) unsigned",
		':counter' => "bigint(20) unsigned NOT NULL DEFAULT '0'",
		':title' => "varchar(255)",
		':block' => "varchar(10000)",
		':datetime_required' => 'datetime NOT NULL',
		':datetime_optional' => 'datetime',
		':timestamp' => 'timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP',
	
	// uncommon
		# RFC 5321; we subtract 2 characters for angle brackets -- yes it''s not (64 + 1 + 255 =) 320
		':email' => 'varchar(254) CHARACTER SET latin1 COLLATE latin1_general_ci NOT NULL',
		# close to the shorhand version of the longest name on record
		':name' => 'varchar(80)',
		':username' => 'varchar(80) NOT NULL',
		# IPv6 length 39 + tunneling IPv4 = 45
		':ipaddress' => 'varchar(45) CHARACTER SET latin1 COLLATE latin1_bin NOT NULL',
		# Secure Hash Algorthm (sha512)
		':secure_hash' => 'char(128) CHARACTER SET latin1 COLLATE latin1_bin NOT NULL',
		# telephone number
		':telephone' => 'varchar(255)',
	
	// general
		':engine' => 'InnoDB',
		':default_charset' => 'utf8',
	);
