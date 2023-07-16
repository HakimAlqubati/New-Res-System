<?php

namespace App\Filament\Pages;

use Illuminate\Contracts\View\View;

class View2 implements View
{
    private $view  ;
    private $data;
    public function __construct(string $view, array $data = [])
    {
        $this->view = $view;
        $this->data = $data;
    }

    public function name(): string
    {
        return $this->view;
    }

    public function with($key, $value = null): View
    {
        if (is_array($key)) {
            $this->data = array_merge($this->data, $key);
        } else {
            $this->data[$key] = $value;
        }
    
        return $this;
    }

    public function getData(): array
    {
        return $this->data;
    }
    public function render()
    {
        return view($this->view, $this->data);
    }
}
