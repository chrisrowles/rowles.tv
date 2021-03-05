/******/ (() => { // webpackBootstrap
var __webpack_exports__ = {};
/*!*******************************!*\
  !*** ./resources/js/video.js ***!
  \*******************************/
function _slicedToArray(arr, i) { return _arrayWithHoles(arr) || _iterableToArrayLimit(arr, i) || _unsupportedIterableToArray(arr, i) || _nonIterableRest(); }

function _nonIterableRest() { throw new TypeError("Invalid attempt to destructure non-iterable instance.\nIn order to be iterable, non-array objects must have a [Symbol.iterator]() method."); }

function _unsupportedIterableToArray(o, minLen) { if (!o) return; if (typeof o === "string") return _arrayLikeToArray(o, minLen); var n = Object.prototype.toString.call(o).slice(8, -1); if (n === "Object" && o.constructor) n = o.constructor.name; if (n === "Map" || n === "Set") return Array.from(o); if (n === "Arguments" || /^(?:Ui|I)nt(?:8|16|32)(?:Clamped)?Array$/.test(n)) return _arrayLikeToArray(o, minLen); }

function _arrayLikeToArray(arr, len) { if (len == null || len > arr.length) len = arr.length; for (var i = 0, arr2 = new Array(len); i < len; i++) { arr2[i] = arr[i]; } return arr2; }

function _iterableToArrayLimit(arr, i) { if (typeof Symbol === "undefined" || !(Symbol.iterator in Object(arr))) return; var _arr = []; var _n = true; var _d = false; var _e = undefined; try { for (var _i = arr[Symbol.iterator](), _s; !(_n = (_s = _i.next()).done); _n = true) { _arr.push(_s.value); if (i && _arr.length === i) break; } } catch (err) { _d = true; _e = err; } finally { try { if (!_n && _i["return"] != null) _i["return"](); } finally { if (_d) throw _e; } } return _arr; }

function _arrayWithHoles(arr) { if (Array.isArray(arr)) return arr; }

var video = {};
var headers = {
  "Content-Type": "application/json",
  "Accept": "application/json",
  "X-Requested-With": "XMLHttpRequest"
};

video.setup = function (path) {
  jwplayer('video-container').setup({
    'playlist': [{
      'file': path
    }]
  });
};

video.get = function (route, refs) {
  video.resetFormFields();

  var handleResponse = function handleResponse(response) {
    if (!response.ok) {
      throw new Error(response.statusText);
    }

    return response.json();
  };

  var handleData = function handleData(data) {
    refs.selectedVideoTitle.value = data.title;
    refs.selectedVideoProducer.value = data.producer;
    refs.selectedVideoGenre.value = data.genre;
    refs.selectedVideoDescription.value = data.description;
    var thumbnail = document.getElementById('video-thumbnail');

    if (thumbnail && data.metadata.thumbnail_filename) {
      thumbnail.src = '/storage/images/jpeg/' + data.metadata.thumbnail_filename;
    }

    var metadataAttributes = document.getElementById('metadata-attributes');

    if (metadataAttributes) {
      var row;
      var filteredAttributes = ['thumbnail_filepath', 'thumbnail_filename', 'preview_filename', 'preview_filepath'];
      row = document.createElement('tr');
      row.innerHTML = "<td class=\"whitespace-nowrap\">Filename</td>\n                             <td class=\"whitespace-nowrap\">\n                                 <a href=\"/watch/".concat(data.id, "\" class=\"text-blue-400\">").concat(data.filename, "</a>\n                             </td>");
      metadataAttributes.appendChild(row);
      row = document.createElement('tr');
      var filepath = video.formatFilePath(data.filepath);
      row.innerHTML = "<td class=\"whitespace-nowrap\">Filepath</td>\n                             <td class=\"whitespace-nowrap\">".concat(filepath, "</td>");
      metadataAttributes.appendChild(row);

      for (var _i = 0, _Object$entries = Object.entries(data.metadata); _i < _Object$entries.length; _i++) {
        var _Object$entries$_i = _slicedToArray(_Object$entries[_i], 2),
            key = _Object$entries$_i[0],
            value = _Object$entries$_i[1];

        if (!filteredAttributes.includes(key)) {
          if (key === 'video_id') key = 'ID';
          if (key === 'filesize') value = video.formatFileSize(value);
          key = key.charAt(0).toUpperCase() + key.slice(1);
          row = document.createElement('tr');
          row.innerHTML = "<td class=\"whitespace-nowrap\">".concat(key, "</td>\n                                     <td class=\"whitespace-nowrap\">").concat(value, "</td>");
          metadataAttributes.appendChild(row);
        }
      }
    }
  };

  fetch(route, {
    headers: headers
  }).then(handleResponse).then(handleData)["catch"](function (e) {
    return console.log(e);
  });
};

video.update = function (route, refs) {
  video.resetFormFields();
  var data = {
    title: refs.selectedVideoTitle.value,
    producer: refs.selectedVideoProducer.value,
    genre: refs.selectedVideoGenre.value,
    description: refs.selectedVideoDescription.value
  };
  headers = Object.assign({}, headers, {
    "X-CSRF-Token": document.querySelector('input[name="_token"]').value
  });

  var handleResponse = function handleResponse(response) {
    if (!response.ok) {
      if (response.status === 422) {
        // Validation error
        return response.json();
      } else {
        throw new Error(response.statusText);
      }
    }

    return response.json();
  };

  var handleData = function handleData(data) {
    if (data.errors) {
      Object.keys(data.errors).forEach(function (field) {
        var elem = document.querySelector('#' + field + '-error');
        elem.classList.remove('hidden');
        elem.innerText = data.errors[field][0];
      });
    } else {
      alert("Data successfully updated.");
    }
  };

  fetch(route, {
    headers: headers,
    method: 'PUT',
    body: JSON.stringify(data)
  }).then(handleResponse).then(handleData)["catch"](function (e) {
    return console.log(e);
  });
};

video.preview = function (el) {
  el.src = el.src.replace("/images/jpeg/", "/images/gif/").replace(".jpg", ".gif");
};

video.unpreview = function (el) {
  el.src = el.src.replace("/images/gif/", "/images/jpeg/").replace(".gif", ".jpg");
};

video.resetFormFields = function () {
  document.querySelectorAll('.error').forEach(function (error) {
    return error.classList.add('hidden');
  });
  document.getElementById('metadata-attributes').innerHTML = null;
};

video.formatFilePath = function (path) {
  var filepath = path.split("/");
  filepath.pop();
  return filepath.join("/");
};

video.formatFileSize = function (bytes) {
  var decimals = arguments.length > 1 && arguments[1] !== undefined ? arguments[1] : 2;
  if (bytes === 0) return '0 Bytes';
  var k = 1024;
  var dm = decimals < 0 ? 0 : decimals;
  var sizes = ['Bytes', 'KB', 'MB', 'GB', 'TB', 'PB', 'EB', 'ZB', 'YB'];
  var i = Math.floor(Math.log(bytes) / Math.log(k));
  return parseFloat((bytes / Math.pow(k, i)).toFixed(dm)) + ' ' + sizes[i];
};

window._video = video;
/******/ })()
;