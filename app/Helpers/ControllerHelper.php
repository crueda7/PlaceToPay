<?php

namespace App\Helpers;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class ControllerHelper
{
    public static function validateRequest(Request $request, array $fields): \Illuminate\Contracts\Validation\Validator|\Illuminate\Validation\Validator
    {
        return Validator::make($request->all(), $fields);

    }

    public static function encodeJsonResponse(JsonResponse $response): JsonResponse
    {
        return response()->json($response->getData());
    }

    public static function encodeStringResponse(array $response): bool|string
    {
        return json_encode($response);
    }
}
