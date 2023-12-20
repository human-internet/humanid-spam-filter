<?php

namespace humanid_spam_filter;

// Create users table
use KMBlueprint;
use KMMigration;

class CreateUsersTable extends KMMigration {
	protected $table_name = 'users';

	public function up( KMBlueprint $blueprint ) {
		$blueprint->id();
		$blueprint->string( 'human_id' );
		$blueprint->boolean( 'blocked' );
		$blueprint->timestamps();
	}

	public function down( KMBlueprint $blueprint ) {
		$blueprint->drop();
	}
}



