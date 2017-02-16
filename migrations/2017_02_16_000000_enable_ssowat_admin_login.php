<?php

use Flarum\Core\User;

return [
   'up' => function () {
      $query = User::where('id', 1);
      $query->update(['ssowat_id' => $query->username ]);;
   },
   'down' => function () {
      $query = User::where('id', 1);
      $query->update(['ssowat_id' => NULL ]);
   }
];
