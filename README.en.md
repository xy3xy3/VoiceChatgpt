A simple demo using tts-1, whisper, and chatting with gpt3.5-turbo!

To modify the model, you can edit the `chat` function in `inc.php`.

The frontend is built using `bootstrap`, `vue2`, and `recorder`.

The frontend uploads `mp3` format files to PHP, which then calls `tts` to obtain `opus` audio to ensure efficiency.

Access `clean.php` to clean up cache files.

It is necessary to enable the `putenv` function.

# Deployment Guide
PHP 8.1 is recommended.
Rename `.env.example` to `.env` and configure the openaikey and url inside (modify the url if using a proxy).
