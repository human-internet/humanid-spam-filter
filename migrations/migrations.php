<?php

namespace humanid_spam_filter;

// Create users table
$memberships = new Migration( 'users' );
$memberships->id();
$memberships->string( 'token' );
$memberships->boolean( 'blocked' );
$memberships->timestamps();


