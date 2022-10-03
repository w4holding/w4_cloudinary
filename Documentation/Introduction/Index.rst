.. include:: /Includes.rst.txt

.. _introduction:

============
Introduction
============

W4 Cloudinary implements API and support for Cloudinary. This extension is based on the official Cloudinary's PHP SDK (https://cloudinary.com/documentation/php_integration).

Once installed, when uploading an image in the :guilabel:`Filelist` it will be uploaded to Cloudinary immediatelly or when running an scheduled task.

The :guilabel:`img` tag in the front end is modified so its :guilabel:`src` is the Cloudinary file url (instead of the one to the original or the file processed by TYPO3) or, if desired, the :guilabel:`src` will be filled up dinamically via JavaScript with the url to a Cloudinary version of the image optimized for the user's device. This ensures that images are optimized for each device.

When renaming, moving to a different folder or deleting an image this change will be updated also in Cloudinary.
