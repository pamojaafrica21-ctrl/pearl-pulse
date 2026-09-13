<?php

namespace App\Models\Concerns;

trait HasSeo
{
    public function getRouteKeyName(): string
    {
        return 'slug';
    }

    public function seoName(): string
    {
        return (string) ($this->name ?? $this->title ?? '');
    }

    public function seoTitle(): string
    {
        return $this->meta_title ?: $this->seoName().' | '.config('app.name');
    }

    public function seoDescription(): string
    {
        if ($this->meta_description) {
            return $this->meta_description;
        }

        if (! empty($this->teaser)) {
            return (string) $this->teaser;
        }

        if (! empty($this->excerpt)) {
            return (string) $this->excerpt;
        }

        $html = (string) ($this->description ?? $this->overview ?? $this->body ?? $this->content ?? '');

        return strip_tags($html);
    }
}
