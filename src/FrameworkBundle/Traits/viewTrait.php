<?php

function render($path, $data = []) {
    include_once $_SERVER['DOCUMENT_ROOT']. '/views' . $path;
}

function renderComponent($path, $data = []) {
    render('/Components' . $path, $data);
}
