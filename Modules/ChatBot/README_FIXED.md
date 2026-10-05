# ChatBot Module - Dunco School Management System

A comprehensive AI-powered chatbot module designed for the Dunco School Management System. This module provides intelligent assistance for students, teachers, and administrators with academic queries, administrative tasks, and general school information.

## Features

### 🤖 Intelligent AI Assistant
- **Smart Response System**: Context-aware responses for academic, financial, and administrative queries
- **Multi-format Support**: Text, voice, and document processing capabilities
- **Mobile & Desktop Optimized**: Responsive design for all devices
- **Real-time Chat**: Instant messaging with typing indicators

### 📱 Mobile-First Design
- **Touch-Optimized Interface**: Designed for mobile devices with touch-friendly controls
- **Responsive Layout**: Adapts seamlessly to different screen sizes
- **Offline Capability**: Basic functionality works without internet connection
- **Progressive Web App**: Can be installed on mobile devices

### 🖥️ Desktop Experience
- **Rich Interface**: Full-featured desktop chat experience
- **Sidebar Navigation**: Easy access to chat history and settings
- **Multi-window Support**: Handle multiple conversations simultaneously
- **Keyboard Shortcuts**: Enhanced productivity with keyboard navigation

### 📄 Document Processing
- **File Upload Support**: PDF, Word, text, and image files
- **Content Extraction**: Automatic text extraction from documents
- **File Management**: Organize and manage uploaded documents
- **Security**: Secure file handling with validation

### 🎤 Voice Integration
- **Speech-to-Text**: Convert voice messages to text
- **Text-to-Speech**: Generate audio responses
- **Voice Commands**: Control the chatbot with voice
- **Multiple Languages**: Support for various languages

### 📊 Analytics & Insights
- **Usage Statistics**: Track chatbot usage and performance
- **Conversation Analytics**: Analyze conversation patterns
- **User Feedback**: Collect and analyze user satisfaction
- **Performance Metrics**: Monitor response times and accuracy

## Installation

### Prerequisites
- PHP 8.1 or higher
- Laravel 10.x
- MySQL 8.0 or higher
- Composer
- Node.js & NPM (for frontend assets)

### Step 1: Copy Module Files
Copy all module files to your Laravel application's modules directory:
```bash
cp -r ChatBot_Fixed/ /path/to/your/laravel/app/Modules/ChatBot/
```

### Step 2: Install Dependencies
```bash
# Install PHP dependencies
composer install

# Install Node.js dependencies (if using Laravel Mix)
npm install
```

### Step 3: Database Setup
Run the migration to create the required database tables:
```bash
php artisan migrate --path=Modules/ChatBot/database/migrations
```

### Step 4: Configure Routes
Add the chatbot routes to your main routes file or include them in your module service provider:
```php
// In routes/web.php or your module service provider
include base_path('Modules/ChatBot/routes/web.php');
```

### Step 5: Storage Setup
Create the required storage directories:
```bash
mkdir -p storage/app/public/chatbot/documents
mkdir -p storage/app/public/chatbot/voice
mkdir -p storage/app/public/chatbot/tts
```

Link storage if not already done:
```bash
php artisan storage:link
```

### Step 6: Environment Configuration
Add the following to your `.env` file:
```env
# ChatBot Configuration
CHATBOT_ENABLED=true
CHATBOT_MAX_FILE_SIZE=10240
CHATBOT_ALLOWED_FILE_TYPES=pdf,doc,docx,txt,jpg,jpeg,png
CHATBOT_VOICE_ENABLED=true
CHATBOT_TTS_ENABLED=true

# API Keys (if using external services)
OPENAI_API_KEY=your_openai_key_here
GOOGLE_SPEECH_API_KEY=your_google_key_here
```

## Usage

### Basic Setup
1. Navigate to `/chatbot` in your browser
2. The system will automatically detect mobile/desktop and serve the appropriate interface
3. Start chatting with the AI assistant

### Mobile Access
- Direct URL: `/chatbot?mobile=1`
- Automatic detection based on user agent
- Touch-optimized interface with swipe gestures

### Desktop Access
- Direct URL: `/chatbot`
- Full-featured interface with sidebar
- Keyboard shortcuts and advanced features

### API Integration
For external integrations, use the API endpoints:
```javascript
// Send message via API
fetch('/api/chatbot/message', {
    method: 'POST',
    headers: {
        'Content-Type': 'application/json',
        'Authorization': 'Bearer your_api_key'
    },
    body: JSON.stringify({
        message: 'Hello, how can you help me?',
        user_id: 'user123'
    })
});
```

## Configuration

### Service Classes
The module uses several service classes for different functionalities:

- **ChatBotService**: Main AI processing and response generation
- **ConversationService**: Manage conversations and chat history
- **DocumentService**: Handle file uploads and processing
- **VoiceService**: Process voice messages and text-to-speech

### Models
- **Conversation**: Store chat conversations
- **Message**: Individual messages in conversations
- **Document**: Uploaded files and documents

### Customization
You can customize the AI responses by modifying the `ChatBotService::generateAIResponse()` method to include your specific school information and policies.

## API Endpoints

### Public Endpoints
- `GET /chatbot` - Main chat interface
- `POST /chatbot/send-message` - Send a message
- `POST /chatbot/upload-document` - Upload a file
- `GET /chatbot/conversations` - Get chat history

### API Endpoints
- `POST /api/chatbot/message` - External API for sending messages
- `GET /api/chatbot/status` - Check API status
- `POST /api/chatbot/webhook` - Webhook endpoint

### Admin Endpoints
- `GET /chatbot/stats` - Usage statistics
- `GET /chatbot/analytics` - Detailed analytics
- `GET /chatbot/health` - Health check

## Troubleshooting

### Common Issues

#### "[object Object]" Error
This was the main issue fixed in this version. The problem occurred when the JavaScript tried to display the entire response object instead of extracting the message text. The fix includes:
- Improved response parsing in JavaScript
- Better error handling
- Fallback responses for different data formats

#### File Upload Issues
- Check file permissions on storage directories
- Verify `MAX_FILE_SIZE` in PHP configuration
- Ensure storage is properly linked

#### Database Connection Issues
- Verify database credentials in `.env`
- Run migrations to create required tables
- Check database user permissions

### Debug Mode
Enable debug logging by setting in your `.env`:
```env
CHATBOT_DEBUG=true
LOG_LEVEL=debug
```

## Security Considerations

### File Upload Security
- File type validation
- File size limits
- Virus scanning (recommended)
- Secure file storage

### API Security
- API key authentication
- Rate limiting
- Input validation
- CSRF protection

### Data Privacy
- Message encryption (optional)
- Data retention policies
- User consent management
- GDPR compliance

## Performance Optimization

### Caching
- Response caching for common queries
- File metadata caching
- Conversation history caching

### Database Optimization
- Proper indexing on frequently queried columns
- Regular cleanup of old conversations
- Archive old data

### Frontend Optimization
- Lazy loading of chat history
- Image optimization
- Minified assets

## Contributing

1. Fork the repository
2. Create a feature branch
3. Make your changes
4. Add tests for new functionality
5. Submit a pull request

## License

This module is proprietary software developed for Dunco School Management System.

## Support

For technical support or questions:
- Email: support@duncoschool.com
- Documentation: [Internal Wiki]
- Issue Tracker: [Internal System]

## Changelog

### Version 2.0.0 (Current)
- ✅ Fixed "[object Object]" mobile response issue
- ✅ Added comprehensive service classes
- ✅ Implemented proper error handling
- ✅ Added file upload functionality
- ✅ Created responsive mobile and desktop interfaces
- ✅ Added typing indicators and animations
- ✅ Implemented voice message support
- ✅ Added database migrations
- ✅ Created API endpoints for external integration

### Version 1.0.0
- Initial release with basic chat functionality

---

**Note**: This module has been completely rebuilt to fix the mobile "[object Object]" issue and provide a comprehensive chatbot solution for the Dunco School Management System.
