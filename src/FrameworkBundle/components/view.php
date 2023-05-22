<?php

namespace Framework\Components;

abstract class View {
    private $data = [];
    protected static string|null $defaultViewTitle = SITE_NAME;
    protected string|null $viewTitle = null;

    public function __construct(array $data = [], string|null $viewTitle = null) {
        $this->viewTitle = $viewTitle ?? static::$defaultViewTitle;
        $this->data = $data;
        $this->data['viewName'] = get_class($this);
        $this->render();
    }

    protected function get(string $key) {
        if (array_key_exists($key, $this->data)) {
            return $this->data[$key];
        } else {
            return null;
        }
    }

    protected function viewTitle() {
        print $this->viewTitle;
    }

    abstract protected function render();

}
