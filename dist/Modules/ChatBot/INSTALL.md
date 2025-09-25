# ChatBot Module Installation Guide

## Quick Setup

### 1. Environment Configuration

Add these variables to your `.env` file:

```env
# OpenAI Configuration
OPENAI_API_KEY=sk-your-api-key-here
OPENAI_MODEL=gpt-3.5-turbo
OPENAI_MAX_TOKENS=1000
OPENAI_TEMPERATURE=0.7

# ChatBot Configuration
CHATBOT_ENABLED=true
```

### 2. Run Migrations

```bash
php artisan migrate
```

### 3. Set Permissions

Add these permissions to your admin panel:

- `chatbot.view` - View chatbot
- `chatbot.create` - Create conversations  
- `chatbot.edit` - Edit conversations
- `chatbot.delete` - Delete conversations
- `chatbot.admin` - Manage chatbot settings

### 4. Test Installation

```bash
# Test the chatbot
php artisan chatbot:test --interactive

# Check system health
php artisan chatbot:test
```

## Access URLs

- **Chat Interface**: `/chatbot`
- **Admin Dashboard**: `/chatbot/admin`
- **Settings**: `/chatbot/admin/settings`

## API Testing

Test the API endpoints:

```bash
# Send a message
curl -X POST /api/v1/chatbot/send \
  -H "Content-Type: application/json" \
  -d '{"message": "Hello"}'

# Get statistics
curl /api/v1/chatbot/statistics
```

## Troubleshooting

### OpenAI Connection Issues
1. Verify API key is correct
2. Check internet connectivity
3. Ensure OpenAI account has credits

### Database Issues
1. Run `php artisan migrate:fresh`
2. Check database permissions
3. Verify table creation

### Permission Issues
1. Ensure user has required permissions
2. Check authentication middleware
3. Verify route protection

## Next Steps

1. Configure OpenAI settings in admin panel
2. Customize welcome message
3. Set up rate limiting
4. Test with sample conversations
5. Train with custom data if needed 