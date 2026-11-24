<?php
// Helpers for image uploads (used by create.php, update.php, delete.php)

const UPLOAD_DIR = __DIR__ . '/../uploads/';
const MAX_IMAGE_SIZE = 2 * 1024 * 1024; // 2 MB

// true if the user actually chose a file in the form
function image_was_sent(?array $file): bool
{
    return $file !== null && $file['error'] !== UPLOAD_ERR_NO_FILE;
}

// Checks an uploaded file. Returns [extension, error]. error is null when the file is OK.
function check_image(array $file): array
{
    if ($file['error'] === UPLOAD_ERR_INI_SIZE || $file['error'] === UPLOAD_ERR_FORM_SIZE) {
        return [null, 'Image is too large'];
    }
    if ($file['error'] !== UPLOAD_ERR_OK) {
        return [null, 'Image upload failed'];
    }
    if ($file['size'] > MAX_IMAGE_SIZE) {
        return [null, 'Image must be 2 MB or smaller'];
    }

    // real type from the file's bytes, not from its name
    $mime = (new finfo(FILEINFO_MIME_TYPE))->file($file['tmp_name']);
    $allowed = [
        'image/jpeg' => 'jpg',
        'image/png'  => 'png',
        'image/webp' => 'webp',
    ];
    if (!isset($allowed[$mime])) {
        return [null, 'Only JPG, PNG or WebP images are allowed'];
    }

    return [$allowed[$mime], null];
}

// Moves the file into uploads/ with a random name. Returns the new file name, or null on failure.
function save_image(array $file, string $ext): ?string
{
    $name = bin2hex(random_bytes(16)) . '.' . $ext;
    if (!move_uploaded_file($file['tmp_name'], UPLOAD_DIR . $name)) {
        return null;
    }
    return $name;
}

// Deletes a file from uploads/ (does nothing if the name is empty or the file does not exist)
function delete_image(?string $name): void
{
    if (empty($name)) {
        return;
    }
    $path = UPLOAD_DIR . basename($name); // basename: only files inside uploads/ can be touched
    if (is_file($path)) {
        unlink($path);
    }
}
