# Project Introduction

This project is a simple demo that utilizes TTS-1, Whisper, and GPT-3.5-turbo for conversational interactions.

## Technology Stack

- The front end is built using `bootstarp`, `vue2`, and `recorder`.
- The front end supports selecting browser-based speech-to-text (low latency) or calling OpenAI's Whisper (high accuracy).
- Users can upload `mp3` format files to PHP and obtain `opus` audio through TTS to ensure efficiency.
- Accessing `clean.php` allows for the clearing of cache files.

## Modifications and Updates

- Removed Composer dependencies, and instead integrated a modified version of the [OpenAI library](https://github.com/orhanerday/open-ai), incorporating functions related to TTS.
- Now utilizes a custom simple function to retrieve `.env` variables.

# Deployment Guide

For optimal performance, it is recommended to use PHP 8.1.

1. Rename `.env.example` to `.env` and configure the OpenAI Key and URL inside it (modify the URL if a proxy is required).
