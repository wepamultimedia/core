<?php

namespace Wepa\Core\Http\Traits;

trait TranslationsTrait
{
    public function languages(): array
    {
        $translations = $this->translations->toArray();
        $languages = [];
        foreach ($translations as $key => $translation) {
            $locale = $translation['locale'];
            unset($translation['locale'], $translation['role_id'], $translation['id']);
            $languages[$locale] = $translation;
        }

        return $languages;
    }
}
