

## Table of Contents

* [Getting Started](#getting-started)
* [Processing](#processing)
  * [Transcoding](#transcoding)
    * [Clip](#clip)
    * [Resize](#resize)
    * [Bulk Processing](#bulk-processing)

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
php artisan vid:transcode /path/to/video.mp4 --ext=mkv --clip --from=40 --to=10
```

Output:

```
[info] transcoding /path/to/video.mp4 to mkv
[info] clip at 00:00:40 for 5 seconds
[info] 0% complete
[info] 15% complete
...
```

#### Resize

If you want to resize your video, pass the following options:
```
-r, --resize         Enable resize
  --width[=WIDTH]    Resize width
  --height[=HEIGHT]  Resize height
```

For example:

```sh
php artisan vid:transcode /path/to/video.mp4 --ext=mkv --resize --width=350 --height=200
```

Output:

```
[info] transcoding /path/to/video.mp4 to mkv
[info] resize to 350x200
[info] 0% complete
[info] 15% complete
...
```

#### Bulk Processing
If you want to bulk process a directory of videos (_note: this will scan your folder and all sub-folders recursively._)

```sh
php artisan vid:transcode --bulk /path/to/videos --ext=mkv
```

Output:

```
[info] 421 videos to transcode
[info] transcoding /path/to/videos/video1.mp4 to mkv
[info] 0% complete
[info] 15% complete
...
```
