<?php

use Flarum\Database\Migration;

return Migration::addColumns('users', [
	'ssowat_id' => ['string', 'length' => 255, 'nullable' => true]
]);
