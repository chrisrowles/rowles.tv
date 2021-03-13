

## Table of Contents

* [Getting Started](#getting-started)
* [Processing](#processing)
  * [Transcoding](#transcoding)
    * [Clip](#clip)
    * [Resize](#resize)
    * [Bulk Processing](#bulk-processing)
* [Development Best Practices](#development-best-practices)
  * [Routing and Authorization](#routing-and-authorization)
  * [Processing Tasks](#processing-tasks)
  * [Frontend](#frontend)
  * [Project Board](https://trello.com/b/fM03Ql2z/project-board)

# Getting Started

1. Copy `.env.example` to `.env` and make sure you have the following atttributes set:

```dotenv
FFPROBE_BINARY=/usr/bin/ffprobe
FFMPEG_BINARY=/usr/bin/ffmpeg
FFMPEG_THREADS=12
FFMPEG_TIMEOUT=300

IMAGE_STORAGE_SOURCE=/mnt/d/SteamBackups
IMAGE_STORAGE_DESTINATION=/home/chris/workspace/ffmpeg2/storage/app/public/images
PREVIEW_STORAGE_SOURCE=/mnt/d/SteamBackups
PREVIEW_STORAGE_DESTINATION=/home/chris/workspace/ffmpeg2/storage/app/public/previews
VIDEO_STORAGE_SOURCE=/mnt/d/SteamBackups/videos/PooAlexa
VIDEO_STORAGE_DESTINATION=/home/chris/workspace/ffmpeg2/storage/app/public/videos
```

# Processing

## Transcoding

```
Description:
  Transcode videos

Usage:
  vid:transcode [options] [--] [<name>]

Arguments:
  name                                               Video filename (null)

Options:
  -b, --bulk                                         Bulk processing (false)
  -c, --clip                                         Enable clip (false)
      --from[=FROM]                                  Clip from seconds (40)
      --to[=TO]                                      Clip to seconds (5)
  -r, --resize                                       Enable resize (false)
      --width[=WIDTH]                                Resize width (500)
      --height[=HEIGHT]                              Resize height (auto)
  -e, --ext[=EXT]                                    File format (initial)
      --bitrate[=BITRATE]                            Kilo bitrate (1000)
      --audio-bitrate[=AUDIO-BITRATE]                Audio bitrate (256)
      --audio-channels[=AUDIO-CHANNELS]              Audio channels (2)
      --constant-rate-factor[=CONSTANT-RATE-FACTOR]  Constant rate factor (24)
      --is-preview                                   Save to previews directory
  -h, --help                                         Display help for the given command. When no command is given display help for the list command
```

For example, if you want to transcode a single video from mp4 to mkv:

```sh
php artisan vid:transcode /path/to/video.mp4 --ext=mkv
```

Output:

```
[info] transcoding path/to/video.mp4 to mkv
[info] 0% complete
[info] 15% complete
[info] 50% complete
[info] 50% complete
[info] 63% complete
[success] 100% complete
```

### Clip

If you want to clip your video, pass the following options:

```
-c, --clip      Enable clip
  --from[=FROM] Clip from seconds
  --to[=TO]     Clip to seconds
```

For example:

```sh
php artisan vid:transcode /path/to/video.mp4 --clip --from=40 --to=10
```

Output:

```
[info] transcoding /path/to/video.mp4 to mkv
[info] clip at 00:00:40 for 5 seconds
[info] 0% complete
[info] 15% complete
...
```

### Resize

If you want to resize your video, pass the following options:
```
-r, --resize         Enable resize
  --width[=WIDTH]    Resize width
  --height[=HEIGHT]  Resize height
```

For example:

```sh
php artisan vid:transcode /path/to/video.mp4 --resize --width=350 --height=200
```

Output:

```
[info] transcoding /path/to/video.mp4 to mkv
[info] resize to 350x200
[info] 0% complete
[info] 15% complete
...
```

### Bulk Processing
If you want to bulk process a directory of videos (_note: this will scan your folder and all sub-folders recursively._)

```sh
php artisan vid:transcode --bulk /path/to/videos --resize --width=1080 --height=auto
```

Output:

```
[info] 20 videos to transcode
[info] transcoding /path/to/videos/video1.mp4 to mkv
[info] 0% complete
[info] 15% complete
...
```

# Development Best Practices

## Routing and Authorization
- When building new admin features, don't forget to wrap routes inside `administrator` middleware, and if adding navigation links, don't forget to wrap those inside `@administrator` directives
- When building new subscribed member features, don't forget to wrap routes inside `subscribed` middleware

## Processing Tasks
- Processor classes should implement ProcessingTaskInterface
- Processor classes should be callable from both the web application, and the artisan console, with error handling included for both (view existing processor class for an example)
- Service container bindings should be registered for Processor classes inside ProcessorServiceProvider, following the same abstract naming convention

## Frontend
- Reusable components should be created as alpine.js components in `views/components`
- Reusable partial views should be created as blade templates in `views/partials` and should use alpine.js components where able.
- Don't add too many tailwind classes to elements, instead think about either creating a reusable alpine.js component with the ability to override classes or create a new class that applies the other classes

## Project Board

https://trello.com/b/fM03Ql2z/


### Notes

#### Assets location
assets are pulled from either local or S3 cloud storage depending on the value of the following environment variables:

```
CLOUDASSET_IMAGE_URL
CLOUDASSET_PREVIEW_URL
CLOUDASSET_VIDEO_URL
```

Change these values depending on whether you want to test using local assets or cloud assets, cloud assets should typically only need to be used when testing before deployment.
