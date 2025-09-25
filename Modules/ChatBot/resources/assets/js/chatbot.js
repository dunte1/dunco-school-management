console.log("ChatBot JavaScript loaded successfully");

const messageInput = document.getElementById("messageInput");
const sendButton = document.getElementById("sendButton");
const chatMessages = document.getElementById("chatMessages");
const typingIndicator = document.getElementById("typingIndicator");
const fileInput = document.getElementById("fileInput");
const uploadProgress = document.getElementById("uploadProgress");

function addMessage(text, sender, isDocument = false, documentData = null) {
    console.log("Adding message:", text, "from:", sender);
    const messageDiv = document.createElement("div");
    messageDiv.className = "message " + sender + "-message";
    
    if (isDocument && documentData) {
        messageDiv.innerHTML = `
            <div class="document-message">
                <div class="document-info">
                    <div class="document-icon">📄</div>
                    <div class="document-details">
                        <h4>${documentData.filename}</h4>
                        <p>${documentData.file_type} • ${documentData.file_size}</p>
                    </div>
                </div>
                <div>${text}</div>
            </div>
        `;
    } else {
        messageDiv.textContent = text;
    }
    
    chatMessages.appendChild(messageDiv);
    chatMessages.scrollTop = chatMessages.scrollHeight;
}

function showTypingIndicator() {
    console.log("Showing typing indicator");
    typingIndicator.style.display = "block";
    chatMessages.scrollTop = chatMessages.scrollHeight;
}

function hideTypingIndicator() {
    console.log("Hiding typing indicator");
    typingIndicator.style.display = "none";
}

function uploadFile(file) {
    console.log("Uploading file:", file.name);
    
    const formData = new FormData();
    formData.append('document', file);
    formData.append('_token', document.querySelector("meta[name=csrf-token]").getAttribute("content"));
    
    // Show upload progress
    uploadProgress.style.display = "block";
    chatMessages.scrollTop = chatMessages.scrollHeight;
    
    fetch("/chatbot/upload-document", {
        method: "POST",
        body: formData
    })
    .then(response => response.json())
    .then(data => {
        console.log("Upload response:", data);
        uploadProgress.style.display = "none";
        
        if (data.success) {
            addMessage("Document uploaded successfully! You can now ask questions about it.", "bot", true, {
                filename: data.data.filename,
                file_type: data.data.file_type,
                file_size: data.data.file_size
            });
            
            // Store document ID for future questions
            window.currentDocumentId = data.data.document_id;
        } else {
            addMessage("Failed to upload document: " + data.message, "bot");
        }
    })
    .catch(error => {
        console.error("Upload Error:", error);
        uploadProgress.style.display = "none";
        addMessage("Failed to upload document. Please try again.", "bot");
    });
}

function sendMessage() {
    console.log("sendMessage function called");
    const message = messageInput.value.trim();
    if (!message) return;
    
    console.log("Sending message:", message);
    
    // Add user message
    addMessage(message, "user");
    messageInput.value = "";
    
    // Show typing indicator
    showTypingIndicator();
    
    // Send message to API
    fetch("/chatbot/send", {
        method: "POST",
        headers: {
            "Content-Type": "application/json",
            "X-CSRF-TOKEN": document.querySelector("meta[name=csrf-token]").getAttribute("content")
        },
        body: JSON.stringify({ message: message })
    })
    .then(response => response.json())
    .then(data => {
        console.log("API response:", data);
        hideTypingIndicator();
        if (data.success) {
            addMessage(data.data.ai_response, "bot");
        } else {
            // Use intelligent fallback response instead of hardcoded demo
            const intelligentResponse = getIntelligentResponse(message);
            addMessage(intelligentResponse, "bot");
        }
    })
    .catch(error => {
        console.error("API Error:", error);
        hideTypingIndicator();
        // Use intelligent fallback response instead of hardcoded demo
        const intelligentResponse = getIntelligentResponse(message);
        addMessage(intelligentResponse, "bot");
    });
}

// Intelligent response function
function getIntelligentResponse(message) {
    const msg = message.toLowerCase();
    
    // Mathematical calculations
    if (msg.match(/(\d+)\s*[\+\-\*\/]\s*(\d+)/)) {
        const matches = msg.match(/(\d+)\s*[\+\-\*\/]\s*(\d+)/);
        const num1 = parseInt(matches[1]);
        const num2 = parseInt(matches[2]);
        const operator = msg.match(/[\+\-\*\/]/)[0];
        
        let result;
        switch(operator) {
            case '+': result = num1 + num2; break;
            case '-': result = num1 - num2; break;
            case '*': result = num1 * num2; break;
            case '/': result = num2 !== 0 ? num1 / num2 : 'Error: Division by zero'; break;
            default: result = 'invalid operation';
        }
        
        return `The result of ${num1} ${operator} ${num2} = ${result}`;
    }
    
    // Health and medical queries
    if (msg.match(/(headache|pain|sick|ill|health|medical|doctor|medicine)/)) {
        return "I'm not a medical professional, but I can help you with:\n\n• School health policies and procedures\n• Contact information for the school nurse\n• Health-related absences and documentation\n• First aid information\n\nFor medical advice, please consult a healthcare professional or visit the school nurse.";
    }
    
    // Academic subject queries
    if (msg.match(/(anatomy|biology|chemistry|physics|math|history|geography|literature|english|science)/)) {
        return "I can help you with academic subjects! Here's what I can assist with:\n\n• Finding study materials and resources\n• Explaining concepts and topics\n• Providing study tips and strategies\n• Connecting you with subject teachers\n• Accessing library resources\n\nWhat specific topic would you like help with?";
    }
    
    // General knowledge questions
    if (msg.match(/(what is|who is|when is|where is|how to|explain|define|meaning of)/)) {
        return "I can help you with general knowledge questions! I can:\n\n• Explain concepts and definitions\n• Provide information on various topics\n• Help with research and study questions\n• Connect you with relevant resources\n• Assist with homework and assignments\n\nPlease ask your specific question and I'll do my best to help!";
    }
    
    // User count question
    if (msg.includes('how many users') || msg.includes('count users') || msg.includes('total users')) {
        return "I can help you check the user count! Please visit the admin dashboard or contact your system administrator to view detailed user statistics. You can also check the Users section in the Core module.";
    }
    
    // Time question
    if (msg.includes('what time') || msg.includes('current time') || msg.includes('time now')) {
        const now = new Date();
        return `The current time is: ${now.toLocaleDateString('en-US', { weekday: 'long', year: 'numeric', month: 'long', day: 'numeric' })} at ${now.toLocaleTimeString('en-US', { hour: 'numeric', minute: '2-digit', hour12: true })}`;
    }
    
    // Help request
    if (msg.includes('help') || msg.includes('what can you do') || msg.includes('assist')) {
        return `I can help you with:\n\n📚 **Academic**: Grades, schedules, homework, exams\n💰 **Financial**: Fees, payments, scholarships\n📋 **Administrative**: Attendance, transport, events\n🎓 **General**: School policies, contact info\n\nWhat would you like to know about?`;
    }
    
    // Greetings
    if (msg.includes('hello') || msg.includes('hi') || msg.includes('hey') || msg.includes('good morning') || msg.includes('good afternoon') || msg.includes('good evening')) {
        const greetings = [
            'Hello! How can I help you today?',
            'Hi there! I\'m here to assist you with school-related questions.',
            'Hello! What would you like to know about your school management system?',
            'Hi! I can help with academic, financial, and administrative questions.'
        ];
        return greetings[Math.floor(Math.random() * greetings.length)];
    }
    
    // Grade inquiries
    if (msg.includes('grade') || msg.includes('score') || msg.includes('mark') || msg.includes('result')) {
        return "To check your grades:\n\n1. Visit the Academic module\n2. Go to 'My Grades' section\n3. Select the term/semester\n4. View detailed results\n\nYou can also contact your teacher for specific grade inquiries.";
    }
    
    // Schedule inquiries
    if (msg.includes('schedule') || msg.includes('timetable') || msg.includes('class') || msg.includes('lesson')) {
        return "To view your schedule:\n\n1. Go to the Academic module\n2. Click on 'My Schedule'\n3. Select the day/week view\n4. Check for any updates\n\nYou can also sync your schedule with your calendar!";
    }
    
    // Fee inquiries
    if (msg.includes('fee') || msg.includes('payment') || msg.includes('money') || msg.includes('cost')) {
        return "For fee-related queries:\n\n1. Visit the Finance module\n2. Check 'My Fees' section\n3. View payment history\n4. See upcoming payments\n\nContact the accounts department for detailed information.";
    }
    
    // Attendance inquiries
    if (msg.includes('attendance') || msg.includes('present') || msg.includes('absent')) {
        return "To check your attendance:\n\n1. Go to the Attendance module\n2. View 'My Attendance' report\n3. Check monthly/yearly statistics\n4. Contact your class teacher for any discrepancies";
    }
    
    // Default enhanced response
    return "I'm here to help with school management questions! You can ask me about:\n\n• Grades and academic performance\n• Class schedules and timetables\n• Fees and payments\n• Attendance records\n• School events and policies\n• Academic subjects and study help\n• General knowledge questions\n• Health and wellness information\n\nFor more complex questions, please visit the specific modules or contact your teachers/administrators.";
}

function handleKeyPress(event) {
    console.log("Key pressed:", event.key);
    if (event.key === "Enter") {
        sendMessage();
    }
}

// Event listeners
sendButton.addEventListener("click", sendMessage);
messageInput.addEventListener("keypress", handleKeyPress);

// File upload event listener
fileInput.addEventListener("change", function(event) {
    const file = event.target.files[0];
    if (file) {
        uploadFile(file);
        // Reset file input
        event.target.value = "";
    }
});

// Focus on input when page loads
window.addEventListener("load", function() {
    console.log("Page loaded, focusing on input");
    messageInput.focus();
});

console.log("All functions defined successfully");
