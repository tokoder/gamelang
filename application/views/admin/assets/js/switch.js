
var toggleSwitch = $('.theme-switch input[type="checkbox"]');
toggleSwitch.on('change', function(e) {
    if (e.target.checked) {
        localStorage.setItem('theme', 'dark');
        setMode('dark')
    } else {
        localStorage.setItem('theme', 'light');
        setMode()
    }
});

var currentTheme = localStorage.getItem('theme');
var mainHeader = $('.main-header');
if (currentTheme) {
    if (currentTheme === 'dark') {
        toggleSwitch.checked = true;
        setMode('dark')
    }
}

function setMode(setmode = 'light') {
    if (setmode == 'dark') {
        if (!$('body').hasClass('dark-mode')) {
            $('body')
                .addClass("dark-mode");
        }
        if (mainHeader.hasClass('navbar-light')) {
            mainHeader
                .addClass('navbar-dark')
                .removeClass('navbar-light');
        }
    } else {
        if ($('body').hasClass('dark-mode')) {
            $('body')
                .removeClass("dark-mode");
        }
        if (mainHeader.hasClass('navbar-dark')) {
            mainHeader
                .addClass('navbar-light')
                .removeClass('navbar-dark');
        }
    }
}