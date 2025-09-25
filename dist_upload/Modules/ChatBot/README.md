# ChatBot Module

A comprehensive AI-powered chatbot module for the school management system, powered by OpenAI's GPT models.

## Features

### 🤖 Core Functionality
- **Real-time Chat Interface**: Modern, responsive chat UI with real-time messaging
- **OpenAI Integration**: Powered by GPT-3.5 Turbo, GPT-4, and GPT-4 Turbo models
- **Conversation Management**: Persistent conversation history with session management
- **Context Awareness**: School-specific context and user role awareness
- **Rate Limiting**: Configurable rate limiting to prevent abuse
- **Error Handling**: Comprehensive error handling and fallback responses

### 🎛️ Admin Management
- **Admin Dashboard**: Real-time statistics and system health monitoring
- **Settings Management**: Comprehensive configuration panel for all chatbot settings
- **OpenAI Configuration**: API key management, model selection, and parameter tuning
- **Usage Statistics**: Track API usage and costs
- **Health Monitoring**: System status and connection testing

### 🔧 Advanced Features
- **Training System**: Custom training with JSON, CSV, and text files
- **Testing Tools**: Automated and interactive testing modes
- **Console Commands**: CLI tools for management and testing
- **API Endpoints**: RESTful API for integration with other systems
- **Streaming Responses**: Real-time response streaming for better UX

## Installation

### 1. Environment Setup

Add the following environment variables to your `.env` file:

```env
# OpenAI Configuration
OPENAI_API_KEY=your_openai_api_key_here
OPENAI_MODEL=gpt-3.5-turbo
OPENAI_MAX_TOKENS=1000
OPENAI_TEMPERATURE=0.7
OPENAI_ORGANIZATION=your_organization_id

# ChatBot Configuration
CHATBOT_ENABLED=true
```

### 2. Database Migration

Run the migrations to create the required database tables:

   ```bash
php artisan migrate
```

### 3. Permissions Setup

Add the chatbot permissions to your permission system:

```php
// In your permission seeder or admin panel
$permissions = [
    'chatbot.view' => 'View chatbot',
    'chatbot.create' => 'Create conversations',
    'chatbot.edit' => 'Edit conversations',
    'chatbot.delete' => 'Delete conversations',
    'chatbot.admin' => 'Manage chatbot settings',
];
```

## Usage

### Web Interface

#### User Chat Interface
- **URL**: `/chatbot`
- **Features**: Real-time chat, conversation history, file uploads
- **Access**: All authenticated users

#### Admin Dashboard
- **URL**: `/chatbot/admin`
- **Features**: Statistics, system health, quick actions
- **Access**: Users with `chatbot.admin` permission

#### Settings Management
- **URL**: `/chatbot/admin/settings`
- **Features**: OpenAI configuration, chatbot settings, feature toggles
- **Access**: Users with `chatbot.admin` permission

### API Endpoints

#### Public Endpoints
```http
POST /api/v1/chatbot/send
GET /api/v1/chatbot/conversations
GET /api/v1/chatbot/conversation/{id}
DELETE /api/v1/chatbot/conversation/{id}
POST /api/v1/chatbot/conversation/{id}/end
GET /api/v1/chatbot/statistics
GET /api/v1/chatbot/test
POST /api/v1/chatbot/stream
```

#### Admin Endpoints
```http
GET /api/v1/chatbot/admin
GET /api/v1/chatbot/admin/settings
POST /api/v1/chatbot/admin/settings
POST /api/v1/chatbot/admin/test-openai
GET /api/v1/chatbot/admin/usage
GET /api/v1/chatbot/admin/models
POST /api/v1/chatbot/admin/clear-cache
GET /api/v1/chatbot/admin/health
```

### Console Commands

#### Test ChatBot
```bash
# Interactive testing
php artisan chatbot:test --interactive

# Automated testing
php artisan chatbot:test --message="Hello" --count=10

# Quick test
php artisan chatbot:test
```

#### Train ChatBot
```bash
# Train with JSON file
php artisan chatbot:train --file=training_data.json

# Train with CSV file
php artisan chatbot:train --file=training_data.csv

# Train with custom model
php artisan chatbot:train --file=training_data.txt --model=gpt-4
```

## Configuration

### OpenAI Settings

| Setting | Default | Description |
|---------|---------|-------------|
| `OPENAI_API_KEY` | - | Your OpenAI API key |
| `OPENAI_MODEL` | `gpt-3.5-turbo` | Model to use (gpt-3.5-turbo, gpt-4, gpt-4-turbo) |
| `OPENAI_MAX_TOKENS` | `1000` | Maximum tokens per response |
| `OPENAI_TEMPERATURE` | `0.7` | Controls randomness (0-2) |

### ChatBot Settings

| Setting | Default | Description |
|---------|---------|-------------|
| `CHATBOT_ENABLED` | `true` | Enable/disable chatbot |
| `WELCOME_MESSAGE` | Custom message | Welcome message for new conversations |
| `MAX_CONVERSATION_LENGTH` | `50` | Maximum messages per conversation |
| `SESSION_TIMEOUT` | `30` | Session timeout in minutes |

### Rate Limiting

| Setting | Default | Description |
|---------|---------|-------------|
| `REQUESTS_PER_MINUTE` | `60` | Max requests per minute per user |
| `REQUESTS_PER_HOUR` | `1000` | Max requests per hour per user |

### Features

| Feature | Default | Description |
|---------|---------|-------------|
| `VOICE_INPUT` | `false` | Enable voice input |
| `VOICE_OUTPUT` | `false` | Enable voice output |
| `FILE_UPLOAD` | `true` | Enable file uploads |
| `CODE_GENERATION` | `true` | Enable code generation |

## Training Data Formats

### JSON Format
```json
{
  "conversations": [
    {
      "messages": [
        {"role": "user", "content": "What is the school schedule?"},
        {"role": "assistant", "content": "The school schedule varies by grade level..."}
      ]
    }
  ]
}
```

### CSV Format
```csv
input,output
"What is the school schedule?","The school schedule varies by grade level..."
"How do I contact a teacher?","You can contact teachers through the parent portal..."
```

### Text Format
```text
Q: What is the school schedule?
A: The school schedule varies by grade level...

Q: How do I contact a teacher?
A: You can contact teachers through the parent portal...
```

## Database Schema

### Conversations Table
```sql
CREATE TABLE chatbot_conversations (
    id BIGINT PRIMARY KEY AUTO_INCREMENT,
    user_id BIGINT NULL,
    session_id VARCHAR(255) UNIQUE,
    is_active BOOLEAN DEFAULT TRUE,
    title VARCHAR(255) NULL,
    metadata JSON NULL,
    created_at TIMESTAMP,
    updated_at TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE
);
```

### Messages Table
```sql
CREATE TABLE chatbot_messages (
    id BIGINT PRIMARY KEY AUTO_INCREMENT,
    conversation_id BIGINT,
    content TEXT,
    role ENUM('user', 'assistant', 'system') DEFAULT 'user',
    metadata JSON NULL,
    created_at TIMESTAMP,
    updated_at TIMESTAMP,
    FOREIGN KEY (conversation_id) REFERENCES chatbot_conversations(id) ON DELETE CASCADE
);
```

## Security Features

- **Rate Limiting**: Prevents abuse and controls API costs
- **Input Validation**: Sanitizes and validates all user inputs
- **Error Handling**: Graceful error handling without exposing sensitive data
- **Permission System**: Role-based access control
- **Session Management**: Secure session handling with timeouts

## Performance Optimization

- **Caching**: Intelligent caching of responses and configurations
- **Database Indexing**: Optimized database queries with proper indexing
- **Async Processing**: Non-blocking operations for better responsiveness
- **Connection Pooling**: Efficient OpenAI API connection management

## Troubleshooting

### Common Issues

1. **OpenAI Connection Failed**
   - Check your API key in `.env`
   - Verify internet connectivity
   - Check OpenAI service status

2. **Rate Limiting Errors**
   - Increase rate limits in settings
   - Check for multiple simultaneous requests
   - Monitor usage statistics

3. **Database Errors**
   - Run migrations: `php artisan migrate`
   - Check database connectivity
   - Verify table permissions

### Debug Commands

```bash
# Test OpenAI connection
php artisan chatbot:test --interactive

# Check system health
curl /chatbot/admin/health

# View logs
tail -f storage/logs/laravel.log
```

## Contributing

1. Fork the repository
2. Create a feature branch
3. Make your changes
4. Add tests for new functionality
5. Submit a pull request

## License

This module is part of the school management system and follows the same license terms.

## Support

For support and questions:
- Check the documentation
- Review the troubleshooting section
- Contact the development team
- Submit issues on the project repository 