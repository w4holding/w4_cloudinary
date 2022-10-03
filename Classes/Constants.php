<?php

namespace W4Services\W4Cloudinary;

final class Constants {

    CONST CLOUDINARY_FIELD_PUBLIC_ID = 'public_id';

    CONST CLOUDINARY_FIELD_URL = 'url';

    CONST CLOUDINARY_RESPONSIVE_CSS_CLASS = 'cld-responsive';

    CONST CLOUDINARY_RESPONSIVE_DEFAULT = [
        'width' => 'auto',
        'crop'  => 'scale',
        'responsive' => true
    ];

    CONST SYS_FILED_PREFIX = 'cloudinary_';

    CONST SYS_FILE_FIELD_PUBLIC_ID = self::SYS_FILED_PREFIX.self::CLOUDINARY_FIELD_PUBLIC_ID;

    CONST SYS_FILE_FIELD_URL = self::SYS_FILED_PREFIX.self::CLOUDINARY_FIELD_URL;

    CONST SYS_FILE_FIELD_FAILED = self::SYS_FILED_PREFIX.'failed';

    CONST SYS_FILE_FIELDS = [
        self::SYS_FILE_FIELD_PUBLIC_ID,
        self::SYS_FILE_FIELD_URL,
        self::SYS_FILE_FIELD_FAILED,
    ];

}
