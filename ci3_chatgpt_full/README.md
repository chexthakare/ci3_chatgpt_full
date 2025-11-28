# CI3 ChatGPT Full

A CodeIgniter 3 web application that integrates multiple AI providers (OpenAI, Google Gemini, and Local AI) to provide a chatbot interface for users.

## Features

- **Multi-AI Provider Support**: Switch between OpenAI, Google Gemini, and Local AI models
- **Chat Interface**: Interactive web-based chat interface
- **Text Summarization**: Summarize long texts using AI
- **Easy API Integration**: Simple integration with popular AI APIs
- **CodeIgniter 3 Framework**: Built on the robust CodeIgniter 3 framework
- **MVC Architecture**: Clean separation of concerns with Models, Views, and Controllers

## Prerequisites

Before you begin, ensure you have the following installed:

- **PHP** version 5.6 or newer (recommended 7.0+)
- **Composer** for PHP dependency management
- **Apache** or any PHP-compatible web server
- **MySQL** (optional, for database support)
- **Git** for cloning the repository

### Required API Keys

Depending on which AI providers you plan to use:

- **OpenAI**: Get your API key from [OpenAI Platform](https://platform.openai.com/api-keys)
- **Google Gemini**: Get your API key from [Google AI Studio](https://aistudio.google.com/app/apikey)
- **Local AI**: Configure a local AI server (optional)

## Installation

### Step 1: Clone the Repository

```bash
git clone https://github.com/chexthakare/ci3_chatgpt_full.git
cd ci3_chatgpt_full
```

### Step 2: Install Dependencies

```bash
composer install
```

### Step 3: Configure Your Environment

1. Navigate to `application/config/config.php` and update the base URL:

```php
$config['base_url'] = 'http://your-domain.com/ci3_chatgpt_full/';
```

2. Update `application/config/database.php` if you need database support:

```php
$db['default'] = array(
    'dsn'   => '',
    'hostname' => 'localhost',
    'username' => 'your_db_user',
    'password' => 'your_db_password',
    'database' => 'your_database',
    'dbdriver' => 'mysqli',
    // ... other settings
);
```

### Step 4: Add Your API Keys

1. Open `application/config/config.php`
2. Add your API keys as constants:

```php
// OpenAI Configuration
define('OpenAIAPIKey', 'your-openai-api-key-here');

// Google Gemini Configuration
define('GeminiAPIKey', 'your-gemini-api-key-here');

// Local AI Configuration (if using)
define('LocalAIEndpoint', 'http://localhost:8000/api');
```

### Step 5: Set File Permissions

Ensure the `application/logs` directory is writable:

```bash
chmod -R 755 application/logs/
chmod -R 755 application/cache/
```

### Step 6: Access the Application

Open your browser and navigate to:

```
http://localhost/ci3_chatgpt_full/
```

## Project Structure

```
ci3_chatgpt_full/
├── application/
│   ├── config/          # Configuration files (database, routes, etc.)
│   ├── controllers/
│   │   ├── Chat.php     # Main chat controller
│   │   └── Welcome.php
│   ├── models/
│   │   ├── OpenAI_model.php
│   │   ├── AI/
│   │   │   ├── AI_model_interface.php
│   │   │   ├── Gemini_model.php
│   │   │   ├── LocalAI_model.php
│   │   │   └── OpenAI_model.php
│   ├── views/
│   │   ├── chat_view.php       # Main chat interface
│   │   └── welcome_message.php
│   ├── cache/           # Cache directory
│   ├── logs/            # Log files
│   └── ...
├── system/              # CodeIgniter core files
├── index.php            # Application entry point
├── composer.json        # PHP dependencies
└── README.md            # This file
```

## Usage

### Accessing the Chat Interface

1. Navigate to the application URL
2. Enter your message in the input field
3. Select the AI provider from the dropdown (OpenAI, Gemini, or Local AI)
4. Click "Send" or press Enter
5. The AI response will appear in the chat window

### API Endpoints

#### Chat Endpoint
- **URL**: `/index.php/chat/ask`
- **Method**: POST
- **Parameters**:
  - `message` (required): The user's message
  - `provider` (optional): AI provider ('openai', 'gemini', 'localai'). Default: 'openai'

#### Summarize Endpoint
- **URL**: `/index.php/chat/summarize`
- **Method**: POST
- **Parameters**:
  - `text` (required): Text to summarize
  - `provider` (optional): AI provider. Default: 'openai'

## Configuration

### Supported AI Providers

#### 1. OpenAI
- Model: `gpt-4o-mini`
- Requires: OpenAI API key

#### 2. Google Gemini
- Requires: Google Gemini API key

#### 3. Local AI
- Run a local AI server
- Configure endpoint in config

### Customizing Models

Edit `application/models/AI/` files to change:
- AI model versions
- API endpoints
- Request/response handling

## Database Setup (Optional)

If you need to store chat history:

```bash
# Navigate to application/migrations/ and create migration files
# Or manually create tables as needed
```

## Troubleshooting

### Issue: "API Key not found"
- Verify your API keys are correctly set in `application/config/config.php`
- Ensure constants are defined before they're used

### Issue: "CORS errors"
- Configure CORS headers in your `.htaccess` or server config
- Check `application/config/config.php` for correct base URL

### Issue: "Cannot connect to AI provider"
- Check your internet connection
- Verify API keys are valid
- Check API quota/rate limits
- Review logs in `application/logs/`

### Issue: "Permission denied" errors
- Ensure proper file permissions: `chmod -R 755 application/logs/` and `application/cache/`

## Learning Resources

- [CodeIgniter 3 Documentation](https://codeigniter.com/docs)
- [OpenAI API Documentation](https://platform.openai.com/docs/api-reference)
- [Google Gemini API Documentation](https://ai.google.dev/docs)

## Contributing

Contributions are welcome! To contribute:

1. Fork the repository
2. Create a feature branch (`git checkout -b feature/AmazingFeature`)
3. Commit your changes (`git commit -m 'Add some AmazingFeature'`)
4. Push to the branch (`git push origin feature/AmazingFeature`)
5. Open a Pull Request

## License

This project is licensed under the MIT License - see the [LICENSE](LICENSE) file for details.

## Security Notes

- **Never commit API keys** to your repository. Use environment variables or `.env` files
- Keep API keys in a `.env` file (add to `.gitignore`)
- Validate and sanitize all user inputs
- Use HTTPS in production
- Keep CodeIgniter and dependencies updated
- Regularly review logs for suspicious activity

## Support

For issues and questions:

1. Check the [Troubleshooting](#troubleshooting) section
2. Search existing GitHub issues
3. Create a new issue with detailed information
4. Include error logs and steps to reproduce

## Credits

- Built with [CodeIgniter 3](https://codeigniter.com/)
- Powered by AI providers:
  - [OpenAI](https://openai.com/)
  - [Google Gemini](https://deepmind.google/technologies/gemini/)

## Contact

For questions or suggestions, feel free to reach out via:
- GitHub Issues
- Email: your-email@example.com

---

**Enjoy building with CI3 ChatGPT Full!**
