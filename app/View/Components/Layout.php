<?php

namespace app\View\Components;

class Layout
{
    public string $title;

    public function __construct(string $title = 'Default Title')
    {
        $this->title = $title;
    }

    public function render(): string
    {
        return 'components.layout';
    }

    public function data(): array
    {
        return ['title' => $this->title];
    }
}
