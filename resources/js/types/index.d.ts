export interface User {
    id: number;
    name: string;
    email: string;
    email_verified_at?: string;
    school_id?: number;
    primary_role_id?: number;
    phone?: string;
    avatar?: string;
    roles?: Role[];
    permissions?: Permission[];
    hasPermission?: (permission: string) => boolean;
    hasAnyRole?: (roles: string[]) => boolean;
    hasAnyPermission?: (permissions: string[]) => boolean;
}

export interface Role {
    id: number;
    name: string;
    display_name?: string;
}

export interface Permission {
    id: number;
    name: string;
    display_name?: string;
}

export interface School {
    id: number;
    name: string;
    slug: string;
}

export interface PageProps<T extends Record<string, unknown> = Record<string, unknown>> extends T {
    auth: {
        user: User;
    };
    flash?: {
        success?: string;
        error?: string;
        info?: string;
    };
}

export interface PaginatedData<T> {
    data: T[];
    current_page: number;
    last_page: number;
    per_page: number;
    total: number;
    from: number | null;
    to: number | null;
}

export interface Student {
    id: number;
    user_id: number;
    student_id: string;
    name: string;
    admission_number: string;
    school_id: number;
    class_id?: number;
    gender: string;
    phone?: string;
    email?: string;
    user?: User;
}

export interface Staff {
    id: number;
    user_id: number;
    staff_id: string;
    first_name: string;
    last_name: string;
    email: string;
    phone?: string;
    job_title?: string;
    department_id?: number;
    school_id: number;
    status: string;
    user?: User;
}

export interface Facility {
    id: number;
    school_id: number;
    name: string;
    code?: string;
    type: string;
    address?: string;
    city?: string;
    state?: string;
    country?: string;
    phone?: string;
    email?: string;
    contact_person?: string;
    contact_person_phone?: string;
    notes?: string;
    is_active: boolean;
    departments_count?: number;
    wards_count?: number;
    departments?: FacilityDepartment[];
    wards?: Ward[];
}

export interface FacilityDepartment {
    id: number;
    facility_id: number;
    name: string;
    code?: string;
    description?: string;
    is_active: boolean;
    wards?: Ward[];
}

export interface Ward {
    id: number;
    facility_id: number;
    department_id: number;
    name: string;
    code?: string;
    floor?: string;
    capacity?: number;
    description?: string;
    is_active: boolean;
}

export interface Placement {
    id: number;
    school_id: number;
    student_id: number;
    facility_id: number;
    department_id: number;
    ward_id?: number;
    instructor_id?: number;
    start_date: string;
    end_date: string;
    required_hours: number;
    status: 'planned' | 'active' | 'completed' | 'cancelled';
    notes?: string;
    student?: Student;
    facility?: Facility;
    department?: FacilityDepartment;
    ward?: Ward;
    instructor?: Staff;
    completed_hours?: number;
    remaining_hours?: number;
    progress_percentage?: number;
}

export interface LogbookEntry {
    id: number;
    school_id: number;
    student_id: number;
    placement_id: number;
    date: string;
    shift?: string;
    hours: number;
    activity?: string;
    procedure?: string;
    learning_objective?: string;
    reflection?: string;
    challenges?: string;
    evidence?: string;
    status: 'draft' | 'submitted' | 'under_review' | 'approved' | 'returned' | 'rejected';
    submitted_at?: string;
    reviewed_at?: string;
    reviewed_by?: number;
    approved_at?: string;
    approved_by?: number;
    review_comments?: string;
    student?: Student;
    placement?: Placement;
    reviewer?: User;
    approver?: User;
}

export interface SkillCategory {
    id: number;
    name: string;
    code?: string;
    description?: string;
    sort_order: number;
    is_active: boolean;
    skills_count?: number;
    skills?: Skill[];
}

export interface Skill {
    id: number;
    category_id: number;
    name: string;
    code?: string;
    description?: string;
    learning_objectives?: string[];
    equipment?: string[];
    procedure_reference?: string;
    safety_considerations?: string;
    documentation_requirements?: string;
    assessment_criteria?: string;
    references?: string[];
    version: number;
    review_date?: string;
    status: string;
    author_id?: number;
    reviewer_id?: number;
    is_active: boolean;
    category?: SkillCategory;
    author?: User;
    reviewer?: User;
}

export interface StudentSkill {
    id: number;
    student_id: number;
    skill_id: number;
    placement_id?: number;
    status: 'not_started' | 'learning' | 'observed' | 'assisted' | 'performed_supervised' | 'competent' | 'remediation_required';
    awarded_by?: number;
    awarded_at?: string;
    notes?: string;
    student?: Student;
    skill?: Skill;
    placement?: Placement;
    awardedBy?: User;
}

export interface SkillAssessment {
    id: number;
    student_id: number;
    skill_id: number;
    student_skill_id?: number;
    assessor_id: number;
    rubric_id?: number;
    criteria_scores?: Record<string, number>;
    score?: number;
    maximum_score?: number;
    percentage?: number;
    result: 'pass' | 'fail' | 'conditional' | 'pending';
    feedback?: string;
    recommendation?: string;
    assessed_at?: string;
    student?: Student;
    skill?: Skill;
    assessor?: User;
}

export interface ClinicalHours {
    id: number;
    school_id: number;
    student_id: number;
    placement_id: number;
    date: string;
    hours: number;
    shift?: string;
    status: 'pending' | 'approved' | 'rejected';
    approved_by?: number;
    approved_at?: string;
    notes?: string;
    logbook_id?: number;
    student?: Student;
    placement?: Placement;
}

export interface ReferenceCategory {
    id: number;
    name: string;
    slug: string;
    description?: string;
    parent_id?: number;
    sort_order: number;
    is_active: boolean;
    articles_count?: number;
    articles?: ReferenceArticle[];
    children?: ReferenceCategory[];
}

export interface ReferenceArticle {
    id: number;
    category_id: number;
    title: string;
    slug: string;
    content: string;
    author?: string;
    author_id?: number;
    reviewer?: string;
    reviewer_id?: number;
    source?: string;
    version: number;
    review_date?: string;
    status: string;
    is_featured: boolean;
    view_count: number;
    tags?: string[];
    category?: ReferenceCategory;
}

export interface Scenario {
    id: number;
    title: string;
    description?: string;
    difficulty: 'beginner' | 'intermediate' | 'advanced';
    category: string;
    patient_information?: Record<string, unknown>;
    patient_history?: Record<string, unknown>;
    observations?: Record<string, unknown>;
    learning_objectives?: string[];
    references?: string[];
    status: string;
    author_id?: number;
    version: number;
    is_active: boolean;
    author?: User;
    questions?: ScenarioQuestion[];
}

export interface ScenarioQuestion {
    id: number;
    scenario_id: number;
    order: number;
    question: string;
    choices: string[];
    correct_choice_index: number;
    explanation?: string;
    learning_point?: string;
}

export interface ScenarioAttempt {
    id: number;
    scenario_id: number;
    student_id: number;
    answers?: Record<number, number>;
    score: number;
    total_questions: number;
    percentage: number;
    is_completed: boolean;
    started_at?: string;
    completed_at?: string;
    scenario?: Scenario;
    student?: Student;
}

export interface CpdActivity {
    id: number;
    school_id: number;
    user_id: number;
    activity_name: string;
    provider?: string;
    type: string;
    activity_date: string;
    hours: number;
    certificate_path?: string;
    status: 'pending' | 'approved' | 'rejected';
    approved_by?: number;
    approved_at?: string;
    notes?: string;
    rejection_reason?: string;
    user?: User;
    approvedBy?: User;
}

export interface NursingSetting {
    id: number;
    school_id: number;
    key: string;
    value?: string;
    type: string;
}

export interface NursingAuditLog {
    id: number;
    school_id: number;
    user_id: number;
    action: string;
    auditable_type: string;
    auditable_id: number;
    old_values?: Record<string, unknown>;
    new_values?: Record<string, unknown>;
    description?: string;
    ip_address?: string;
    user?: User;
    created_at: string;
}
