<?php

function render($path, $data = []) {
    include_once 'views' . $path;
}

function renderComponent($path, $data = []) {
    render('/Components' . $path, $data);
}
