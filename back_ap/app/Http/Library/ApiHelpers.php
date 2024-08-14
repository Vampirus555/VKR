<?php

namespace App\Http\Library;

use Illuminate\Http\JsonResponse;

trait ApiHelpers
{
    protected function isAdmin($user): bool
    {
        if (!empty($user)) {
            return $user->tokenCan('admin');
        }

        return false;
    }

    protected function isUser($user): bool
    {

        if (!empty($user)) {
            return $user->tokenCan('user');
        }

        return false;
    }

    protected function isOrganizer($user): bool
    {
        if (!empty($user)) {
            return $user->tokenCan('organizer');
        }

        return false;
    }

    protected function isDelegate($user): bool
    {
        if (!empty($user)) {
            return $user->tokenCan('delegate');
        }

        return false;
    }

    protected function onSuccess($data, string $message = '', int $code = 200): JsonResponse
    {
        return response()->json([
            'status' => $code,
            'message' => $message,
            'data' => $data,
        ], $code);
    }

    protected function onError(int $code, string $message = ''): JsonResponse
    {
        return response()->json([
            'status' => $code,
            'message' => $message,
        ], $code);
    }

}
