The software behind Yuuki OP/ED.
This is a work-in-progress.

Current framework in use :
- Laravel 5.5 as base
- lakshmaji/Thumbnail + ffmpeg for video thumbnail
- Bootstrap for most of the design (Change to Material Design in the future)
- Twitter Typeahead + Bloodhound for search function
- jQuery for menu

Also, the backend you may need: 
- PHP 7.x, should works with 5.6.x but this isn't developed on 5.6.x
- MariaDB or MySQL
- Apache with php module, or NGINX with fastcgi/fpm
- Storage for your video

The video codec should be h264 or VP9, and OPUS or AAC audio with mp4/webm container.
Video codec settings should use standard settings encoding specifications, so it can be played at most web browser.
For optimal quality streams, video bitrate should be around ~3000kbps (CRF 19-23 at medium preset for h264) for a 720p video.
There is NO transcoding done in the server.

Most main functionality is done, but need some code cleanup.
