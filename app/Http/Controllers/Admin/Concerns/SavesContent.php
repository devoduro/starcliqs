<?php

namespace App\Http\Controllers\Admin\Concerns;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

/**
 * Shared form handling for the content screens: "one per line" text areas become arrays, and image
 * fields are stored on the public `uploads` disk (public/uploads), replacing or removing the old file.
 */
trait SavesContent
{
    protected function lines(?string $text): array
    {
        return array_values(array_filter(array_map('trim', preg_split('/\r\n|\r|\n/', (string) $text))));
    }

    /**
     * Returns the path to keep for $field: a freshly uploaded file, null when the admin ticked
     * "remove", otherwise the existing $current path.
     */
    protected function storeImage(Request $request, string $field, string $folder, ?string $current = null): ?string
    {
        if ($request->hasFile($field)) {
            $this->deleteImage($current);

            return $request->file($field)->store($folder, 'uploads');
        }

        if ($request->boolean('remove_' . $field)) {
            $this->deleteImage($current);

            return null;
        }

        return $current;
    }

    protected function deleteImage(?string $path): void
    {
        if ($path) {
            Storage::disk('uploads')->delete($path);
        }
    }
}
