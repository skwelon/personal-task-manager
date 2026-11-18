<?php

/**
 * @apiGroup           Authentication
 *
 * @apiName            RegisterUser
 *
 * @api                {post} /v1/register Register User
 *
 * @apiDescription     Register a new user
 *
 * @apiVersion         1.0.0
 *
 * @apiPermission      none
 *
 * @apiHeader          {String} accept=application/json
 *
 * @apiBody            {String} name min:2|max:50
 * @apiBody            {String} email
 * @apiBody            {String} password min:6
 *
 * @apiUse             UserSuccessSingleResponse
 */

use App\Containers\AppSection\Authentication\UI\API\Controllers\RegisterUserController;
use Illuminate\Support\Facades\Route;

Route::post('/register', RegisterUserController::class);
