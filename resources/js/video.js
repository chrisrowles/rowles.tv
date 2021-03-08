import notify from "./notify";

const video = {};

let headers = {
    "Content-Type": "application/json",
    "Accept": "application/json",
    "X-Requested-With": "XMLHttpRequest"
}

video.setup = path => {
    jwplayer('video-container').setup({
        'playlist': [{
            'file': path
        }]
    });
}

video.get = (route, refs) => {
    video.resetFormFields();

    const handleResponse = response => {
        if (!response.ok) {
            throw new Error(response.statusText);
        }

        return response.json();
    }

    const handleData = data => {
        refs.selectedVideoTitle.value = data.title;
        refs.selectedVideoProducer.value = data.producer;
        refs.selectedVideoGenre.value = data.genre;
        refs.selectedVideoDescription.value = data.description;

        let thumbnail = document.getElementById('video-thumbnail');
        if (thumbnail && data.metadata.thumbnail_filename) {
            thumbnail.classList.remove('hidden');
            thumbnail.src = '/storage/images/jpeg/' + data.metadata.thumbnail_filename;
        }

        const metadataAttributes = document.getElementById('metadata-attributes');
        if (metadataAttributes) {
            let row;
            const filteredAttributes = [
                'thumbnail_filepath',
                'thumbnail_filename',
                'preview_filename',
                'preview_filepath'
            ];

            row = document.createElement('tr');
            row.innerHTML = `<td class="whitespace-nowrap">Filename</td>
                             <td class="whitespace-nowrap">
                                 <a href="/watch/${data.id}" class="text-blue-400">${data.filename}</a>
                             </td>`;

            metadataAttributes.appendChild(row);

            row = document.createElement('tr');
            let filepath = video.formatFilePath(data.filepath);
            row.innerHTML = `<td class="whitespace-nowrap">Filepath</td>
                             <td class="whitespace-nowrap">${filepath}</td>`;

            metadataAttributes.appendChild(row);

            for (let [key, value] of Object.entries(data.metadata)) {
                if (!filteredAttributes.includes(key)) {
                    if (key === 'video_id') key = 'ID';
                    if (key === 'filesize') value = video.formatFileSize(value);
                    key = key.charAt(0).toUpperCase() + key.slice(1);

                    row = document.createElement('tr');
                    row.innerHTML = `<td class="whitespace-nowrap">${key}</td>
                                     <td class="whitespace-nowrap">${value}</td>`;

                    metadataAttributes.appendChild(row);
                }
            }

        }
    }

    fetch(route, {
        headers: headers
    })
        .then(handleResponse)
        .then(handleData)
        .catch(e => console.log(e));
}

video.update = (route, refs) => {
    let data = {
        title: refs.selectedVideoTitle.value,
        producer: refs.selectedVideoProducer.value,
        genre: refs.selectedVideoGenre.value,
        description: refs.selectedVideoDescription.value
    };

    headers = Object.assign({}, headers, {
        "X-CSRF-Token": document.querySelector('input[name="_token"]').value
    });

    const handleResponse = response => {
        if (!response.ok && response.status !== 422) {
            throw new Error(response.statusText);
        }

        return response.json();
    }

    const handleData = data => {
        if (data.errors) {
            Object.keys(data.errors).forEach(field => {
                let elem = document.querySelector('#'+field+'-error');
                elem.classList.remove('hidden');
                elem.innerText = data.errors[field][0];
            });
        } else {
            notify.send('success', 'Video information successfully updated.', 'top');
        }
    }

    fetch(route, {
        headers: headers,
        method: 'PUT',
        body: JSON.stringify(data)
    })
        .then(handleResponse)
        .then(handleData)
        .catch(e => console.log(e));
}

video.preview = el => {
    // Lazy load previews
    let thumbnail = el.querySelector('img');
    if (thumbnail) {
        thumbnail.classList.add('hidden');
        let preview = el.querySelector('video');
        if (preview) {
            preview.classList.remove('hidden');
        } else {
            preview = document.createElement('video');
            preview.classList.add('media');
            preview.src = thumbnail.src.replace("/images/jpeg", "/previews").replace(".jpg", "");
            preview.loop = true;
            preview.muted = true;

            el.appendChild(preview, thumbnail);
        }

        let isPlaying = preview.currentTime > 0 && !preview.paused && !preview.ended
            && preview.readyState > preview.HAVE_CURRENT_DATA;

        if (!isPlaying) {
            preview.play().then().catch(()=>{});
        }
    }
}

video.unpreview = el => {
    let preview = el.querySelector('video');
    if (preview) {
        preview.pause();
        preview.currentTime = 0;
        preview.classList.add('hidden');

        let thumbnail = el.querySelector('img');
        if (thumbnail) {
            thumbnail.classList.remove('hidden');
        }
    }
}

video.resetFormFields = () => {
    document.querySelectorAll('.error').forEach(error => error.classList.add('hidden'));
    document.getElementById('metadata-attributes').innerHTML = null;
}

video.formatFilePath = path => {
    let filepath = path.split("/");
    filepath.pop();

    return filepath.join("/");
}

video.formatFileSize = (bytes, decimals = 2) => {
    if (bytes === 0) return '0 Bytes';

    const k = 1024;
    const dm = decimals < 0 ? 0 : decimals;
    const sizes = ['Bytes', 'KB', 'MB', 'GB', 'TB', 'PB', 'EB', 'ZB', 'YB'];

    const i = Math.floor(Math.log(bytes) / Math.log(k));

    return parseFloat((bytes / Math.pow(k, i)).toFixed(dm)) + ' ' + sizes[i];
}

window._video = video;
