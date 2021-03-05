# Getting Started


1. Copy .env.example to .env and make sure you have the following custom env atttributes set:

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
