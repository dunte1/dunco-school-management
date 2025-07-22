# Examination Module

A comprehensive examination management system for educational institutions with advanced online proctoring capabilities.

## 🚀 Features

### Core Examination Management
- **Exam Creation & Management**: Create, edit, and schedule various types of examinations
- **Question Bank System**: Comprehensive question management with multiple question types
- **Exam Scheduling**: Advanced timetable management with clash detection
- **Result Processing**: Automated grading and result generation
- **Transcript Generation**: Professional PDF transcripts with QR verification

### Online Examination Features
- **Real-time Proctoring**: Live video/audio monitoring with AI-based suspicious behavior detection
- **Multiple Question Types**: MCQ, Essay, Coding, Fill-in-the-blanks, Matching, True/False
- **Auto-grading**: Instant grading for objective questions
- **Manual Grading**: Comprehensive grading interface for subjective questions
- **Auto-save**: Automatic answer saving to prevent data loss
- **Timer Management**: Real-time countdown with warnings

### Advanced Proctoring
- **Webcam Monitoring**: Live video feed monitoring
- **Screen Recording**: Continuous screen capture
- **Tab Switch Detection**: Automatic detection of browser tab switching
- **Copy-Paste Prevention**: Block unauthorized copy-paste actions
- **Multiple Face Detection**: AI-powered detection of multiple faces
- **Voice Detection**: Audio monitoring for suspicious activities
- **Device Information**: Comprehensive device and browser tracking

### Security Features
- **Question Encryption**: Secure question storage and transmission
- **Randomization**: Question and option shuffling per student
- **Access Control**: Role-based permissions (Admin, Teacher, Student)
- **Audit Logging**: Comprehensive activity tracking
- **IP Tracking**: Device and location monitoring

### Analytics & Reporting
- **Performance Analytics**: Detailed performance analysis with charts
- **Heatmaps**: Visual representation of question difficulty
- **Export Options**: PDF, Excel, and CSV export capabilities
- **Real-time Dashboards**: Live monitoring of ongoing exams

## 📋 Requirements

- PHP 8.1+
- Laravel 10+
- MySQL 8.0+ or PostgreSQL 13+
- Node.js 16+ (for frontend assets)
- WebRTC support (for proctoring features)

## 🛠 Installation

1. **Install the module**:
```bash
composer require nwidart/laravel-modules
php artisan module:make Examination
```

2. **Run migrations**:
```bash
php artisan module:migrate Examination
```

3. **Seed the database**:
```bash
php artisan module:seed Examination
```

4. **Publish assets**:
```bash
php artisan module:publish Examination
```

5. **Install frontend dependencies**:
```bash
cd Modules/Examination
npm install
npm run build
```

## 🗄 Database Structure

### Core Tables
- `exam_types` - Different types of examinations
- `exams` - Main exam information
- `question_categories` - Question organization
- `questions` - Question bank
- `exam_questions` - Exam-question relationships
- `exam_schedules` - Exam timetables
- `exam_attempts` - Student exam sessions
- `exam_answers` - Student responses
- `exam_results` - Final grades and rankings
- `proctoring_logs` - Proctoring activity logs

## 🎯 Usage

### Creating an Exam

```php
use Modules\Examination\app\Models\Exam;
use Modules\Examination\app\Models\ExamType;

$exam = Exam::create([
    'name' => 'Mathematics Final 2024',
    'code' => 'MATH_FINAL_2024',
    'exam_type_id' => ExamType::where('code', 'FINAL')->first()->id,
    'academic_year' => '2023-2024',
    'term' => 'Spring',
    'start_date' => now()->addDays(7),
    'end_date' => now()->addDays(7),
    'duration_minutes' => 120,
    'total_marks' => 100,
    'is_online' => true,
    'enable_proctoring' => true,
    'shuffle_questions' => true,
    'status' => 'published'
]);
```

### Adding Questions

```php
use Modules\Examination\app\Models\Question;

$question = Question::create([
    'question_text' => 'What is 2 + 2?',
    'type' => 'mcq',
    'category_id' => $category->id,
    'options' => ['3', '4', '5', '6'],
    'correct_answers' => ['4'],
    'marks' => 2
]);

$exam->questions()->attach($question->id, ['order' => 1]);
```

### Starting an Online Exam

```php
use Modules\Examination\app\Models\ExamAttempt;

$attempt = ExamAttempt::create([
    'exam_id' => $exam->id,
    'student_id' => auth()->id(),
    'attempt_code' => Str::random(16),
    'started_at' => now(),
    'expires_at' => now()->addMinutes($exam->duration_minutes),
    'status' => 'started'
]);
```

## 🔧 Configuration

### Proctoring Settings

Add to your `.env` file:
```env
EXAMINATION_PROCTORING_ENABLED=true
EXAMINATION_SCREEN_RECORDING=true
EXAMINATION_VIDEO_QUALITY=720p
EXAMINATION_AUDIO_SAMPLE_RATE=44100
```

### Exam Settings

```php
// config/examination.php
return [
    'auto_save_interval' => 30, // seconds
    'proctoring_heartbeat' => 30, // seconds
    'max_file_size' => 10, // MB
    'allowed_file_types' => ['jpg', 'png', 'pdf'],
    'default_exam_duration' => 120, // minutes
];
```

## 🎨 Frontend Components

### Exam Timer Component
```blade
@component('examination::components.timer', [
    'duration' => $exam->duration_minutes,
    'startTime' => $attempt->started_at
])
@endcomponent
```

### Question Display Component
```blade
@component('examination::components.question-display', [
    'question' => $question,
    'questionNumber' => $index + 1
])
@endcomponent
```

## 🔒 Security Considerations

1. **HTTPS Required**: All exam sessions must use HTTPS
2. **Session Management**: Implement proper session handling
3. **Rate Limiting**: Apply rate limiting to prevent abuse
4. **Input Validation**: Validate all user inputs
5. **SQL Injection**: Use prepared statements
6. **XSS Protection**: Sanitize all outputs

## 📊 API Endpoints

### Exam Management
- `GET /api/examination/exams` - List exams
- `POST /api/examination/exams` - Create exam
- `GET /api/examination/exams/{id}` - Get exam details
- `PUT /api/examination/exams/{id}` - Update exam
- `DELETE /api/examination/exams/{id}` - Delete exam

### Online Exam
- `GET /examination/online/exam/{exam}` - Start online exam
- `POST /examination/online/save-answer/{attempt}` - Save answer
- `POST /examination/online/submit/{attempt}` - Submit exam
- `POST /examination/online/proctoring-log/{attempt}` - Log proctoring event

### Proctoring
- `POST /api/examination/proctoring/heartbeat` - Proctoring heartbeat
- `POST /api/examination/proctoring/screenshot` - Upload screenshot
- `POST /api/examination/proctoring/video-frame` - Upload video frame

## 🧪 Testing

Run the test suite:
```bash
php artisan test --filter=Examination
```

## 📈 Performance Optimization

1. **Database Indexing**: Ensure proper indexes on frequently queried columns
2. **Caching**: Implement Redis caching for exam data
3. **CDN**: Use CDN for static assets
4. **Queue Jobs**: Use queues for heavy operations like result processing
5. **Database Optimization**: Regular database maintenance

## 🚨 Troubleshooting

### Common Issues

1. **Proctoring not working**: Check browser permissions and HTTPS
2. **Timer issues**: Verify server time synchronization
3. **File upload failures**: Check file size limits and permissions
4. **Database connection**: Verify database credentials and connectivity

### Debug Mode

Enable debug mode in `.env`:
```env
EXAMINATION_DEBUG=true
```

## 🤝 Contributing

1. Fork the repository
2. Create a feature branch
3. Make your changes
4. Add tests
5. Submit a pull request

## 📄 License

This module is licensed under the MIT License.

## 🆘 Support

For support and questions:
- Create an issue on GitHub
- Contact: support@duncoschool.com
- Documentation: https://docs.duncoschool.com/examination

## 🔄 Changelog

### v1.0.0 (2024-01-01)
- Initial release
- Basic exam management
- Online examination
- Real-time proctoring
- Question bank system
- Result processing
- Analytics dashboard

---

**Built with ❤️ for educational institutions worldwide** 