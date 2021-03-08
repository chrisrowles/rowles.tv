require('./bootstrap');

require('alpinejs');

document.addEventListener('DOMContentLoaded', () => {
    let text = process.env.MIX_DISPLAY_NAME.toUpperCase();
    let container = document.querySelectorAll('.logo-text');

    if (container) {
        container.forEach(instance => {
            let shades = [700, 600, 500, 400]
            let shadeIndex = 0;
            let logo = document.createElement('span');
            for (textIndex=0;textIndex<text.length;++textIndex) {
                let textNode = document.createElement('span');

                if (!shades[shadeIndex]) {
                    shades = shades.slice().reverse();
                    shadeIndex = 1;
                }

                textNode.classList.add('text-' + process.env.MIX_PRIMARY_THEME_COLOR + '-' + shades[shadeIndex]);
                textNode.innerText = text[textIndex];

                logo.appendChild(textNode);

                ++shadeIndex;
            }

            instance.prepend(logo);
        })
    }
});
