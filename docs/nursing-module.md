# Nursing Education & Clinical Management Module

## Architecture

The Nursing module is built as a modular extension of the Dunco School Management System, following the established module pattern (same as Academic, HR, Finance, etc.).

### Technology Stack
- **Backend**: Laravel 12 (PHP)
- **Frontend**: Vue 3 + Inertia.js + TypeScript + Tailwind CSS
- **Database**: MySQL (same database as the main system)
- **Auth**: Existing Laravel authentication system
- **RBAC**: Existing custom role/permission system with school scoping

### Key Architectural Decisions
1. **No duplicate user/student/staff models** - References existing `users`, `academic_students`, `staff` tables via foreign keys
2. **Existing auth system** - Uses the same login, sessions, and authentication
3. **Existing permission system** - Adds nursing-specific permissions to the existing RBAC
4. **School scoping** - All nursing data is scoped to the school via `school_id`
5. **Audit trail** - All clinical actions are logged via `nursing_audit_logs`

---

## Database Schema

### New Tables (20 tables)

| Table | Purpose |
|---|---|
| `nursing_facilities` | Clinical facilities (hospitals, health centres) |
| `nursing_facility_departments` | Departments within facilities |
| `nursing_wards` | Wards within departments |
| `nursing_placements` | Student placement records |
| `nursing_logbooks` | Clinical logbook entries |
| `nursing_logbook_corrections` | Correction audit trail |
| `nursing_skill_categories` | Skill categories |
| `nursing_skills` | Skill library |
| `nursing_student_skills` | Student skill progress |
| `nursing_skill_rubrics` | Assessment rubric criteria |
| `nursing_skill_assessments` | Instructor assessments |
| `nursing_clinical_hours` | Clinical hours tracking |
| `nursing_reference_categories` | Reference categories |
| `nursing_reference_articles` | Reference articles |
| `nursing_scenarios` | Clinical case scenarios |
| `nursing_scenario_questions` | Scenario questions |
| `nursing_scenario_attempts` | Student scenario attempts |
| `nursing_cpd_activities` | CPD activities |
| `nursing_settings` | Module configuration |
| `nursing_audit_logs` | Clinical audit trail |

### Existing Tables Referenced

| Table | Relationship |
|---|---|
| `users` | Auth, instructors, reviewers |
| `academic_students` | Nursing students |
| `staff` | Clinical instructors |
| `schools` | Multi-school scoping |
| `roles` / `permissions` | RBAC |
| `documents` | File uploads |

---

## Permissions

### Nursing Permissions (35 permissions)

```
nursing.dashboard.view
nursing.placements.view / .create / .edit / .delete / .manage
nursing.logbooks.view / .create / .review
nursing.skills.view / .manage / .assess
nursing.competency.award
nursing.attendance.view / .mark
nursing.hours.view / .manage / .approve
nursing.instructors.manage
nursing.reference.view / .create / .approve
nursing.scenarios.view / .create
nursing.assessments.view / .create / .grade
nursing.cpd.view / .manage
nursing.reports.view / .export
nursing.settings.manage
nursing.calculators.view
nursing.study.view / .manage
```

### Roles

| Role | Description |
|---|---|
| `nursing_admin` | Manages nursing education and clinical placements |
| `nursing_instructor` | Clinical instructor for nursing students |
| `clinical_instructor` | Supervises students in clinical settings |
| `nurse` | School nurse (existing) |

---

## Workflows

### Clinical Logbook Workflow

```
Student creates entry (draft)
    ↓
Student submits (submitted)
    ↓
Instructor reviews (under_review)
    ↓
Instructor approves OR returns
    ↓
Approved record becomes locked (approved)
```

- Approved entries cannot be edited
- Corrections use an auditable correction workflow
- Clinical hours are automatically created when logbook is approved

### Skill Competency Workflow

```
Student learns skill (learning)
    ↓
Student observed performing (observed)
    ↓
Student assisted (assisted)
    ↓
Student performed supervised (performed_supervised)
    ↓
Instructor awards competency (competent) -- ONLY instructors
```

- Students CANNOT mark themselves as competent
- Only authorized instructors can award competency
- Remediation can be required if skill is not met

### Placement Workflow

```
Admin creates placement (planned)
    ↓
Placement becomes active (active)
    ↓
Students complete clinical hours
    ↓
Placement completed (completed)
```

---

## File Structure

```
Modules/Nursing/
├── composer.json
├── module.json
├── config/config.php
├── Database/
│   ├── Migrations/          (20 migration files)
│   └── Seeders/             (NursingDatabaseSeeder)
├── Http/
│   ├── Controllers/         (15 controllers)
│   │   ├── Api/             (NursingApiController)
│   │   └── ...
│   └── Requests/            (Form requests)
├── Models/                  (20 Eloquent models)
├── Policies/                (3 policies)
├── Providers/
│   ├── NursingServiceProvider.php
│   └── RouteServiceProvider.php
├── routes/
│   ├── web.php
│   └── api.php
├── resources/
│   ├── views/               (Blade fallback views)
│   └── js/                  (Vue components - in main resources)
└── tests/

resources/js/Pages/Nursing/  (Vue/Inertia pages)
├── Dashboard.vue
├── Facilities/
│   ├── Index.vue
│   ├── Create.vue
│   ├── Edit.vue
│   └── Show.vue
├── Placements/
│   ├── Index.vue
│   ├── Create.vue
│   ├── Edit.vue
│   └── Show.vue
├── Logbook/
│   ├── Index.vue
│   ├── Create.vue
│   ├── Edit.vue
│   ├── Show.vue
│   └── PendingReviews.vue
├── Skills/
│   ├── Index.vue
│   ├── Show.vue
│   ├── MyProgress.vue
│   ├── Assess.vue
│   └── AssessCreate.vue
├── Hours/
│   └── Index.vue
├── Reference/
│   ├── Index.vue
│   └── Show.vue
├── Scenarios/
│   ├── Index.vue
│   ├── Show.vue
│   ├── Attempt.vue
│   └── Results.vue
├── CPD/
│   └── Index.vue
├── Calculators/
│   └── Index.vue
├── Study/
│   ├── Index.vue
│   ├── Flashcards.vue
│   ├── Quizzes.vue
│   └── Assistant.vue
├── Instructor/
│   ├── Dashboard.vue
│   ├── Students.vue
│   └── StudentDetail.vue
├── Reports/
│   ├── Index.vue
│   ├── StudentReport.vue
│   ├── ClinicalHours.vue
│   ├── SkillsCompetency.vue
│   └── Attendance.vue
└── Settings/
    └── Index.vue
```

---

## Installation

1. Enable the module in `modules_statuses.json`:
   ```json
   "Nursing": true
   ```

2. Run migrations:
   ```bash
   php artisan migrate
   ```

3. Run seeders:
   ```bash
   php artisan db:seed --class=Modules\\Nursing\\Database\\Seeders\\NursingDatabaseSeeder
   php artisan db:seed --class=Database\\Seeders\\SystemPermissionsSeeder
   php artisan db:seed --class=Database\\Seeders\\CoreSeeder
   php artisan db:seed --class=Database\\Seeders\\AdminPermissionsSeeder
   ```

4. Build frontend assets:
   ```bash
   npm run build
   ```

---

## Configuration

All settings are configurable via `config/nursing.php` or the Settings page in the UI.

| Setting | Default | Description |
|---|---|---|
| `clinical_hours.required_per_placement` | 200 | Hours required per placement |
| `clinical_hours.required_per_semester` | 400 | Hours required per semester |
| `clinical_hours.max_hours_per_day` | 12 | Max hours per day |
| `attendance.passing_percentage` | 80 | Passing attendance % |
| `logbook.allow_backdating_days` | 7 | Days allowed for backdating |
| `cpd.target_hours_per_year` | 40 | CPD target hours |
| `files.allowed_types` | pdf,jpg,png,doc,docx | Allowed file types |
| `files.max_upload_size` | 10240 | Max upload size (KB) |

---

## Security

### Authorization
- All controllers use policy-based authorization
- Students can only access their own records
- Instructors can only access assigned students
- Admins access according to permissions
- Server-side validation on all endpoints

### IDOR Prevention
- Every controller method checks `school_id` scoping
- Student logbook access verified against authenticated user
- Instructor access verified against placement assignment

### Data Protection
- No patient-identifiable data stored in logbooks
- Approved logbook entries are locked (immutable)
- All corrections create audit trail entries
- File uploads validated for type and size

### Audit Trail
- All clinical actions logged via `nursing_audit_logs`
- Tracks: user, action, record, timestamp, old/new values
- IP address and user agent recorded

---

## Testing

### Manual Tests
1. **Student**: Create logbook entry, submit for review, view skills progress
2. **Instructor**: Review logbook, approve/return, assess skills, view students
3. **Admin**: Manage facilities, placements, settings, view reports
4. **Authorization**: Verify students cannot access other students' data
5. **Mobile**: Verify responsive design on mobile devices

### Automated Tests
- Run `composer test` for backend tests
- Run `npm run build` for frontend compilation
- Run `npm run lint` for code quality

---

## Future Expansion

### Potential Additions
- Google Drive integration for document storage
- Gmail integration for notifications
- Google Calendar for placement scheduling
- Google Sheets for report export
- Offline-first clinical logbook (PWA)
- Push notifications for mobile
- Multi-language support
- Advanced analytics dashboard
- Integration with hospital information systems
- Continuing education credits tracking
- Peer review system
- Student portfolio builder

### Content Governance
- Draft → Under Review → Approved → Published → Archived
- Only authorized reviewers can approve clinical content
- Version tracking for all reference materials
- Review date tracking for content freshness
