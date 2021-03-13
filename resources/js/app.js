require('./bootstrap');

require('alpinejs');

document.addEventListener('DOMContentLoaded', () => {
    let text = process.env.MIX_DISPLAY_NAME.toUpperCase();
    let container = document.querySelectorAll('.logo-text');

    if (container) {
        container.forEach(instance => {
            let shades = [600, 500, 400, 300]
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

const dh = {};
dh.ord = n => ["st","nd","rd"][((n+90)%100-10)%10-1]||"th";
dh.formatDate = timestamp => {
    let date = new Date(timestamp),
        num = date.getUTCDay(),
        day = date.toLocaleDateString('en', { weekday: 'short' }),
        month = date.toLocaleString('en', { month: 'long' }),
        year = date.getFullYear(),
        time = date.toLocaleTimeString();

    return [day, num+dh.ord(num), month, year, time].join(" ");
}

window._dh = dh;
