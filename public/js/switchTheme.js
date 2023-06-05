let $themeStyle = $('#theme-style');
let $actualTheme = $themeStyle.attr('theme');
$('#theme-style').attr('href', '/css/' + $actualTheme + '-theme.css');

let $switchButton = $('#switchTheme');

$switchButton.click(function () {
    fetch('/theme/', { method: 'POST' })
    .then(response => response.json())
    .then(data => {
        console.log(data);
        $actualTheme = data.theme;
        $themeStyle.attr('href', '/css/' + $actualTheme + '-theme.css');
        $themeStyle.attr('theme', $actualTheme);
        console.log($actualTheme);
    });
});

$switchButton.hover(function () {
    $switchButton.css('cursor', 'pointer');
});
