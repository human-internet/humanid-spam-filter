<?php

namespace humanid_spam_filter;

// Create users table
$users = new Migration( 'users' );
$users->id();
$users->string( 'human_id' );
$users->boolean( 'blocked' );
$users->timestamps();


