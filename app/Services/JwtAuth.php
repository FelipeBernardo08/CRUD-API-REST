<?php

namespace App\Services;

class JwtAuth
{
    private $secret;

    public function __construct()
    {
        $this->secret = getenv("JWT_AUTH");
    }

    public function createToken(array $data): string
    {
        $header = [
            'alg' => 'HS256',
            'typ' => 'JWT'
        ];

        $base64Header = $this->base64UrlEncode(json_encode($header));
        $base64Payload = $this->base64UrlEncode(json_encode($data));

        $signature = hash_hmac('sha256', "$base64Header.$base64Payload", $this->secret, true);
        $base64Signature = $this->base64UrlEncode($signature);

        return "$base64Header.$base64Payload.$base64Signature";
    }

    public function decryptToken(string $token): array
    {
        $parts = explode('.', $token);
        if (count($parts) !== 3) {
            return [];
        }

        list($base64Header, $base64Payload, $base64Signature) = $parts;

        $signature = hash_hmac('sha256', "$base64Header.$base64Payload", $this->secret, true);
        $expectedSignature = rtrim(strtr(base64_encode($signature), '+/', '-_'), '=');

        if (!hash_equals($expectedSignature, $base64Signature)) {
            return [];
        }

        $payloadJson = $this->base64UrlDecode($base64Payload);
        $payload = json_decode($payloadJson, true);

        if (!$payload) {
            return [];
        }

        return $payload;
    }

    private function base64UrlEncode(string $data): string
    {
        return rtrim(strtr(base64_encode($data), '+/', '-_'), '=');
    }

    private function base64UrlDecode(string $data): string
    {
        $b64 = strtr($data, '-_', '+/');
        $pad = strlen($b64) % 4;
        if ($pad) {
            $b64 .= str_repeat('=', 4 - $pad);
        }
        return base64_decode($b64);
    }
}
