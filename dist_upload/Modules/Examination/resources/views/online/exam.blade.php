@extends('layouts.app')

@section('title', 'Online Exam')

@section('content')
<div class="min-h-screen bg-gray-900">
    <!-- Exam Header -->
    <div class="bg-gray-800 border-b border-gray-700 sticky top-0 z-40">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between items-center py-4">
                <div>
                    <h1 class="text-2xl font-bold text-white">{{ $exam->name }}</h1>
                    <p class="text-gray-300 text-sm">{{ $exam->examType->name }} • {{ $exam->academic_year }} - {{ $exam->term }}</p>
                </div>
                <div class="flex items-center space-x-6">
                    <!-- Timer -->
                    <div class="bg-red-600 text-white px-4 py-2 rounded-lg font-mono text-lg font-bold" id="timer">
                        <span id="hours">00</span>:<span id="minutes">00</span>:<span id="seconds">00</span>
                    </div>
                    
                    <!-- Progress -->
                    <div class="text-white">
                        <span id="currentQuestion">1</span> / <span id="totalQuestions">{{ $exam->questions->count() }}</span>
                    </div>
                    
                    <!-- Proctoring Status -->
                    @if($exam->enable_proctoring)
                    <div class="flex items-center space-x-2">
                        <div class="w-3 h-3 bg-green-500 rounded-full animate-pulse" id="proctoringStatus"></div>
                        <span class="text-green-400 text-sm">PROCTORED</span>
                    </div>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
        <div class="grid grid-cols-1 lg:grid-cols-4 gap-8">
            <!-- Main Exam Area -->
            <div class="lg:col-span-3">
                <div class="bg-gray-800 rounded-xl border border-gray-700 p-6">
                    <!-- Question Navigation -->
                    <div class="flex justify-between items-center mb-6">
                        <div class="flex space-x-2">
                            <button onclick="previousQuestion()" class="bg-gray-700 hover:bg-gray-600 text-white px-4 py-2 rounded-lg transition-colors duration-200 flex items-center space-x-2">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path>
                                </svg>
                                <span>PREVIOUS</span>
                            </button>
                            <button onclick="nextQuestion()" class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg transition-colors duration-200 flex items-center space-x-2">
                                <span>NEXT</span>
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                                </svg>
                            </button>
                        </div>
                        <button onclick="submitExam()" class="bg-red-600 hover:bg-red-700 text-white px-6 py-2 rounded-lg font-medium transition-colors duration-200">
                            SUBMIT EXAM
                        </button>
                    </div>

                    <!-- Question Content -->
                    <div id="questionContainer" class="space-y-6">
                        <!-- Question will be loaded here -->
                    </div>
                </div>
            </div>

            <!-- Sidebar -->
            <div class="lg:col-span-1">
                <!-- Question Navigator -->
                <div class="bg-gray-800 rounded-xl border border-gray-700 p-6 mb-6">
                    <h3 class="text-lg font-semibold text-white mb-4">QUESTION NAVIGATOR</h3>
                    <div class="grid grid-cols-5 gap-2" id="questionNavigator">
                        <!-- Question numbers will be generated here -->
                    </div>
                </div>

                <!-- Proctoring Panel -->
                @if($exam->enable_proctoring)
                <div class="bg-gray-800 rounded-xl border border-gray-700 p-6 mb-6">
                    <h3 class="text-lg font-semibold text-white mb-4">PROCTORING</h3>
                    <div class="space-y-4">
                        <div class="flex items-center justify-between">
                            <span class="text-gray-300 text-sm">CAMERA</span>
                            <div class="w-3 h-3 bg-green-500 rounded-full" id="cameraStatus"></div>
                        </div>
                        <div class="flex items-center justify-between">
                            <span class="text-gray-300 text-sm">MICROPHONE</span>
                            <div class="w-3 h-3 bg-green-500 rounded-full" id="micStatus"></div>
                        </div>
                        <div class="flex items-center justify-between">
                            <span class="text-gray-300 text-sm">SCREEN RECORDING</span>
                            <div class="w-3 h-3 bg-green-500 rounded-full" id="screenStatus"></div>
                        </div>
                        <div class="text-center">
                            <span class="text-yellow-400 text-sm" id="proctoringAlerts">0 ALERTS</span>
                        </div>
                    </div>
                </div>
                @endif

                <!-- Exam Info -->
                <div class="bg-gray-800 rounded-xl border border-gray-700 p-6">
                    <h3 class="text-lg font-semibold text-white mb-4">EXAM INFO</h3>
                    <div class="space-y-3 text-sm">
                        <div class="flex justify-between">
                            <span class="text-gray-300">TOTAL MARKS:</span>
                            <span class="text-white font-medium">{{ $exam->total_marks }}</span>
                        </div>
                        <div class="flex justify-between">
                            <span class="text-gray-300">PASSING MARKS:</span>
                            <span class="text-white font-medium">{{ $exam->passing_marks }}</span>
                        </div>
                        <div class="flex justify-between">
                            <span class="text-gray-300">DURATION:</span>
                            <span class="text-white font-medium">{{ $exam->duration_minutes }} min</span>
                        </div>
                        <div class="flex justify-between">
                            <span class="text-gray-300">QUESTIONS:</span>
                            <span class="text-white font-medium">{{ $exam->questions->count() }}</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Proctoring Video Modal -->
@if($exam->enable_proctoring)
<div id="proctoringModal" class="fixed inset-0 bg-black bg-opacity-75 hidden z-50">
    <div class="flex items-center justify-center min-h-screen p-4">
        <div class="bg-gray-800 rounded-xl max-w-2xl w-full border border-gray-700">
            <div class="px-6 py-4 border-b border-gray-700">
                <h3 class="text-xl font-semibold text-white">PROCTORING SETUP</h3>
            </div>
            <div class="p-6 space-y-4">
                <div class="text-center">
                    <video id="proctoringVideo" class="w-full h-64 bg-gray-700 rounded-lg" autoplay muted></video>
                </div>
                <div class="text-center text-gray-300">
                    <p>Please allow camera and microphone access to continue with the exam.</p>
                    <p class="text-sm mt-2">Your exam session will be monitored for academic integrity.</p>
                </div>
                <div class="flex justify-center space-x-3">
                    <button onclick="startProctoring()" class="bg-blue-600 hover:bg-blue-700 text-white px-6 py-2 rounded-lg font-medium transition-colors duration-200">
                        START EXAM
                    </button>
                    <button onclick="closeProctoringModal()" class="bg-gray-600 hover:bg-gray-700 text-white px-6 py-2 rounded-lg font-medium transition-colors duration-200">
                        CANCEL
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>
@endif

<!-- Submit Confirmation Modal -->
<div id="submitModal" class="fixed inset-0 bg-black bg-opacity-75 hidden z-50">
    <div class="flex items-center justify-center min-h-screen p-4">
        <div class="bg-gray-800 rounded-xl max-w-md w-full border border-gray-700">
            <div class="px-6 py-4 border-b border-gray-700">
                <h3 class="text-xl font-semibold text-white">CONFIRM SUBMISSION</h3>
            </div>
            <div class="p-6">
                <div id="unansweredSummary"></div>
                <p class="text-gray-300 mb-4">Are you sure you want to submit your exam? This action cannot be undone.</p>
                <div class="flex justify-end space-x-3">
                    <button onclick="closeSubmitModal()" class="px-4 py-2 text-gray-300 hover:text-white transition-colors duration-200">
                        CANCEL
                    </button>
                    <button onclick="confirmSubmit()" class="bg-red-600 hover:bg-red-700 text-white px-6 py-2 rounded-lg font-medium transition-colors duration-200">
                        SUBMIT EXAM
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>

@endsection

@push('scripts')
<script>
let examData = null;
let currentQuestionIndex = 0;
let answers = {};
let examStartTime = null;
let proctoringInterval = null;
let heartbeatInterval = null;

// Initialize exam
document.addEventListener('DOMContentLoaded', function() {
    loadExamData();
    if (@json($exam->enable_proctoring)) {
        showProctoringModal();
    } else {
        startExam();
    }
});

async function loadExamData() {
    try {
        const response = await fetch(`/examination/online/exam-data/{{ $attempt->id }}`);
        examData = await response.json();
        renderQuestionNavigator();
        loadQuestion(0);
        startTimer();
        startHeartbeat();
    } catch (error) {
        console.error('Failed to load exam data:', error);
    }
}

function renderQuestionNavigator() {
    const navigator = document.getElementById('questionNavigator');
    navigator.innerHTML = '';
    
    examData.questions.forEach((question, index) => {
        const button = document.createElement('button');
        button.className = 'w-8 h-8 rounded-lg text-sm font-medium transition-colors duration-200 relative group';
        button.textContent = index + 1;
        button.onclick = () => loadQuestion(index);
        
        if (index === currentQuestionIndex) {
            button.className += ' bg-blue-600 text-white ring-2 ring-blue-400';
        } else if (answers[index]) {
            button.className += ' bg-green-600 text-white';
        } else {
            button.className += ' bg-gray-700 text-gray-300 hover:bg-gray-600';
        }
        
        // Tooltip for answered/unanswered
        const tooltip = document.createElement('span');
        tooltip.className = 'absolute left-1/2 -translate-x-1/2 bottom-full mb-2 px-2 py-1 rounded bg-black text-xs text-white opacity-0 group-hover:opacity-100 pointer-events-none z-10';
        tooltip.textContent = answers[index] ? 'Answered' : 'Unanswered';
        button.appendChild(tooltip);
        navigator.appendChild(button);
    });
}

function loadQuestion(index) {
    if (index < 0 || index >= examData.questions.length) return;
    
    currentQuestionIndex = index;
    const question = examData.questions[index];
    const container = document.getElementById('questionContainer');
    
    let questionHtml = `
        <div class="mb-6">
            <div class="flex items-center justify-between mb-4">
                <h2 class="text-xl font-semibold text-white">Question ${index + 1}</h2>
                <span class="text-gray-300">${question.marks} marks</span>
            </div>
            <div class="bg-gray-700 rounded-lg p-4 mb-6">
                <p class="text-white text-lg">${question.question_text}</p>
            </div>
    `;
    
    // Render different question types
    switch (question.type) {
        case 'mcq':
            questionHtml += renderMCQ(question, index);
            break;
        case 'essay':
            questionHtml += renderEssay(question, index);
            break;
        case 'coding':
            questionHtml += renderCoding(question, index);
            break;
        case 'fill_blank':
            questionHtml += renderFillBlank(question, index);
            break;
        default:
            questionHtml += renderDefault(question, index);
    }
    
    questionHtml += '</div>';
    container.innerHTML = questionHtml;
    
    // Update navigation
    document.getElementById('currentQuestion').textContent = index + 1;
    renderQuestionNavigator();
    
    // Auto-save current answer
    autoSaveAnswer();
}

function renderMCQ(question, index) {
    let html = '<div class="space-y-3">';
    question.options.forEach((option, optionIndex) => {
        const isChecked = answers[index] && answers[index].includes(option);
        html += `
            <label class="flex items-center p-3 bg-gray-700 rounded-lg hover:bg-gray-600 cursor-pointer transition-colors duration-200">
                <input type="checkbox" value="${option}" onchange="updateAnswer(${index}, '${option}', this.checked)" 
                       ${isChecked ? 'checked' : ''} class="mr-3 bg-gray-600 border-gray-500 text-blue-500 rounded focus:ring-blue-500">
                <span class="text-white">${option}</span>
            </label>
        `;
    });
    html += '</div>';
    return html;
}

function renderEssay(question, index) {
    const currentAnswer = answers[index] || '';
    let html = `
        <div>
            <textarea id="essay-${index}" rows="10" class="w-full bg-gray-700 border border-gray-600 text-white rounded-lg px-3 py-2 focus:outline-none focus:border-blue-500" 
                      placeholder="Write your answer here..." oninput="updateEssayAnswer(${index}, this.value)">${typeof currentAnswer === 'string' ? currentAnswer : ''}</textarea>
    `;
    if (question.file_upload) {
        html += `
            <div class="mt-4">
                <label class="block text-gray-300 mb-2">Upload File (PDF, DOC, DOCX, Max 10MB)</label>
                <input type="file" accept=".pdf,.doc,.docx" onchange="handleFileUpload(event, ${index})"
                    class="block w-full text-sm text-gray-200 bg-gray-700 border border-gray-600 rounded-lg cursor-pointer focus:outline-none focus:border-blue-500" />
                <div id="file-name-${index}" class="mt-2 text-blue-400 text-xs">
                    ${currentAnswer && typeof currentAnswer !== 'string' && currentAnswer.name ? 'Selected: ' + currentAnswer.name : ''}
                </div>
            </div>
        `;
    }
    html += '</div>';
    return html;
}

function handleFileUpload(event, index) {
    const file = event.target.files[0];
    if (file) {
        if (!['application/pdf', 'application/msword', 'application/vnd.openxmlformats-officedocument.wordprocessingml.document'].includes(file.type)) {
            alert('Only PDF, DOC, or DOCX files are allowed.');
            event.target.value = '';
            return;
        }
        if (file.size > 10 * 1024 * 1024) {
            alert('File size must be less than 10MB.');
            event.target.value = '';
            return;
        }
        answers[index] = file;
        document.getElementById(`file-name-${index}`).textContent = 'Selected: ' + file.name;
        autoSaveAnswer();
    }
}

function renderCoding(question, index) {
    const currentAnswer = answers[index] || '';
    return `
        <div>
            <div class="bg-gray-900 rounded-lg p-4 mb-4">
                <div class="flex items-center justify-between mb-2">
                    <span class="text-gray-300 text-sm">CODE EDITOR</span>
                    <select class="bg-gray-700 text-white border border-gray-600 rounded px-2 py-1 text-sm">
                        <option>JavaScript</option>
                        <option>Python</option>
                        <option>Java</option>
                        <option>C++</option>
                    </select>
                </div>
                <textarea id="code-${index}" rows="15" class="w-full bg-gray-900 border border-gray-700 text-green-400 font-mono text-sm rounded px-3 py-2 focus:outline-none focus:border-blue-500" 
                          placeholder="Write your code here..." oninput="updateCodeAnswer(${index}, this.value)">${currentAnswer}</textarea>
            </div>
        </div>
    `;
}

function renderFillBlank(question, index) {
    const currentAnswer = answers[index] || '';
    return `
        <div>
            <input type="text" value="${currentAnswer}" oninput="updateAnswer(${index}, this.value)" 
                   class="w-full bg-gray-700 border border-gray-600 text-white rounded-lg px-3 py-2 focus:outline-none focus:border-blue-500" 
                   placeholder="Enter your answer">
        </div>
    `;
}

function renderDefault(question, index) {
    const currentAnswer = answers[index] || '';
    return `
        <div>
            <input type="text" value="${currentAnswer}" oninput="updateAnswer(${index}, this.value)" 
                   class="w-full bg-gray-700 border border-gray-600 text-white rounded-lg px-3 py-2 focus:outline-none focus:border-blue-500" 
                   placeholder="Enter your answer">
        </div>
    `;
}

function updateAnswer(questionIndex, value, checked = null) {
    if (checked !== null) {
        // Handle checkbox (MCQ)
        if (!answers[questionIndex]) answers[questionIndex] = [];
        if (checked) {
            answers[questionIndex].push(value);
        } else {
            answers[questionIndex] = answers[questionIndex].filter(v => v !== value);
        }
    } else {
        // Handle single value
        answers[questionIndex] = value;
    }
    autoSaveAnswer();
}

function updateEssayAnswer(questionIndex, value) {
    answers[questionIndex] = value;
    autoSaveAnswer();
}

function updateCodeAnswer(questionIndex, value) {
    answers[questionIndex] = value;
    autoSaveAnswer();
}

function previousQuestion() {
    if (currentQuestionIndex > 0) {
        loadQuestion(currentQuestionIndex - 1);
    }
}

function nextQuestion() {
    if (currentQuestionIndex < examData.questions.length - 1) {
        loadQuestion(currentQuestionIndex + 1);
    }
}

function startTimer() {
    examStartTime = new Date();
    const timerElement = document.getElementById('timer');
    
    setInterval(() => {
        const now = new Date();
        const elapsed = now - examStartTime;
        const remaining = (examData.exam.duration_minutes * 60 * 1000) - elapsed;
        
        if (remaining <= 0) {
            submitExam();
            return;
        }
        
        const hours = Math.floor(remaining / (1000 * 60 * 60));
        const minutes = Math.floor((remaining % (1000 * 60 * 60)) / (1000 * 60));
        const seconds = Math.floor((remaining % (1000 * 60)) / 1000);
        
        document.getElementById('hours').textContent = hours.toString().padStart(2, '0');
        document.getElementById('minutes').textContent = minutes.toString().padStart(2, '0');
        document.getElementById('seconds').textContent = seconds.toString().padStart(2, '0');
        
        // Warning colors
        if (remaining < 300000) { // 5 minutes
            timerElement.className = 'bg-red-600 text-white px-4 py-2 rounded-lg font-mono text-lg font-bold animate-pulse shadow-lg shadow-red-900';
        } else if (remaining < 600000) { // 10 minutes
            timerElement.className = 'bg-yellow-600 text-white px-4 py-2 rounded-lg font-mono text-lg font-bold animate-pulse shadow-lg shadow-yellow-900';
        } else {
            timerElement.className = 'bg-red-600 text-white px-4 py-2 rounded-lg font-mono text-lg font-bold';
        }
    }, 1000);
}

function autoSaveAnswer() {
    // Auto-save every 30 seconds
    setTimeout(() => {
        saveAnswers();
    }, 30000);
}

async function saveAnswers() {
    try {
        document.getElementById('autosaveStatus').textContent = 'Saving...';
        const currentQuestion = examData.questions[currentQuestionIndex];
        const answer = answers[currentQuestionIndex];
        let hasFile = currentQuestion.type === 'essay' && currentQuestion.file_upload && answer instanceof File;
        let url = `/examination/online/save-answer/{{ $attempt->id }}`;
        let options = {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                'Accept': 'application/json',
            },
        };
        if (hasFile) {
            let formData = new FormData();
            formData.append('question_id', currentQuestion.id);
            formData.append('file', answer);
            formData.append('time_spent_seconds', 30);
            let essayText = document.getElementById(`essay-${currentQuestionIndex}`)?.value || '';
            formData.append('essay_answer', essayText);
            options.body = formData;
        } else {
            options.headers['Content-Type'] = 'application/json';
            let payload = {
                question_id: currentQuestion.id,
                time_spent_seconds: 30
            };
            if (currentQuestion.type === 'essay') {
                payload.essay_answer = answer;
            } else if (currentQuestion.type === 'coding') {
                payload.code_answer = answer;
            } else {
                payload.answer = answer;
            }
            options.body = JSON.stringify(payload);
        }
        await fetch(url, options);
        document.getElementById('autosaveStatus').textContent = 'Saved';
        setTimeout(() => {
            document.getElementById('autosaveStatus').textContent = '';
        }, 1500);
    } catch (error) {
        document.getElementById('autosaveStatus').textContent = 'Autosave failed';
        console.error('Failed to save answer:', error);
    }
}

function startHeartbeat() {
    heartbeatInterval = setInterval(async () => {
        try {
            await fetch(`/examination/api/examination/exam-status/{{ $attempt->id }}`);
        } catch (error) {
            console.error('Heartbeat failed:', error);
        }
    }, 30000); // Every 30 seconds
}

function showProctoringModal() {
    document.getElementById('proctoringModal').classList.remove('hidden');
    startProctoring();
}

function closeProctoringModal() {
    document.getElementById('proctoringModal').classList.add('hidden');
}

async function startProctoring() {
    try {
        const stream = await navigator.mediaDevices.getUserMedia({ 
            video: true, 
            audio: true 
        });
        
        const video = document.getElementById('proctoringVideo');
        video.srcObject = stream;
        
        // Start proctoring monitoring
        startProctoringMonitoring();
        
        closeProctoringModal();
        startExam();
        
    } catch (error) {
        console.error('Failed to start proctoring:', error);
        alert('Please allow camera and microphone access to continue with the exam.');
    }
}

function startProctoringMonitoring() {
    if (!@json($exam->enable_proctoring)) return;
    
    proctoringInterval = setInterval(async () => {
        try {
            // Monitor for suspicious activities
            const alerts = await monitorProctoring();
            document.getElementById('proctoringAlerts').textContent = `${alerts} ALERTS`;
            
            if (alerts > 0) {
                document.getElementById('proctoringAlerts').className = 'text-red-400 text-sm';
            }
        } catch (error) {
            console.error('Proctoring monitoring failed:', error);
        }
    }, 5000); // Every 5 seconds
}

async function monitorProctoring() {
    // Simulate proctoring alerts
    let alerts = 0;
    
    // Check for tab switching
    if (document.hidden) {
        alerts++;
        await logProctoringEvent('tab_switch', 'Tab switch detected', 'medium');
    }
    
    // Check for right-click
    document.addEventListener('contextmenu', async (e) => {
        e.preventDefault();
        alerts++;
        await logProctoringEvent('right_click', 'Right-click detected', 'low');
    });
    
    return alerts;
}

async function logProctoringEvent(eventType, description, severity) {
    try {
        await fetch(`/examination/online/proctoring-log/{{ $attempt->id }}`, {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                'Accept': 'application/json',
                'Content-Type': 'application/json'
            },
            body: JSON.stringify({
                event_type: eventType,
                description: description,
                severity: severity
            })
        });
    } catch (error) {
        console.error('Failed to log proctoring event:', error);
    }
}

function startExam() {
    fetch(`/examination/online/start-attempt/{{ $attempt->id }}`, {
        method: 'POST',
        headers: {
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
            'Accept': 'application/json',
            'Content-Type': 'application/json'
        }
    });
}

function submitExam() {
    // Find unanswered questions
    const unanswered = examData.questions.map((q, i) => answers[i] ? null : (i + 1)).filter(x => x);
    let modal = document.getElementById('submitModal');
    let summary = '';
    if (unanswered.length > 0) {
        summary = `<div class='mb-4 text-yellow-400 font-semibold'>You have not answered the following questions: <span class='font-bold'>${unanswered.join(', ')}</span></div>`;
    } else {
        summary = `<div class='mb-4 text-green-400 font-semibold'>All questions are answered.</div>`;
    }
    document.getElementById('unansweredSummary').innerHTML = summary;
    modal.classList.remove('hidden');
}

function closeSubmitModal() {
    document.getElementById('submitModal').classList.add('hidden');
}

async function confirmSubmit() {
    try {
        // Save all answers first
        await saveAnswers();
        
        const response = await fetch(`/examination/online/submit/{{ $attempt->id }}`, {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                'Accept': 'application/json',
                'Content-Type': 'application/json'
            }
        });
        
        const result = await response.json();
        
        if (result.success) {
            // Redirect to results page
            window.location.href = `/examination/online/result/{{ $attempt->id }}`;
        } else {
            alert('Failed to submit exam: ' + result.message);
        }
    } catch (error) {
        console.error('Failed to submit exam:', error);
        alert('Failed to submit exam. Please try again.');
    }
}

// Prevent leaving the page
window.addEventListener('beforeunload', function(e) {
    e.preventDefault();
    e.returnValue = 'Are you sure you want to leave? Your exam progress may be lost.';
});

// Prevent keyboard shortcuts
document.addEventListener('keydown', function(e) {
    if (e.ctrlKey || e.metaKey) {
        e.preventDefault();
    }
});
</script>
@endpush 