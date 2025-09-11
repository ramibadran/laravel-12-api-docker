<?php


//->middleware("auth:api")
Route::prefix("settings")->as("settings.")->middleware(middleWare())->group(__DIR__ . DIRECTORY_SEPARATOR . "settings.php");
Route::prefix("users")->as("users.")->middleware(middleWare())->group(__DIR__ . DIRECTORY_SEPARATOR . "users.php");
Route::prefix("auth")->as("auth.")->middleware(middleWare())->group(__DIR__ . DIRECTORY_SEPARATOR . "auth.php");


