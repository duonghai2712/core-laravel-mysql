<?php
const STATUS_API_SUCCESS = 0;
const STATUS_API_FALSE = 1;
const STATUS_API_INPUT_VALIDATOR = 2;
const STATUS_API_INPUT_VALIDATOR_ARRAY = 4;
const STATUS_API_INPUT_EMPTY_DATA = 3;
const STATUS_API_TOKEN_FALSE = 5;
const STATUS_API_TOKEN_EXPIRED = 10;
const STATUS_API_EXPIRED_TIME = 11;
const STATUS_API_TOKEN_EMPTY = 6;
const STATUS_API_DEVICE_ACTIVE_CODE_NO_EXISTS = 7;
const STATUS_API_DEVICE_EXISTS = 8;
const STATUS_API_ERROR = 500;


function public_link($router)
{
    return url(str_replace('//', '/', '/' . $router));
}
