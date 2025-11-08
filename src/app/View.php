<?php

namespace app;

class View 
{
    protected $template = null;

    public function __construct(string $template) 
    {
        $this->template = $template;
    }

    public function render(array $data): void 
    {
        include sprintf('../src/views/%s.php', $this->template);
        
        ob_start();
        ob_get_clean();
    }
}