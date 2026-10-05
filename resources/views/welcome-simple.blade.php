<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title>Dunco School Management System</title>
    
    <!-- Essential CSS -->
    <link href="{{ asset('css/font-fallback.css') }}" rel="stylesheet">
    <link href="{{ asset('css/essential.css') }}" rel="stylesheet">
    
    <!-- Essential JavaScript -->
    <script src="{{ asset('js/font-loader.js') }}"></script>
    <script src="{{ asset('js/essential.js') }}"></script>
    
    <style>
        .hero-section {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            padding: 4rem 0;
            text-align: center;
        }
        
        .hero-title {
            font-size: 3rem;
            font-weight: bold;
            margin-bottom: 1rem;
        }
        
        .hero-subtitle {
            font-size: 1.25rem;
            margin-bottom: 2rem;
            opacity: 0.9;
        }
        
        .cta-buttons {
            display: flex;
            gap: 1rem;
            justify-content: center;
            flex-wrap: wrap;
        }
        
        .btn {
            padding: 0.75rem 1.5rem;
            border-radius: 0.5rem;
            text-decoration: none;
            font-weight: 600;
            transition: all 0.3s ease;
        }
        
        .btn-primary {
            background: #4f46e5;
            color: white;
        }
        
        .btn-primary:hover {
            background: #4338ca;
            transform: translateY(-2px);
        }
        
        .btn-secondary {
            background: rgba(255, 255, 255, 0.1);
            color: white;
            border: 1px solid rgba(255, 255, 255, 0.3);
        }
        
        .btn-secondary:hover {
            background: rgba(255, 255, 255, 0.2);
        }
        
        .features-section {
            padding: 4rem 0;
            background: #f8f9fa;
        }
        
        .features-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
            gap: 2rem;
            max-width: 1200px;
            margin: 0 auto;
            padding: 0 1rem;
        }
        
        .feature-card {
            background: white;
            padding: 2rem;
            border-radius: 0.75rem;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
            text-align: center;
        }
        
        .feature-icon {
            font-size: 3rem;
            margin-bottom: 1rem;
        }
        
        .feature-title {
            font-size: 1.5rem;
            font-weight: 600;
            margin-bottom: 1rem;
            color: #111827;
        }
        
        .feature-description {
            color: #6b7280;
            line-height: 1.6;
        }
        
        .stats-section {
            padding: 4rem 0;
            background: white;
        }
        
        .stats-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 2rem;
            max-width: 1200px;
            margin: 0 auto;
            padding: 0 1rem;
        }
        
        .stat-card {
            text-align: center;
            padding: 2rem;
        }
        
        .stat-number {
            font-size: 3rem;
            font-weight: bold;
            color: #4f46e5;
            margin-bottom: 0.5rem;
        }
        
        .stat-label {
            color: #6b7280;
            font-size: 1.125rem;
        }
        
        @media (max-width: 768px) {
            .hero-title {
                font-size: 2rem;
            }
            
            .cta-buttons {
                flex-direction: column;
                align-items: center;
            }
            
            .btn {
                width: 100%;
                max-width: 300px;
                text-align: center;
            }
        }
    </style>
</head>
<body>
    <div class="hero-section">
        <div class="container">
            <h1 class="hero-title">School Management System</h1>
            <p class="hero-subtitle">
                Streamline your educational institution with our comprehensive management platform
            </p>
            <div class="cta-buttons">
                <a href="{{ route('login') }}" class="btn btn-primary">Login</a>
                <a href="{{ route('register') }}" class="btn btn-secondary">Get Started</a>
            </div>
        </div>
    </div>
    
    <div class="features-section">
        <div class="container">
            <h2 style="text-align: center; margin-bottom: 3rem; font-size: 2.5rem; color: #111827;">
                Everything your school needs
            </h2>
            <div class="features-grid">
                <div class="feature-card">
                    <div class="feature-icon">👨‍🎓</div>
                    <h3 class="feature-title">Student Management</h3>
                    <p class="feature-description">
                        Complete student lifecycle management from admission to graduation
                    </p>
                </div>
                <div class="feature-card">
                    <div class="feature-icon">💰</div>
                    <h3 class="feature-title">Financial Management</h3>
                    <p class="feature-description">
                        Handle fees, payments, and financial reporting efficiently
                    </p>
                </div>
                <div class="feature-card">
                    <div class="feature-icon">📚</div>
                    <h3 class="feature-title">Academic Management</h3>
                    <p class="feature-description">
                        Manage courses, exams, grades, and academic records
                    </p>
                </div>
                <div class="feature-card">
                    <div class="feature-icon">📊</div>
                    <h3 class="feature-title">Reports & Analytics</h3>
                    <p class="feature-description">
                        Comprehensive reporting and data analytics for informed decisions
                    </p>
                </div>
                <div class="feature-card">
                    <div class="feature-icon">📱</div>
                    <h3 class="feature-title">Communication</h3>
                    <p class="feature-description">
                        Seamless communication between staff, students, and parents
                    </p>
                </div>
                <div class="feature-card">
                    <div class="feature-icon">🔒</div>
                    <h3 class="feature-title">Security</h3>
                    <p class="feature-description">
                        Enterprise-grade security to protect sensitive educational data
                    </p>
                </div>
            </div>
        </div>
    </div>
    
    <div class="stats-section">
        <div class="container">
            <div class="stats-grid">
                <div class="stat-card">
                    <div class="stat-number counter" data-target="50">0</div>
                    <div class="stat-label">Schools Onboarded</div>
                </div>
                <div class="stat-card">
                    <div class="stat-number counter" data-target="15000">0</div>
                    <div class="stat-label">Students Managed</div>
                </div>
                <div class="stat-card">
                    <div class="stat-number">99.9%</div>
                    <div class="stat-label">System Uptime</div>
                </div>
                <div class="stat-card">
                    <div class="stat-number">24/7</div>
                    <div class="stat-label">Support Available</div>
                </div>
            </div>
        </div>
    </div>
    
    <footer style="background: #111827; color: white; padding: 2rem 0; text-align: center;">
        <div class="container">
            <p>&copy; 2024 Dunco School Management System. All rights reserved.</p>
        </div>
    </footer>
</body>
</html>
