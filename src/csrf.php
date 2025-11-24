<?php

class CSRF {
    /**
     * Validate a submitted token using a constant-time comparison to reduce
     * timing attacks.
     */
    static public function validateToken($token) {
        if (!isset($_SESSION['csrf_token']) || !is_string($token)) {
            return false;
        }

        return hash_equals($_SESSION['csrf_token'], $token);
    }

    /**
     * Generate a high-entropy token and store it in the session so multiple
     * forms rendered on the same page reuse the same value.
     */
    static private function getOrCreateToken() {
        if (empty($_SESSION['csrf_token'])) {
            $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
        }

        return $_SESSION['csrf_token'];
    }

    static public function csrfInputField() {
        $token = self::getOrCreateToken();
        echo '<input name="token" value="' . htmlspecialchars($token, ENT_QUOTES, 'UTF-8') . '" hidden>';
    }
}