# Demo: TTS-1, Whisper, and GPT-3.5-Turbo Chat

This is a simple demo showcasing the integration of Text-to-Speech (TTS-1), Whisper, and GPT-3.5-Turbo models for conversational purposes.

## Usage

### Modifying Models
To change the models used, navigate to the `inc.php` file and modify the `chat` function.

```php
// Modify the chat function in inc.php
function chat($input) {
    // Your custom logic to interact with different models
    // ...
    return $output;
}
```

### Frontend Frameworks
The frontend is built using `Bootstrap`, `Vue 2`, and the `Recorder` library.

### Uploading Audio Files
Users can upload audio files in the `mp3` format to the PHP backend. The backend then utilizes the Text-to-Speech (TTS) functionality to generate `opus` audio files, ensuring efficiency in the process.

### Cleaning Cache Files
For maintenance purposes, you can access `clean.php` to clean up cache files and manage the storage efficiently.

## How to Run

1. Clone the repository.
2. Configure the models in `inc.php`.
3. Set up a PHP server.
4. Access the application through your browser.

Feel free to explore and experiment with different models and functionalities!

## Technologies Used

- Backend: PHP
- Frontend: Bootstrap, Vue 2, Recorder library

## Disclaimer

This is a basic demonstration and may require additional configurations for production use. Make sure to review and adhere to the usage guidelines of the models involved.

Happy chatting!