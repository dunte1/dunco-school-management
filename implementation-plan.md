# IMPLEMENTATION PLAN
## dunco-school-management

**Created:** 2026-09-19
**Based on:** gaps.md (full audit) + fresh audit addendum (section 30)
**Approach:** Sequential phases, each with verification before proceeding

---

## Phase Execution Order

| Phase | Name | Status | Est. Effort |
|-------|------|--------|-------------|
| 0 | Baseline and Safety | IN PROGRESS | 1-2 hours |
| 1 | Critical Security Fixes | PENDING | 2-3 hours |
| 2 | Architecture and Provider Bootstrapping | PENDING | 3-4 hours |
| 3 | Database Consolidation | PENDING | 2-3 hours |
| 4 | Authentication and Authorization | PENDING | 3-4 hours |
| 5 | Attendance Module | PENDING | 3-4 hours |
| 6 | Document Module | PENDING | 3-4 hours |
| 7 | Notification Module | PENDING | 2-3 hours |
| 8 | Academic Module Completion | PENDING | 4-5 hours |
| 9 | Examination Module Completion | PENDING | 4-5 hours |
| 10 | Finance Module Completion | PENDING | 5-6 hours |
| 11 | HR Module Completion | PENDING | 3-4 hours |
| 12 | Hostel Module Completion | PENDING | 2-3 hours |
| 13 | Library Module Completion | PENDING | 2-3 hours |
| 14 | Transport Module Completion | PENDING | 2-3 hours |
| 15 | Timetable Module Completion | PENDING | 3-4 hours |
| 16 | Communication Module Completion | PENDING | 2-3 hours |
| 17 | Portal Module Completion | PENDING | 3-4 hours |
| 18 | Settings and ChatBot Completion | PENDING | 3-4 hours |
| 19 | API/Mobile Completion | PENDING | 4-5 hours |
| 20 | Cross-System Features | PENDING | 3-4 hours |
| 21 | Testing Suite | PENDING | 4-5 hours |
| 22 | Performance and Final QA | PENDING | 3-4 hours |

---

## PHASE 0 - Baseline and Safety

**Objective:** Lock in a stable, secure baseline before feature work.
**Gap IDs:** CRIT-05, CRIT-10, CRIT-11, LOW-05, LOW-06

### Tasks
1. Remove unauthenticated debug/seed routes (/test, /test-transport, /seed-transport)
2. Remove ChatBot .env write endpoints
3. Fix debug/permissions to require admin middleware
4. Verify app boots: php artisan route:list works

### Verification
- No /test* or /seed-transport routes listed
- /login returns 200

---

## PHASE 1 - Critical Security Fixes

**Objective:** Eliminate exploitable vulnerabilities.
**Gap IDs:** CRIT-02, CRIT-04, S-H1, S-H2, S-H3, S-M1

### Tasks
1. Add auth middleware to Finance route group
2. Remove hardcoded Gemini key, add env fallback only
3. Add auth to ChatBot API routes
4. Move Academic student list inside auth:sanctum
5. Fix Zip Slip in student bulk import
6. Add validation to all ->all() usage
7. Add MIME/size validation to file uploads
8. Admin-gate Settings secrets

### Verification
- Unauthenticated requests to finance/chatbot/upload endpoints return 302/403
- No hardcoded API keys in source
- Zip traversal rejected

---

## PHASE 2 - Architecture and Provider Bootstrapping

**Objective:** Resolve module/provider/sanctum/spatie inconsistency.
**Gap IDs:** CRIT-01, CRIT-03, CRIT-06, CRIT-12, HIGH-07, HIGH-14, HIGH-15

### Tasks
1. Register Sanctum guard + HasApiTokens on User model
2. Register middleware aliases (admin, role, permission, verified)
3. Load module API routes
4. Fix duplicate web.php loading
5. Implement or delete zero-byte models
6. Fix namespace inconsistencies
7. Register Communication and Notification module providers

### Verification
- route:list shows api/v1 and mobile/v1 routes
- auth:sanctum returns 401 (not 500)
- role:admin middleware resolves correctly

---

## PHASE 3 - Database Consolidation

**Objective:** Coherent, portable schema with proper integrity.
**Gap IDs:** CRIT-09, HIGH-08, MED-08

### Tasks
1. Add missing foreign keys (50+ across Finance, Hostel, HR, Attendance, Timetable)
2. Add missing indexes on FK columns
3. Fix empty string guards in migrations
4. Add school_id to module tables for multi-tenancy
5. Standardize on one RBAC scheme
6. Fix broken model relationships
7. Ensure migrate:fresh works

### Verification
- php artisan migrate:fresh --seed succeeds
- No orphan models
- FK relationships enforced

---

## PHASE 4 - Authentication and Authorization

**Objective:** Server-side RBAC enforced everywhere.
**Gap IDs:** CRIT-13, HIGH-01, HIGH-02

### Tasks
1. Implement MustVerifyEmail or remove verification flow
2. Create policies for core models
3. Replace unscoped findOrFail() with school-scoped queries
4. Add permission checks to all controller actions
5. Fix broken route references (78 missing methods)
6. Fix broken view references (159 missing views)
7. Seed consistent roles/permissions

### Verification
- Feature tests: unauthenticated 401, non-owner 403, admin 200
- No unscoped record lookups

---

## PHASE 5 - Attendance Module

**Objective:** Full attendance system with all features.
**Gap IDs:** Section 30.3 Attendance

### Tasks
1. Implement Attendance models with  and relationships
2. Fix AdvancedAttendanceController namespace
3. Implement store/update/destroy in AttendanceController
4. Wire QR/biometric/face scans to actual attendance records
5. Implement session template CRUD API
6. Fix sendXDaysAbsentAlerts logic bug
7. Enforce attendance settings during marking
8. Implement bulk import and export
9. Add PDF export route
10. Fix XSS in past_records.blade.php

### Verification
- Mark attendance for a class -> DB record created
- QR scan creates attendance record (not just log)
- Settings (late threshold, backdate) enforced
- Export generates real file

---

## PHASE 6 - Document Module

**Objective:** Full document management system.
**Gap IDs:** Section 30.3 Document

### Tasks
1. Create Document model with relationships
2. Create DocumentCategory and DocumentTag models
3. Create migration for documents table
4. Implement DocumentController with full CRUD
5. Implement file upload with validation (MIME, size)
6. Implement download with access control
7. Implement versioning
8. Implement permissions/policy
9. Consolidate RequiredDocument into Document module
10. Create all missing views
11. Implement student document upload via Academic module

### Verification
- Upload a document -> stored securely with metadata
- Download requires permission
- Version history tracked
- Categories and tags work

---

## PHASE 7 - Notification Module

**Objective:** Full notification system integrated across modules.
**Gap IDs:** Section 30.3 Notification, HIGH-07

### Tasks
1. Create NotificationPreference model
2. Create NotificationTemplate model
3. Create migration for notification tables
4. Implement NotificationController with full CRUD
5. Implement notification dispatch service
6. Check user preferences before sending
7. Implement in-app notification center
8. Wire notifications to all modules (fees, attendance, exams, etc.)
9. Fix AttendanceSmsNotification channel
10. Complete StudentEnrolled notification
11. Schedule SendFeeReminders command
12. Create all missing views

### Verification
- Create a fee -> parent receives notification
- User can set notification preferences
- Preferences are respected before dispatch
- In-app notification list shows unread count

---

## PHASE 8 - Academic Module Completion

**Objective:** Complete all academic workflows.
**Gap IDs:** Section 30.3 Academic

### Tasks
1. Create AcademicYear and Term models/controllers/views
2. Implement student promotion system
3. Implement transcript generation (PDF)
4. Implement ExamResult entry controller
5. Implement Question bank controller
6. Complete SubjectController (groups, import, export, analytics)
7. Complete AcademicController (fix compact() issue)
8. Implement missing model files (SubjectFeedback, SubjectApproval, SubjectCapacityLimit, SubjectNotification)
9. Implement academic calendar (school-wide, not per-subject)
10. Add GPA/CGPA calculation using GradingScale
11. Fix IDOR in idCard()

### Verification
- Create academic year/term -> classes can be assigned
- Enter exam results -> grades calculated per GradingScale
- Generate transcript -> PDF with all results
- Promote students -> enrollment history updated

---

## PHASE 9 - Examination Module Completion

**Objective:** Full exam system with online exams and proctoring.
**Gap IDs:** Section 30.3 Examination

### Tasks
1. Implement QuestionController CRUD (real, not hardcoded)
2. Implement ExamScheduleController
3. Implement QuestionCategoryController
4. Implement student exam taking (start, submit, resume)
5. Implement auto-save and auto-submit
6. Implement proctoring (heartbeat, screenshots, tab detection)
7. Implement result calculation and publishing
8. Implement result analytics
9. Create all missing views
10. Fix hardcoded demo data in ExamController

### Verification
- Create question bank -> questions stored in DB
- Create exam from questions -> exam appears in list
- Student takes exam -> answers saved, result calculated
- Proctoring logs heartbeat data

---

## PHASE 10 - Finance Module Completion

**Objective:** Full finance system with payments, reports, and reconciliation.
**Gap IDs:** HIGH-03, Section 30.3 Finance

### Tasks
1. Implement M-Pesa STK Push integration
2. Implement M-Pesa C2B callback (no auth, validate signature)
3. Implement receipt generation (PDF)
4. Implement invoice auto-generation on fee assignment
5. Implement payment -> invoice balance update
6. Implement bank reconciliation (match with payment linking)
7. Implement General Ledger with double-entry
8. Implement budget CRUD and variance analysis
9. Implement all 7 stub report controllers
10. Add authorization to all Finance controllers
11. Add soft deletes to payment/fee/invoice models
12. Implement FinanceNotificationService (actually send notifications)
13. Implement BillingController
14. Create all missing views

### Verification
- Assign fee to student -> invoice generated
- Record M-Pesa payment -> STK push sent, callback updates payment
- Payment updates invoice balance
- Bank reconciliation matches transactions to payments
- Reports show real data
- Unauthorized users cannot access finance routes

---

## PHASE 11 - HR Module Completion

**Objective:** Full HR system with leave, payroll, and performance.
**Gap IDs:** Section 30.3 HR

### Tasks
1. Complete all missing resource methods (show/edit/update/destroy)
2. Implement leave approval workflow with notifications
3. Implement payroll calculation (salary + allowances - deductions)
4. Implement payslip generation (PDF)
5. Implement contract expiry tracking and notifications
6. Implement performance review workflow
7. Fix empty string guards in migrations
8. Add authorization to all HR controllers

### Verification
- Apply for leave -> approver notified -> approve/reject -> status updated
- Run payroll -> payslips generated
- Contract expiry triggers notification
- Unauthorized users cannot access HR data

---

## PHASE 12 - Hostel Module Completion

**Objective:** Complete hostel with reports and warden features.
**Gap IDs:** Section 30.3 Hostel

### Tasks
1. Complete all reports (occupancy, movement, maintenance, fee defaulters, damages, allocations)
2. Implement inspection workflow
3. Implement maintenance request workflow
4. Implement QR check-in (if feasible)
5. Complete warden dashboard with real data

### Verification
- Allocate room -> bed status updated
- Report shows occupancy statistics
- Maintenance request workflow functional

---

## PHASE 13 - Library Module Completion

**Objective:** Full library with real borrow/return and reports.
**Gap IDs:** Section 30.3 Library

### Tasks
1. Replace hardcoded data in MemberController with real CRUD
2. Replace hardcoded data in PublisherController with real CRUD
3. Replace hardcoded data in CategoryController with real CRUD
4. Implement real borrow/return with due dates and fines
5. Implement overdue tracking
6. Implement reservation system
7. Implement reports (borrowed, overdue, most-borrowed, fines)
8. Add barcode/QR generation for books
9. Implement digital library access

### Verification
- Create member -> stored in DB
- Borrow book -> borrow record created, book status updated
- Return book -> fine calculated if overdue
- Reports show real statistics

---

## PHASE 14 - Transport Module Completion

**Objective:** Full transport with trip management.
**Gap IDs:** Section 30.3 Transport

### Tasks
1. Fix Student import namespace
2. Complete all missing views
3. Implement passenger assignment to trips
4. Implement trip status tracking
5. Implement vehicle maintenance reminders
6. Complete dashboard with real statistics

### Verification
- Create vehicle/driver/route -> stored in DB
- Assign students to route -> trip passengers tracked
- Dashboard shows real statistics

---

## PHASE 15 - Timetable Module Completion

**Objective:** Full timetable with working auto-generation and workflow.
**Gap IDs:** Section 30.3 Timetable

### Tasks
1. Implement real constraint checking in TimetableAutoGenerator
2. Implement soft constraint scoring
3. Implement approval workflow (submit/approve/reject/publish)
4. Implement conflict detection
5. Implement calendar view
6. Implement PDF/CSV export
7. Implement analytics
8. Implement schedule snapshots and rollback

### Verification
- Auto-generate timetable -> respects constraints
- Submit for approval -> workflow progresses
- Conflict detection identifies overlapping schedules
- Export generates PDF/CSV

---

## PHASE 16 - Communication Module Completion

**Objective:** Full messaging with SMS, email, and push.
**Gap IDs:** Section 30.3 Communication

### Tasks
1. Implement SMS sending via Africa's Talking (real, not stub)
2. Implement FCM push notifications (real)
3. Implement template management
4. Implement scheduled messages
5. Implement delivery status tracking
6. Implement retry for failed deliveries
7. Complete missing settings/templates views

### Verification
- Send message -> delivered via chosen channel
- SMS actually sends (or fails gracefully with config)
- Delivery status tracked

---

## PHASE 17 - Portal Module Completion

**Objective:** Real data in student/parent/teacher portals.
**Gap IDs:** Section 30.3 Portal

### Tasks
1. Replace demo data with real Eloquent queries
2. Implement student portal (results, fees, attendance, timetable, exams, assignments)
3. Implement parent portal (multi-child, fees, attendance, results, communication)
4. Implement teacher portal (classes, attendance, marks, assignments)
5. Implement public admission application with real workflow
6. Fix wrong model imports

### Verification
- Student logs in -> sees real data (not hardcoded)
- Parent sees all children's data
- Admission application stored in DB

---

## PHASE 18 - Settings and ChatBot Completion

**Objective:** Complete settings and clean up ChatBot.
**Gap IDs:** Section 30.3 Settings, ChatBot

### Tasks
1. Create all missing Settings views (general, academic, finance, notifications, security, backup)
2. Implement backup endpoints
3. Extract ChatBotController HTML into Blade views
4. Fix ChatBotService undefined openAIService
5. Implement ChatBot usage tracking
6. Add rate limiting to ChatBot

### Verification
- Settings pages all accessible and functional
- ChatBot renders in proper Blade layout
- Usage statistics tracked

---

## PHASE 19 - API/Mobile Completion

**Objective:** Working, secured, documented API.
**Gap IDs:** CRIT-03, HIGH-11, MED-01, Section 20

### Tasks
1. Implement missing Mobile controllers (Academic, Finance, Notification, Library, Hostel, Transport, Document)
2. Add API Resources for consistent responses
3. Add FormRequests for validation
4. Add pagination and filtering
5. Add rate limiting
6. Generate API documentation

### Verification
- All API endpoints return consistent JSON
- Authentication required where specified
- Pagination works
- Documentation generated

---

## PHASE 20 - Cross-System Features

**Objective:** Global search, workflow engine, report builder.
**Gap IDs:** Section 9 Missing Features

### Tasks
1. Implement global search across all modules
2. Implement workflow engine for approvals (leave, documents, expenses, admissions)
3. Implement report builder with filters, sorting, PDF/CSV export
4. Implement system health monitoring dashboard
5. Wire scheduler for backups and fee reminders

### Verification
- Global search returns results from all modules
- Leave approval workflow end-to-end
- Reports export to PDF/CSV
- System health dashboard shows status

---

## PHASE 21 - Testing Suite

**Objective:** Meaningful test coverage of critical paths.
**Gap IDs:** Section 23

### Tasks
1. Feature tests for every module workflow (CRUD + domain)
2. Authorization tests for every policy
3. Validation tests for every FormRequest
4. API tests for every endpoint
5. Fix tests that accept 500 as valid
6. Ensure all tests run on SQLite

### Verification
- php artisan test passes
- No tests accept 500 as valid
- Critical paths have >80% coverage

---

## PHASE 22 - Performance and Final QA

**Objective:** Optimized, production-ready system.
**Gap IDs:** Section 22, Section 29

### Tasks
1. Fix N+1 queries with eager loading
2. Add query caching for dashboards
3. Enable config:cache and route:cache
4. Full regression testing
5. Security re-test
6. Create FINAL-AUDIT.md
7. Production readiness checklist

### Verification
- No N+1 queries in critical paths
- Dashboard loads under 2 seconds
- All tests pass
- FINAL-AUDIT.md documents completion status

---

## Notes

- Each phase builds on the previous one
- Phases 0-4 are infrastructure/foundation work
- Phases 5-18 are module implementations
- Phases 19-22 are cross-cutting concerns and quality
- The order prioritizes security and data integrity before features
- Each phase should be verified before moving to the next
