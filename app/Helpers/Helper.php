<?php

function statusResponse($statusCode, $status , $message, $data) {
    return response()->json([
        'statusCode' => $statusCode,
        'message' => $message,
        'data' => $data
    ], $status);
}