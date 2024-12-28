<?php

namespace Config\Validation;

use CodeIgniter\Validation\Rules;
use CodeIgniter\Validation\ValidationException;

class CustomRules
{
    public static function strong_password(string $str, string &$error = null): bool
    {
        if (!preg_match('/[A-Z]/', $str)) {
            $error = 'The {field} must contain at least one uppercase letter.';
            return false;
        }

        if (!preg_match('/[a-z]/', $str)) {
            $error = 'The {field} must contain at least one lowercase letter.';
            return false;
        }

        if (!preg_match('/[0-9]/', $str)) {
            $error = 'The {field} must contain at least one digit.';
            return false;
        }

        if (!preg_match('/[!@#$%^&*()\-_=+{};:,<.>]/', $str)) {
            $error = 'The {field} must contain at least one special character.';
            return false;
        }

        return true;
    }

	public static function phone_number_format(string $str, string &$error = null): bool
    {
        $phoneNumber = preg_replace('/[^0-9]/', '', $str);

        if (!preg_match('/^\([0-9]{3}\) [0-9]{3}-[0-9]{4}-[0-9]{4}$/', $phoneNumber)) {
            $error = 'The {field} must be in the format (XXX) XXX-XXXX-XXXX.';
            return false;
        }

        return true;
    }
}
