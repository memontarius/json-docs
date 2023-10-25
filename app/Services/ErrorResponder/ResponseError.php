<?php

namespace App\Services\ErrorResponder;

enum ResponseError: int
{
    case PageNotFound = 0;
    case BadRequest = 1;
    case ValidationFailed = 2;
    case AuthenticationFailed = 3;
    case Forbidden = 4;
    case NotAllowedEditPublishedDocument = 5;
}
