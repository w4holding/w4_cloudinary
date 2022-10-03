[![TYPO3 11](https://img.shields.io/badge/TYPO3-11-orange.svg)](https://get.typo3.org/version/11)

# TYPO3 Extension `w4_cloudinary` 

Implements API and support for Cloudinary. This extension is based on the official Cloudinary's PHP SDK (https://cloudinary.com/documentation/php_integration).

Once installed, when uploading an image in the `Filelist` it will be uploaded to Cloudinary immediatelly or when running a scheduled task.

The `img` tag in the front end is modified so its `src` is the Cloudinary file url (instead of the one to the original or the file processed by TYPO3) or, if desired, the `src` will be filled up dinamically via JavaScript with the url to a Cloudinary version of the image optimized for the user's device. This ensures that images are optimized for each device.

When renaming, moving to a different folder or deleting an image this change will be updated also in Cloudinary.

|                  | URL                                                   |
|------------------|-------------------------------------------------------|
| **Repository:**  | https://github.com/w4holding/w4_cloudinary            |
| **TER:**         | https://extensions.typo3.org/extension/w4_cloudinary/ |

## Compatibility

| w4_cloudinary | TYPO3 | PHP       | Support / Development                |
|---------------|-------|-----------|--------------------------------------|
| dev-main      | 11.5  | 7.4 - 8.1 | unstable development branch          |
| 1.x.x         | 11.5  | 7.4 - 8.1 | features, bugfixes, security updates |

## Installation

Install via composer:

    composer require w4services/w4_cloudinary
