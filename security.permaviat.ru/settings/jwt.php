<?php
function base64url_encode($data) {
    return rtrim(strtr(base64_encode($data), '+/', '-_'), '=');
}
function generate_jwt_habr($userId, $role) {
    $header = json_encode(['alg' => 'HS256', 'typ' => 'JWT']);
    $payload = json_encode([
        'userId' => $userId,
        'role'   => $role,
        'iat'    => time(),
        'exp'    => time() + 3600
    ]);
    $base64UrlHeader = base64url_encode($header);
    $base64UrlPayload = base64url_encode($payload);
    $SECRET_KEY = 'cAtwa1kkEy';
    $unsignedToken = $base64UrlHeader . "." . $base64UrlPayload;
    $signature = hash_hmac('sha256', $unsignedToken, $SECRET_KEY, true);
    $base64UrlSignature = base64url_encode($signature);
    return $base64UrlHeader . "." . $base64UrlPayload . "." . $base64UrlSignature;
}
function validate_jwt_habr($token) {
    $parts = explode('.', $token);
    if (count($parts) != 3) return false;
    list($header, $payload, $signature) = $parts;
    $SECRET_KEY = 'cAtwa1kkEy';
    $validSignature = base64url_encode(hash_hmac('sha256', "$header.$payload", $SECRET_KEY, true));
    if ($signature === $validSignature) {
        $data = json_decode(base64_decode(strtr($payload, '-_', '+/')), true);
        if (isset($data['exp']) && $data['exp'] < time()) {
            return false;
        }
        return $data;
    }
    return false;
}
?>