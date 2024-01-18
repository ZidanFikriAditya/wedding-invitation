<?php

namespace App\Http\Helpers;

class MedsosEnum {
    const FACEBOOK = 'facebook';
    const INSTAGRAM = 'instagram';
    const TWITTER = 'twitter';
    const YOUTUBE = 'youtube';
    const TIKTOK = 'tiktok';
    const LINKEDIN = 'linkedin';
    const WHATSAPP = 'whatsapp';
    const TELEGRAM = 'telegram';
    const EMAIL = 'email';
    const WEBSITE = 'website';
    const OTHER = 'other';

    public static function getMedsosList()
    {
        return [
            self::FACEBOOK,
            self::INSTAGRAM,
            self::TWITTER,
            self::YOUTUBE,
            self::TIKTOK,
            self::LINKEDIN,
            self::WHATSAPP,
            self::TELEGRAM,
            self::EMAIL,
            self::WEBSITE,
            self::OTHER,
        ];
    }

    public static function getMedsosName(string $medsos)
    {
        switch ($medsos) {
            case self::FACEBOOK:
                return 'Facebook';
            case self::INSTAGRAM:
                return 'Instagram';
            case self::TWITTER:
                return 'Twitter';
            case self::YOUTUBE:
                return 'Youtube';
            case self::TIKTOK:
                return 'Tiktok';
            case self::LINKEDIN:
                return 'Linkedin';
            case self::WHATSAPP:
                return 'Whatsapp';
            case self::TELEGRAM:
                return 'Telegram';
            case self::EMAIL:
                return 'Email';
            case self::WEBSITE:
                return 'Website';
            case self::OTHER:
                return 'Lainnya';
            default:
                return 'Tidak diketahui';
        }
    }
}