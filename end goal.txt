Bhai, **bilkul sahi approach hai!** Yeh multi-phase distributed development ka prompt excellent hai. Ab main aapke **ISO/IEC 27001 Compliance Assessment Tool** ke liye exactly same format me comprehensive master prompt bana deta hoon.

---

# 🔐 ISO/IEC 27001 Compliance Assessment Tool
## Multi-Phase Project Breakdown & AI Chat Coordination Prompt

---

# MASTER PROMPT FOR DISTRIBUTED DEVELOPMENT

```markdown
I need to build an enterprise-grade ISO/IEC 27001:2022 Compliance Assessment SaaS platform across multiple AI chat sessions. Each phase must be self-contained with clear deliverables, dependencies, handoff protocols, and testing requirements.

## PROJECT NAME: ISMS Compliance Assessment Platform

## TECH STACK (ALL PHASES - FIXED)
- Laravel 11.x, PHP 8.2+, MySQL 8.0+ (XAMPP local)
- Blade Templates + Livewire 3 + Alpine.js
- Tailwind CSS 4
- Laravel Breeze for authentication
- Spatie Laravel Permission for RBAC
- Maatwebsite Excel for exports
- Barryvdh DomPDF for PDF generation
- Owen-it Laravel Auditing for audit trails
- Chart.js for visualizations

## ARCHITECTURE RULE (CRITICAL - ALL PHASES)
**Business logic NEVER in controllers or views.**
- ALL compliance calculations in Service classes (app/Services/)
- ALL database operations in Repository pattern (app/Repositories/)
- ALL data transfer via DTOs (app/DTOs/)
- ALL ISO 27001 logic in dedicated services (app/Services/Compliance/)
- Controllers only: validate request → call service → return response
- This ensures enterprise scalability and future API readiness

## MULTI-TENANCY RULE (CRITICAL - ALL PHASES)
- Single database, shared schema architecture
- ALL tenant data MUST have tenant_id foreign key
- Global scopes for automatic tenant isolation
- NEVER query without tenant scope
- Tenant context from authenticated user

---

# PHASE 1: FOUNDATION & MULTI-TENANCY CORE
**Duration: 1-2 AI Chat Sessions**
**Dependencies: None (Starting point)**

## What MUST be built:

### 1. Laravel Project Setup
```bash
laravel new isms-tool
cd isms-tool
composer require laravel/breeze --dev
php artisan breeze:install blade
composer require spatie/laravel-permission
composer require maatwebsite/excel
composer require barryvdh/laravel-dompdf
composer require owen-it/laravel-auditing
```

### 2. Database Migrations (Complete Schema)

#### Core Tables:
```php
// tenants table
Schema::create('tenants', function (Blueprint $table) {
    $table->id();
    $table->string('name');
    $table->string('slug')->unique();
    $table->string('logo_path')->nullable();
    $table->string('industry')->nullable();
    $table->enum('size', ['1-10', '11-50', '51-200', '201-1000', '1000+']);
    $table->string('contact_email');
    $table->string('contact_phone')->nullable();
    $table->string('address')->nullable();
    $table->string('city')->nullable();
    $table->string('state')->nullable();
    $table->string('country')->default('India');
    $table->string('pincode')->nullable();
    $table->json('settings')->nullable();
    $table->timestamps();
    $table->softDeletes();
});

// tenant_user pivot
Schema::create('tenant_user', function (Blueprint $table) {
    $table->id();
    $table->foreignId('tenant_id')->constrained()->cascadeOnDelete();
    $table->foreignId('user_id')->constrained()->cascadeOnDelete();
    $table->string('role')->default('viewer'); // owner, admin, auditor, assessor, viewer
    $table->json('permissions')->nullable();
    $table->timestamps();
    $table->unique(['tenant_id', 'user_id']);
});

// invitations table
Schema::create('invitations', function (Blueprint $table) {
    $table->id();
    $table->foreignId('tenant_id')->constrained()->cascadeOnDelete();
    $table->string('email');
    $table->string('token')->unique();
    $table->string('role')->default('viewer');
    $table->timestamp('expires_at');
    $table->timestamp('accepted_at')->nullable();
    $table->timestamps();
});

// Modify users table - add avatar, is_active
```

#### ISO 27001 Structure Tables:
```php
// domains table (14 clauses + 4 Annex A themes)
Schema::create('domains', function (Blueprint $table) {
    $table->id();
    $table->string('code')->unique(); // e.g., 'A.5', 'A.6', '4.1'
    $table->string('name');
    $table->text('description');
    $table->enum('clause_type', ['main_clause', 'annex_a']);
    $table->integer('sort_order');
    $table->timestamps();
});

// controls table (93 Annex A + 14 main clause controls)
Schema::create('controls', function (Blueprint $table) {
    $table->id();
    $table->foreignId('domain_id')->constrained()->cascadeOnDelete();
    $table->string('control_id')->unique(); // e.g., '5.1', 'A.5.1'
    $table->string('title');
    $table->text('description');
    $table->text('implementation_guidance')->nullable();
    $table->enum('category', ['organizational', 'people', 'physical', 'technological']);
    $table->string('control_type')->nullable(); // preventive, detective, corrective
    $table->boolean('is_active')->default(true);
    $table->timestamps();
});

// assessment table
Schema::create('assessments', function (Blueprint $table) {
    $table->id();
    $table->foreignId('tenant_id')->constrained()->cascadeOnDelete();
    $table->string('name');
    $table->text('description')->nullable();
    $table->text('scope')->nullable();
    $table->date('start_date');
    $table->date('target_date')->nullable();
    $table->date('completed_date')->nullable();
    $table->enum('status', ['draft', 'in_progress', 'completed', 'archived']);
    $table->integer('progress_percentage')->default(0);
    $table->foreignId('lead_assessor_id')->constrained('users');
    $table->json('team_members')->nullable();
    $table->timestamps();
    $table->softDeletes();
});

// assessment_responses table
Schema::create('assessment_responses', function (Blueprint $table) {
    $table->id();
    $table->foreignId('assessment_id')->constrained()->cascadeOnDelete();
    $table->foreignId('control_id')->constrained()->cascadeOnDelete();
    $table->enum('status', ['compliant', 'non_compliant', 'partially_compliant', 'not_applicable', 'not_assessed']);
    $table->integer('maturity_level')->nullable(); // 0-5
    $table->text('evidence_notes')->nullable();
    $table->text('gap_description')->nullable();
    $table->foreignId('assigned_to')->nullable()->constrained('users');
    $table->date('due_date')->nullable();
    $table->foreignId('assessed_by')->nullable()->constrained('users');
    $table->timestamp('assessed_at')->nullable();
    $table->timestamps();
    $table->unique(['assessment_id', 'control_id']);
});
```

### 3. Seeders (CRITICAL - Complete ISO 27001:2022 Data)

#### Domain Seeder:
- 4 main clauses (4, 5, 6, 7, 8, 9, 10 - organizational)
- 4 Annex A themes (A.5 Organizational, A.6 People, A.7 Physical, A.8 Technological)

#### Control Seeder (ALL 93 Controls):
```php
// Example structure
[
    'control_id' => 'A.5.1',
    'domain' => 'A.5',
    'title' => 'Policies for information security',
    'description' => 'Information security policy and topic-specific policies should be defined, approved by management, published, communicated to and acknowledged by relevant personnel and relevant interested parties, and reviewed at planned intervals and if significant changes occur.',
    'category' => 'organizational',
    'control_type' => 'preventive'
]
```

### 4. Models with Relationships & Global Scopes

```php
// TenantScope for automatic filtering
class TenantScope implements Scope
{
    public function apply(Builder $builder, Model $model)
    {
        if (auth()->check() && auth()->user()->current_tenant_id) {
            $builder->where('tenant_id', auth()->user()->current_tenant_id);
        }
    }
}

// Models: Tenant, User, Domain, Control, Assessment, AssessmentResponse
// Each model with proper relationships and tenant scope
```

### 5. Authentication & Tenant Context

- Login/Register (Breeze)
- After login, check if user has tenant
- If no tenant → redirect to create/join organization
- Store current_tenant_id in session
- Tenant switcher in header

### 6. Repository Pattern Setup

```php
// app/Repositories/Contracts/
TenantRepositoryInterface.php
UserRepositoryInterface.php
AssessmentRepositoryInterface.php
ControlRepositoryInterface.php

// app/Repositories/
BaseRepository.php
TenantRepository.php
UserRepository.php
AssessmentRepository.php
ControlRepository.php
```

### 7. DTOs

```php
// app/DTOs/
TenantData.php
UserData.php
AssessmentData.php
ControlData.php
AssessmentResponseData.php
```

### 8. Middleware

```php
// EnsureTenantSelected.php - redirects to tenant setup if no tenant
// CheckTenantPermission.php - checks user has permission in current tenant
// ValidateTenantAccess.php - ensures user belongs to tenant
```

### 9. Service Layer Skeletons

```php
// app/Services/
Tenant/TenantService.php
Tenant/InvitationService.php
User/UserService.php
Assessment/AssessmentService.php
Assessment/ResponseService.php
Compliance/ComplianceCalculator.php
Risk/RiskCalculationService.php
```

## What NOT to build (Phase 2+):
- NO assessment wizard UI
- NO risk management
- NO document management
- NO SoA generator
- NO reporting

## Deliverables:
- Complete multi-tenant foundation
- User can register → create/join organization → access dashboard
- Role assignment working (owner, admin, assessor, viewer)
- Invitation system (email or direct link for local)
- All ISO 27001 data seeded (domains + 93 controls)
- Dashboard shows all domains with control counts

## Testing Required:
```php
// tests/Feature/
TenantCreationTest.php
UserInvitationTest.php
RoleAssignmentTest.php
TenantIsolationTest.php // User A cannot see User B's data
ControlSeederTest.php // 93 controls seeded correctly
```

## Handoff to Phase 2:
```
- Database migrated and seeded
- Test tenant: "Demo Organization" (tenant_id=1)
- Test users: owner@demo.com, assessor@demo.com, viewer@demo.com (password: password)
- All 14 domains + 93 controls available
- Repository pattern working for Tenant, User, Assessment
- Tenant isolation verified
```

---

# PHASE 2: ASSESSMENT ENGINE & WORKFLOW
**Duration: 2 AI Chat Sessions**
**Dependencies: Phase 1 complete**

## SUB-PHASE 2A: ASSESSMENT MANAGEMENT

### What MUST be built:

1. **Assessment CRUD Operations**
```php
// AssessmentController (thin)
- index() // list all assessments with filters
- create() // show creation form
- store(StoreAssessmentRequest $request)
- show(Assessment $assessment) // dashboard with progress
- edit(Assessment $assessment)
- update(UpdateAssessmentRequest $request, Assessment $assessment)
- destroy(Assessment $assessment) // soft delete with confirmation
- startAssessment(Assessment $assessment) // change status to in_progress
- completeAssessment(Assessment $assessment) // final validation
```

2. **AssessmentService Methods**
```php
class AssessmentService
{
    public function createAssessment(AssessmentData $data): Assessment
    public function updateAssessment(int $id, AssessmentData $data): Assessment
    public function calculateProgress(Assessment $assessment): int
    public function assignTeamMember(Assessment $assessment, int $userId, string $role): void
    public function removeTeamMember(Assessment $assessment, int $userId): void
    public function validateCompletion(Assessment $assessment): bool
}
```

3. **Livewire Components**
```php
// AssessmentWizard.php (multi-step)
- Step 1: Basic Info (name, description, scope)
- Step 2: Team Assignment (select users, assign roles)
- Step 3: Review & Create
// AssessmentList.php (with filters)
- Search by name/status
- Filter by date range
// AssessmentProgress.php (real-time dashboard)
- Overall percentage
- Domain-wise progress
```

4. **Blade Views**
```
assessments/
├── index.blade.php (list with cards)
├── create.blade.php (wizard)
├── show.blade.php (overview dashboard)
├── edit.blade.php (wizard in edit mode)
└── partials/
    ├── progress-bar.blade.php
    ├── team-members.blade.php
    └── domain-summary.blade.php
```

5. **Database Additions**
```php
// assessment_team table
Schema::create('assessment_team', function (Blueprint $table) {
    $table->id();
    $table->foreignId('assessment_id')->constrained()->cascadeOnDelete();
    $table->foreignId('user_id')->constrained()->cascadeOnDelete();
    $table->string('role')->default('assessor'); // lead_assessor, assessor, reviewer
    $table->timestamps();
});
```

## SUB-PHASE 2B: CONTROL ASSESSMENT INTERFACE

### What MUST be built:

1. **Response Management**
```php
// AssessmentResponseController
- showDomain(Assessment $assessment, Domain $domain) // list controls in domain
- updateResponse(UpdateResponseRequest $request, AssessmentResponse $response)
- bulkUpdate(BulkUpdateRequest $request) // mark multiple controls
- assignControl(AssessmentResponse $response) // assign to user
- uploadEvidence(AssessmentResponse $response) // file upload
```

2. **ResponseService Methods**
```php
class ResponseService
{
    public function initializeResponses(Assessment $assessment): void // create all 107 responses
    public function updateResponse(int $responseId, array $data): AssessmentResponse
    public function bulkUpdate(int $assessmentId, array $controlIds, string $status): void
    public function calculateMaturityScore(Assessment $assessment): float
    public function getDomainCompliance(Domain $domain, Assessment $assessment): array
    public function autoAssignControls(Assessment $assessment): void // round-robin assignment
}
```

3. **Assessment Interface (Livewire)**
```php
// ControlAssessment.php (main component)
Properties:
- $assessment
- $domain (current)
- $controls (paginated)
- $responses (keyed by control_id)

Actions:
- updateStatus($controlId, $status)
- updateMaturity($controlId, $level)
- addNote($controlId, $note)
- uploadEvidence($controlId)
- assignTo($controlId, $userId)
- markNotApplicable($controlId)

// EvidenceUpload.php (file upload component)
- Validate file type (pdf, doc, xls, png, jpg)
- Max size 10MB
- Store in storage/app/evidence/{tenant_id}/{assessment_id}/
```

4. **Maturity Model**
```
Level 0: Not Implemented
Level 1: Initial/Ad-hoc
Level 2: Repeatable but informal
Level 3: Defined and documented
Level 4: Managed and measured
Level 5: Optimized/Continuous improvement
```

5. **Compliance Status**
- ✅ Compliant (green)
- ❌ Non-Compliant (red)
- ⚠️ Partially Compliant (amber)
- ⊘ Not Applicable (gray) - requires justification
- ○ Not Assessed (default)

6. **Views**
```
assessments/
├── conduct/
│   ├── index.blade.php (domain selector)
│   ├── domain.blade.php (control list)
│   └── control-detail.blade.php (modal/slide-over)
├── evidence/
│   ├── index.blade.php
│   └── upload.blade.php
```

## What NOT to build (Phase 3+):
- NO risk management module
- NO SoA generation
- NO document management
- NO advanced reporting

## Deliverables:
- Complete assessment lifecycle
- Multi-step wizard for creation
- Domain-by-domain control assessment
- Evidence upload per control
- Team assignment and collaboration
- Real-time progress tracking
- Maturity level scoring

## Testing Required:
```php
// tests/Feature/Assessment/
AssessmentCreationTest.php
ResponseUpdateTest.php
BulkUpdateTest.php
EvidenceUploadTest.php
ProgressCalculationTest.php
TeamAssignmentTest.php
```

## Handoff to Phase 3:
```
- Assessment created: "ISO 27001 Gap Assessment 2024" (assessment_id=1)
- 107 responses initialized (93 Annex A + 14 main clauses)
- Sample responses updated for 20% of controls
- Evidence files uploaded for 5 controls
- Domain progress percentages calculated
```

---

# PHASE 3: RISK MANAGEMENT MODULE
**Duration: 2 AI Chat Sessions**
**Dependencies: Phase 2 complete**

## SUB-PHASE 3A: ASSET REGISTER & RISK IDENTIFICATION

### What MUST be built:

1. **Database Migrations**
```php
// assets table
Schema::create('assets', function (Blueprint $table) {
    $table->id();
    $table->foreignId('tenant_id')->constrained()->cascadeOnDelete();
    $table->string('name');
    $table->string('asset_type'); // hardware, software, data, people, facility, service
    $table->string('owner')->nullable();
    $table->string('department')->nullable();
    $table->string('location')->nullable();
    $table->integer('confidentiality_rating')->default(3); // 1-5
    $table->integer('integrity_rating')->default(3); // 1-5
    $table->integer('availability_rating')->default(3); // 1-5
    $table->text('description')->nullable();
    $table->timestamps();
    $table->softDeletes();
});

// risk_assessments table
Schema::create('risk_assessments', function (Blueprint $table) {
    $table->id();
    $table->foreignId('tenant_id')->constrained()->cascadeOnDelete();
    $table->foreignId('asset_id')->constrained()->cascadeOnDelete();
    $table->string('threat');
    $table->string('vulnerability');
    $table->integer('likelihood')->default(3); // 1-5
    $table->integer('impact')->default(3); // 1-5
    $table->integer('risk_score'); // calculated
    $table->string('risk_level'); // low, medium, high, critical
    $table->string('treatment'); // accept, mitigate, transfer, avoid
    $table->text('treatment_description')->nullable();
    $table->integer('residual_likelihood')->nullable();
    $table->integer('residual_impact')->nullable();
    $table->integer('residual_score')->nullable();
    $table->string('residual_level')->nullable();
    $table->foreignId('risk_owner')->nullable()->constrained('users');
    $table->string('status')->default('identified'); // identified, under_review, accepted, in_treatment, closed
    $table->date('review_date')->nullable();
    $table->timestamps();
    $table->softDeletes();
});

// risk_treatment_plans table
Schema::create('risk_treatment_plans', function (Blueprint $table) {
    $table->id();
    $table->foreignId('risk_assessment_id')->constrained()->cascadeOnDelete();
    $table->string('action');
    $table->text('description');
    $table->foreignId('assigned_to')->nullable()->constrained('users');
    $table->date('due_date');
    $table->string('status')->default('pending'); // pending, in_progress, completed, overdue
    $table->integer('cost_estimate')->nullable();
    $table->timestamps();
});
```

2. **Risk Calculation Service (CRITICAL)**
```php
class RiskCalculationService
{
    public function calculateRiskScore(int $likelihood, int $impact): int
    {
        return $likelihood * $impact; // 1-25
    }
    
    public function determineRiskLevel(int $score): string
    {
        // 1-4: Low (green)
        // 5-9: Medium (yellow)
        // 10-16: High (orange)
        // 17-25: Critical (red)
    }
    
    public function calculateResidualRisk(RiskAssessment $risk, array $controls): array
    {
        // Reduce likelihood/impact based on implemented controls
    }
    
    public function generateRiskHeatmap(array $risks): array
    {
        // Return data for 5x5 heatmap visualization
    }
}
```

3. **Asset Management**
```php
// AssetController (thin)
- index() // asset register with search/filter
- create() / store()
- edit() / update()
- destroy() // soft delete

// AssetService
public function createAsset(AssetData $data): Asset
public function calculateAssetValue(Asset $asset): int // based on CIA ratings
public function identifyCriticalAssets(): Collection // CIA >= 4 in any dimension
public function getAssetsByType(string $type): Collection
```

4. **Risk CRUD**
```php
// RiskController
- index() // risk register with filters
- create() / store()
- show(RiskAssessment $risk) // detail with treatment plan
- edit() / update()
- destroy()

// RiskService
public function createRisk(RiskData $data): RiskAssessment
public function updateRisk(int $id, RiskData $data): RiskAssessment
public function calculateRiskLevel(RiskAssessment $risk): string
public function applyTreatment(RiskAssessment $risk, string $treatment): void
public function reviewRisk(RiskAssessment $risk, array $updates): void
```

## SUB-PHASE 3B: RISK VISUALIZATION & REPORTING

### What MUST be built:

1. **Risk Dashboard (Livewire)**
```php
// RiskDashboard.php
Properties:
- $risks (all)
- $heatmapData
- $criticalRisks
- $treatmentPending

Computed:
- getRiskDistribution() // count by level
- getTreatmentStats() // count by treatment type
- getTrendData() // risk over time (if multiple assessments)
```

2. **Risk Heatmap (Chart.js)**
- 5x5 matrix visualization
- Color-coded cells (green → red)
- Hover tooltips with risk details
- Click to drill down

3. **Risk Reports (Excel Export)**
```php
// RiskReportExport.php (Maatwebsite Excel)
- Export all risks with details
- Include treatment plans
- Summary sheet with statistics
- Color coding in Excel
```

4. **Treatment Plan Workflow**
- Assign treatment actions
- Set due dates
- Track completion status
- Automated reminders (pending in Phase 5)

## What NOT to build (Phase 4+):
- NO SoA generator
- NO document management
- NO email notifications
- NO advanced analytics

## Deliverables:
- Complete asset register
- Risk identification and assessment
- Automated risk scoring
- Risk heatmap visualization
- Treatment plan tracking
- Risk register export

## Testing Required:
```php
// tests/Feature/Risk/
AssetCRUDTest.php
RiskCreationTest.php
RiskScoreCalculationTest.php
RiskLevelDeterminationTest.php
TreatmentPlanTest.php
RiskExportTest.php
```

## Handoff to Phase 4:
```
- 10 sample assets created
- 15 risks identified and assessed
- Risk scores calculated
- 5 risks with treatment plans
- Heatmap data available
```

---

# PHASE 4: STATEMENT OF APPLICABILITY (SoA)
**Duration: 1 AI Chat Session**
**Dependencies: Phase 2 & 3 complete**

## What MUST be built:

### 1. SoA Generation Engine
```php
class StatementOfApplicabilityService
{
    public function generateSoA(Assessment $assessment): SoA
    {
        // Collect all assessment responses
        // Map to controls
        // Determine applicability based on:
        // - Business requirements
        // - Legal/regulatory requirements
        // - Risk assessment results
        // - Contractual obligations
    }
    
    public function determineApplicability(Control $control, Assessment $assessment): bool
    {
        // Logic to suggest applicability
        // Returns true/false with reasons
    }
    
    public function generateJustification(Control $control, bool $applicable): string
    {
        // Auto-generate justification text
        // Manual override available
    }
    
    public function getImplementationStatus(Control $control, Assessment $assessment): string
    {
        // Map from assessment response
        // fully_implemented, partially_implemented, not_implemented, not_applicable
    }
}
```

### 2. Database Migration
```php
// soa_entries table
Schema::create('soa_entries', function (Blueprint $table) {
    $table->id();
    $table->foreignId('assessment_id')->constrained()->cascadeOnDelete();
    $table->foreignId('control_id')->constrained()->cascadeOnDelete();
    $table->boolean('applicable')->default(true);
    $table->text('justification')->nullable();
    $table->enum('implementation_status', ['fully_implemented', 'partially_implemented', 'not_implemented', 'not_applicable']);
    $table->text('implementation_description')->nullable();
    $table->text('exclusion_reason')->nullable();
    $table->foreignId('approved_by')->nullable()->constrained('users');
    $table->timestamp('approved_at')->nullable();
    $table->timestamps();
    $table->unique(['assessment_id', 'control_id']);
});

// soa_versions table
Schema::create('soa_versions', function (Blueprint $table) {
    $table->id();
    $table->foreignId('assessment_id')->constrained()->cascadeOnDelete();
    $table->string('version');
    $table->json('data_snapshot');
    $table->foreignId('created_by')->constrained('users');
    $table->timestamps();
});
```

### 3. SoA Controller & Views
```php
// SoAController
- index() // list all SoA versions
- generate(Assessment $assessment) // generate new SoA
- show(SoAVersion $version) // view SoA
- approve(SoAEntry $entry) // approve individual entry
- bulkApprove(Assessment $assessment) // approve all
- export(SoAVersion $version) // PDF/Excel export
- compare(SoAVersion $v1, SoAVersion $v2) // version comparison
```

### 4. SoA Views
```
soa/
├── index.blade.php (version list)
├── generate.blade.php (SoA wizard - 3 steps)
├── show.blade.php (SoA view with filters)
├── compare.blade.php (side-by-side comparison)
└── partials/
    ├── control-row.blade.php
    ├── applicability-toggle.blade.php
    └── approval-status.blade.php
```

### 5. PDF Export (DomPDF)
```php
// SoAPdfExport.php
- Professional SoA document
- Cover page with company details
- Table of contents
- Control-wise applicability with justifications
- Approval signatures section
- Document control information (version, date, owner)
```

### 6. Excel Export (Maatwebsite)
```php
// SoAExcelExport.php
- Sheet 1: Summary (counts by domain)
- Sheet 2: Full SoA (all controls)
- Sheet 3: Exclusions (non-applicable controls)
- Sheet 4: Implementation status
```

## What NOT to build (Phase 5+):
- NO document management
- NO advanced reporting
- NO email notifications

## Deliverables:
- One-click SoA generation
- Applicability determination with justifications
- Version control for SoA
- PDF & Excel export
- Approval workflow
- Version comparison

## Testing Required:
```php
// tests/Feature/SoA/
SoAGenerationTest.php
ApplicabilityDeterminationTest.php
JustificationGenerationTest.php
SoAExportTest.php
ApprovalWorkflowTest.php
VersionComparisonTest.php
```

## Handoff to Phase 5:
```
- SoA generated for "ISO 27001 Gap Assessment 2024"
- 85 controls applicable, 8 not applicable
- Justifications generated for all
- Version 1.0 approved
- PDF export tested
```

---

# PHASE 5: DOCUMENT MANAGEMENT SYSTEM
**Duration: 1-2 AI Chat Sessions**
**Dependencies: Phase 1-4 complete**

## What MUST be built:

### 1. Database Migrations
```php
// documents table
Schema::create('documents', function (Blueprint $table) {
    $table->id();
    $table->foreignId('tenant_id')->constrained()->cascadeOnDelete();
    $table->string('title');
    $table->string('document_type'); // policy, procedure, record, template, evidence
    $table->string('category')->nullable(); // ISMS, HR, IT, Physical, etc.
    $table->string('current_version')->default('1.0');
    $table->string('file_path');
    $table->enum('status', ['draft', 'under_review', 'approved', 'archived']);
    $table->foreignId('owner_id')->constrained('users');
    $table->foreignId('approved_by')->nullable()->constrained('users');
    $table->timestamp('approved_at')->nullable();
    $table->date('review_date')->nullable(); // next review date
    $table->text('description')->nullable();
    $table->json('tags')->nullable();
    $table->timestamps();
    $table->softDeletes();
});

// document_versions table
Schema::create('document_versions', function (Blueprint $table) {
    $table->id();
    $table->foreignId('document_id')->constrained()->cascadeOnDelete();
    $table->string('version');
    $table->string('file_path');
    $table->text('changes')->nullable();
    $table->foreignId('created_by')->constrained('users');
    $table->timestamps();
});

// document_reviews table
Schema::create('document_reviews', function (Blueprint $table) {
    $table->id();
    $table->foreignId('document_id')->constrained()->cascadeOnDelete();
    $table->foreignId('reviewer_id')->constrained('users');
    $table->enum('status', ['pending', 'approved', 'rejected', 'changes_requested']);
    $table->text('comments')->nullable();
    $table->timestamp('reviewed_at')->nullable();
    $table->timestamps();
});

// document_control_map (link documents to controls)
Schema::create('document_control_map', function (Blueprint $table) {
    $table->id();
    $table->foreignId('document_id')->constrained()->cascadeOnDelete();
    $table->foreignId('control_id')->constrained()->cascadeOnDelete();
    $table->timestamps();
    $table->unique(['document_id', 'control_id']);
});
```

### 2. Document Management Service
```php
class DocumentService
{
    public function uploadDocument(DocumentData $data, UploadedFile $file): Document
    public function createNewVersion(Document $document, UploadedFile $file, string $version): DocumentVersion
    public function submitForReview(Document $document, array $reviewerIds): void
    public function approveDocument(Document $document, User $approver): void
    public function rejectDocument(Document $document, string $comments): void
    public function requestChanges(Document $document, string $comments): void
    public function archiveDocument(Document $document): void
    public function getDocumentsByControl(Control $control): Collection
    public function getDocumentsDueForReview(): Collection
    public function linkToControl(Document $document, Control $control): void
}
```

### 3. Document Controllers
```php
// DocumentController
- index() // document library with filters
- create() // upload form
- store(StoreDocumentRequest $request) // handle upload
- show(Document $document) // document details with versions
- download(Document $document) // download file
- preview(Document $document) // browser preview (if PDF/image)
- edit(Document $document)
- update(UpdateDocumentRequest $request, Document $document)
- destroy(Document $document) // soft delete

// DocumentReviewController
- submitForReview(Document $document)
- review(Document $document)
- approve(Document $document)
- reject(Document $document, Request $request)
- requestChanges(Document $document, Request $request)

// DocumentVersionController
- uploadNewVersion(Document $document, Request $request)
- versionHistory(Document $document)
- compareVersions(Document $document, $v1, $v2)
```

### 4. Policy Templates Library
```php
// Pre-loaded templates (seeded)
- Information Security Policy
- Access Control Policy
- Incident Response Procedure
- Risk Assessment Methodology
- Business Continuity Plan
- Data Classification Policy
- Acceptable Use Policy
- Supplier Security Policy
- Change Management Procedure
- Backup & Recovery Policy
```

### 5. File Storage Structure
```
storage/app/documents/
├── {tenant_id}/
│   ├── policies/
│   │   └── {document_id}/
│   │       ├── v1_0/
│   │       ├── v1_1/
│   │       └── current/
│   ├── procedures/
│   ├── records/
│   └── templates/
```

### 6. Document Views
```
documents/
├── index.blade.php (library with search/filter)
├── create.blade.php (upload form)
├── show.blade.php (document details)
├── review.blade.php (review interface)
├── versions.blade.php (version history)
└── partials/
    ├── document-card.blade.php
    ├── version-timeline.blade.php
    ├── review-status.blade.php
    └── control-links.blade.php
```

## What NOT to build (Phase 6+):
- NO advanced reporting dashboards
- NO email notifications
- NO API integration

## Deliverables:
- Complete document management with versioning
- Review workflow
- Document-control mapping
- Template library
- File storage organized

## Testing Required:
```php
// tests/Feature/Documents/
DocumentUploadTest.php
VersionControlTest.php
ReviewWorkflowTest.php
DocumentControlMappingTest.php
FileStorageTest.php
AccessControlTest.php
```

## Handoff to Phase 6:
```
- 10 documents uploaded (policies, procedures)
- 3 documents with multiple versions
- 5 documents linked to controls
- 2 documents in review workflow
```

---

# PHASE 6: REPORTING & ANALYTICS DASHBOARD
**Duration: 2 AI Chat Sessions**
**Dependencies: Phase 1-5 complete**

## What MUST be built:

### 1. Executive Dashboard (Livewire + Chart.js)
```php
// ExecutiveDashboard.php
Properties:
- $assessment
- $dateRange

Computed:
- getOverallCompliance() // percentage
- getDomainWiseCompliance() // chart data
- getMaturityDistribution() // pie chart
- getRiskSummary() // risk counts by level
- getDocumentStats() // total, pending review, overdue
- getRecentActivity() // latest audit trail entries
- getTopGaps() // most non-compliant controls
```

### 2. Compliance Reports
```php
class ComplianceReportService
{
    public function generateExecutiveSummary(Assessment $assessment): array
    public function generateDomainReport(Domain $domain, Assessment $assessment): array
    public function generateGapAnalysis(Assessment $assessment): array
    public function generateTrendAnalysis(int $tenantId, $period): array
    public function generateBenchmarkReport(int $tenantId): array // industry comparison
    public function generateAuditReadinessReport(Assessment $assessment): array
}
```

### 3. Report Types
```
1. Executive Summary Report
   - Overall compliance score
   - Key findings (top 5 gaps)
   - Risk summary
   - Recommendations

2. Domain-wise Compliance Report
   - Controls status per domain
   - Maturity levels
   - Evidence status

3. Gap Analysis Report
   - Non-compliant controls
   - Root cause analysis
   - Action items
   - Timeline for remediation

4. Risk Register Report
   - All risks with scores
   - Treatment status
   - Risk owners
   - Review dates

5. Document Status Report
   - Document inventory
   - Version control status
   - Review status
   - Upcoming reviews

6. Audit Trail Report
   - All activities
   - User-wise summary
   - Critical actions
```

### 4. Chart Components (Chart.js)
```javascript
// Charts needed:
1. Overall Compliance Gauge
2. Domain-wise Bar Chart
3. Maturity Level Distribution (Pie)
4. Risk Heatmap (Matrix)
5. Compliance Trend Line
6. Document Status Donut
7. Team Workload Bar
8. Control Status Stacked Bar
```

### 5. Export Functionality
```php
// ExportService
public function exportToPDF($reportData, $template): PDF
public function exportToExcel($reportData, $template): Excel
public function exportToCSV($reportData, $template): CSV
public function scheduleReportExport($reportType, $frequency): void
```

### 6. Dashboard Views
```
dashboard/
├── executive.blade.php (main dashboard)
├── compliance.blade.php (compliance-focused view)
├── risk.blade.php (risk-focused view)
├── documents.blade.php (document status)
├── team.blade.php (team performance)
└── reports/
    ├── generate.blade.php (report builder)
    ├── list.blade.php (saved reports)
    └── templates/
        ├── executive-summary.blade.php
        ├── gap-analysis.blade.php
        ├── risk-register.blade.php
        └── audit-readiness.blade.php
```

## What NOT to build (Phase 7+):
- NO email notifications (Phase 7)
- NO API (Phase 8)
- NO advanced AI features

## Deliverables:
- Complete executive dashboard
- 6 report types
- Chart visualizations
- Export capabilities
- Report builder interface

## Testing Required:
```php
// tests/Feature/Reports/
ExecutiveDashboardTest.php
ComplianceReportTest.php
GapAnalysisTest.php
ReportExportTest.php
ChartDataTest.php
```

## Handoff to Phase 7:
```
- Dashboard showing for "Demo Organization"
- All charts rendering with real data
- Reports export working (PDF, Excel, CSV)
- Compliance score calculated
```

---

# PHASE 7: AUDIT TRAIL, NOTIFICATIONS & ENTERPRISE FEATURES
**Duration: 2 AI Chat Sessions**
**Dependencies: Phase 1-6 complete**

## What MUST be built:

### 1. Audit Trail (Owen-it Laravel Auditing)
```php
// Configure Auditable trait on all models
// audit_trails table auto-created

// AuditTrailService
class AuditTrailService
{
    public function logActivity(string $action, $entity, array $oldValues = [], array $newValues = []): void
    public function getUserActivity(User $user, $dateRange): Collection
    public function getEntityHistory($entityType, $entityId): Collection
    public function exportAuditLog($filters): Excel
    public function getCriticalActions(): Collection // deletes, role changes, etc.
}
```

### 2. Notification System
```php
// notifications table
Schema::create('notifications', function (Blueprint $table) {
    $table->id();
    $table->foreignId('tenant_id')->constrained()->cascadeOnDelete();
    $table->foreignId('user_id')->constrained()->cascadeOnDelete();
    $table->string('type'); // assessment_created, control_assigned, risk_identified, document_review, etc.
    $table->string('title');
    $table->text('message');
    $table->json('data')->nullable();
    $table->timestamp('read_at')->nullable();
    $table->timestamps();
});

// NotificationService
class NotificationService
{
    public function sendAssessmentNotification(Assessment $assessment, string $type): void
    public function sendControlAssignment(AssessmentResponse $response, User $user): void
    public function sendRiskAlert(RiskAssessment $risk): void
    public function sendDocumentReviewRequest(Document $document, array $reviewers): void
    public function sendDueDateReminder(string $entityType, $entityId): void
    public function markAsRead(Notification $notification): void
    public function markAllAsRead(User $user): void
    public function getUnreadCount(User $user): int
}
```

### 3. Email Notifications
```php
// Mail classes
class AssessmentCreatedMail extends Mailable
class ControlAssignedMail extends Mailable
class RiskIdentifiedMail extends Mailable
class DocumentReviewMail extends Mailable
class DueDateReminderMail extends Mailable
class WeeklyDigestMail extends Mailable

// Email templates
- Professional HTML templates
- Include relevant data
- Action buttons
- Unsubscribe option
```

### 4. Scheduled Tasks (Laravel Scheduler)
```php
// app/Console/Kernel.php
protected function schedule(Schedule $schedule)
{
    // Daily
    $schedule->command('notifications:send-due-reminders')->dailyAt('09:00');
    $schedule->command('documents:check-review-dates')->daily();
    
    // Weekly
    $schedule->command('reports:send-weekly-digest')->weekly()->mondays('08:00');
    
    // Monthly
    $schedule->command('reports:generate-monthly')->monthlyOn(1, '00:00');
    $schedule->command('backup:run')->dailyAt('02:00');
}
```

### 5. Real-time Notifications (Laravel Echo + Pusher)
```php
// Broadcast events
class AssessmentCreated implements ShouldBroadcast
class ControlUpdated implements ShouldBroadcast
class RiskIdentified implements ShouldBroadcast

// Livewire integration for real-time updates
```

### 6. Advanced Enterprise Features
```php
// Multi-factor Authentication (stub)
// Single Sign-On (SAML/OAuth - stub)
// API Rate Limiting per tenant
// Data Export (GDPR compliance)
// Bulk Operations
// Advanced User Permissions
// Custom Roles Creation
```

### 7. Notification Views
```
notifications/
├── index.blade.php (notification center)
├── partials/
│   ├── notification-item.blade.php
│   ├── notification-badge.blade.php
│   └── notification-filters.blade.php
└── settings/
    ├── email-preferences.blade.php
    ├── notification-settings.blade.php
    └── digest-settings.blade.php
```

## What NOT to build (Phase 8):
- NO REST API
- NO external integrations
- NO advanced AI

## Deliverables:
- Complete audit trail
- Email + in-app notifications
- Real-time updates
- Scheduled tasks
- Notification preferences
- Enterprise features

## Testing Required:
```php
// tests/Feature/Notifications/
NotificationCreationTest.php
EmailSendingTest.php
ScheduledTasksTest.php
AuditTrailTest.php
RealTimeBroadcastTest.php
PreferencesTest.php
```

## Handoff to Phase 8:
```
- Audit trail logging all actions
- Notifications working (email + in-app)
- Scheduled tasks configured
- User preferences saved
```

---

# PHASE 8: REST API & INTEGRATIONS
**Duration: 1-2 AI Chat Sessions**
**Dependencies: Phase 1-7 complete**

## What MUST be built:

### 1. API Authentication (Laravel Sanctum)
```php
// Install Sanctum
composer require laravel/sanctum

// API routes structure
Route::prefix('api/v1')->middleware('auth:sanctum')->group(function () {
    // All API routes
});
```

### 2. API Controllers
```php
// app/Http/Controllers/Api/V1/
AuthController.php
TenantController.php
UserController.php
AssessmentController.php
ControlController.php
ResponseController.php
RiskController.php
AssetController.php
DocumentController.php
SoAController.php
ReportController.php
NotificationController.php
DashboardController.php
```

### 3. API Resources (JSON)
```php
// app/Http/Resources/
TenantResource.php
UserResource.php
AssessmentResource.php
ControlResource.php
ResponseResource.php
RiskResource.php
AssetResource.php
DocumentResource.php
SoAResource.php
NotificationResource.php
DashboardResource.php
```

### 4. API Endpoints (Complete List)
```php
// Authentication
POST /api/v1/auth/login
POST /api/v1/auth/logout
POST /api/v1/auth/refresh
GET /api/v1/auth/me

// Tenants
GET /api/v1/tenants
POST /api/v1/tenants
GET /api/v1/tenants/{id}
PUT /api/v1/tenants/{id}
DELETE /api/v1/tenants/{id}

// Assessments
GET /api/v1/assessments
POST /api/v1/assessments
GET /api/v1/assessments/{id}
PUT /api/v1/assessments/{id}
DELETE /api/v1/assessments/{id}
POST /api/v1/assessments/{id}/start
POST /api/v1/assessments/{id}/complete

// Controls & Responses
GET /api/v1/domains
GET /api/v1/domains/{id}/controls
GET /api/v1/assessments/{id}/responses
PUT /api/v1/responses/{id}
POST /api/v1/responses/bulk-update

// Risks
GET /api/v1/risks
POST /api/v1/risks
GET /api/v1/risks/{id}
PUT /api/v1/risks/{id}
DELETE /api/v1/risks/{id}

// Documents
GET /api/v1/documents
POST /api/v1/documents
GET /api/v1/documents/{id}
PUT /api/v1/documents/{id}
DELETE /api/v1/documents/{id}
GET /api/v1/documents/{id}/download

// Reports
GET /api/v1/reports/executive-summary
GET /api/v1/reports/compliance
GET /api/v1/reports/gap-analysis
GET /api/v1/reports/risk-register
POST /api/v1/reports/export

// Dashboard
GET /api/v1/dashboard/summary
GET /api/v1/dashboard/compliance-trend
GET /api/v1/dashboard/risk-summary
```

### 5. API Documentation (Swagger/OpenAPI)
```yaml
# api-docs.yaml
openapi: 3.0.0
info:
  title: ISMS Compliance API
  version: 1.0.0
  description: ISO 27001 Compliance Assessment Platform API
```

### 6. API Transformations & DTOs
```php
// Ensure all responses use DTOs
// Consistent error format
// Pagination metadata
// Rate limiting headers
// Request validation
```

### 7. Postman Collection
```json
{
  "info": {
    "name": "ISMS Compliance API",
    "schema": "https://schema.getpostman.com/json/collection/v2.1.0/collection.json"
  }
}
```

### 8. Webhook Integration (Stub)
```php
// webhooks table
// WebhookService
// Send events to external systems
```

## What NOT to build (Phase 9):
- NO deployment (that's Phase 9)
- NO monitoring setup

## Deliverables:
- Complete REST API
- Authentication tokens
- API documentation
- Postman collection
- Consistent responses
- Rate limiting

## Testing Required:
```php
// tests/Feature/Api/
AuthApiTest.php
TenantApiTest.php
AssessmentApiTest.php
RiskApiTest.php
DocumentApiTest.php
ReportApiTest.php
RateLimitTest.php
```

## Handoff to Phase 9:
```
- API fully functional
- Postman collection available
- Documentation complete
- All endpoints tested
```

---

# PHASE 9: DEPLOYMENT & PRODUCTION READINESS
**Duration: 1 AI Chat Session**
**Dependencies: Phase 1-8 complete**

## What MUST be built:

### 1. Production Server Setup
```bash
# deploy.sh script
#!/bin/bash
# Server: Ubuntu 22.04 LTS
# Stack: Nginx + PHP 8.2 FPM + MySQL 8.0

# Install packages
sudo apt update
sudo apt install nginx php8.2-fpm php8.2-mysql php8.2-gd php8.2-curl php8.2-mbstring php8.2-xml php8.2-zip

# Clone repository
git clone [repo-url] /var/www/isms-tool

# Install dependencies
cd /var/www/isms-tool
composer install --no-dev --optimize-autoloader
npm ci && npm run build

# Configure environment
cp .env.example .env
php artisan key:generate

# Setup database
mysql -u root -p -e "CREATE DATABASE isms_tool"
php artisan migrate --force
php artisan db:seed --force

# Set permissions
sudo chown -R www-data:www-data /var/www/isms-tool
sudo chmod -R 755 /var/www/isms-tool/storage

# Optimize Laravel
php artisan config:cache
php artisan route:cache
php artisan view:cache

# Setup queue worker
sudo cp deployment/supervisor/queue-worker.conf /etc/supervisor/conf.d/
sudo supervisorctl reread
sudo supervisorctl update
sudo supervisorctl start queue-worker

# Setup scheduler
(crontab -l 2>/dev/null; echo "* * * * * php /var/www/isms-tool/artisan schedule:run >> /dev/null 2>&1") | crontab -
```

### 2. Docker Setup (Alternative)
```dockerfile
# Dockerfile
FROM php:8.2-fpm
# ... configuration

# docker-compose.yml
version: '3.8'
services:
  app:
    build: .
    volumes: [./:/var/www]
  nginx:
    image: nginx:alpine
  mysql:
    image: mysql:8.0
  redis:
    image: redis:alpine
```

### 3. Monitoring Setup
```php
// HealthCheckController
public function check()
{
    return response()->json([
        'status' => 'healthy',
        'database' => $this->checkDatabase(),
        'storage' => $this->checkStorage(),
        'queue' => $this->checkQueue(),
        'cache' => $this->checkCache(),
        'timestamp' => now()
    ]);
}

// Log monitoring
// Error tracking (Sentry - stub)
// Performance monitoring
```

### 4. Backup System
```bash
# backup.sh
#!/bin/bash
# Database backup
mysqldump -u root -p isms_tool > backup_$(date +%Y%m%d_%H%M%S).sql

# File backup
tar -czf storage_backup_$(date +%Y%m%d).tar.gz storage/app

# Upload to S3/Drive (stub)
```

### 5. Performance Optimization
```php
// Laravel Octane (optional)
// Redis caching
// Queue optimization
// Database indexing
// CDN for static assets
// Image optimization
```

### 6. Security Hardening
```php
// Headers middleware
// CSP policy
// HTTPS enforcement
// Rate limiting
// Input sanitization
// File upload restrictions
// SQL injection prevention
// XSS protection
```

### 7. Documentation
```markdown
# README.md
- Project overview
- Setup instructions
- Deployment guide
- API documentation
- Troubleshooting
- Contributing guidelines

# DEPLOYMENT.md
- Server requirements
- Step-by-step deployment
- Rollback procedures
- Backup strategy

# API.md
- Authentication
- Endpoints
- Examples
- Error codes
```

## Deliverables:
- Production-ready deployment
- Docker setup
- Monitoring configured
- Backup system
- Documentation
- Security hardening

## Testing Required:
```php
// tests/Feature/Production/
DeploymentScriptTest.php
HealthCheckTest.php
BackupTest.php
SecurityHeadersTest.php
PerformanceTest.php
```

---

# COORDINATION PROTOCOL FOR MULTIPLE AI CHATS

## Session Handoff Template:
```markdown
## HANDOFF FROM PHASE [X] TO PHASE [X+1]

### Completed Deliverables:
[List all completed files/features]

### Database State:
- Migrations run: [list]
- Seeders executed: [list]
- Sample data: [description]

### Key Files Created:
[List important file paths]

### Known Issues:
[List any issues to be aware of]

### Test Results:
[Summary of passing/failing tests]

### Next Phase Requirements:
[Specific prerequisites for next phase]

### Environment Variables:
[List required .env variables]

### Git Branch:
[Current branch name or commit hash]
```

## Cross-Phase Validation Checklist:
```markdown
Before starting any phase, verify:
□ Previous phase deliverables exist
□ Database schema matches expected
□ Service classes in correct namespaces
□ No business logic in controllers
□ Tests from previous phase pass
□ DTOs properly defined
□ Repository pattern followed
□ Multi-tenancy maintained
□ Security measures in place
```

## Emergency Rollback Plan:
```markdown
If a phase breaks previous functionality:
1. Revert to previous git tag: git checkout phase-[X]-complete
2. Restore database from backup: mysql -u root -p isms_tool < backup_[date].sql
3. Run migrations rollback if needed: php artisan migrate:rollback --step=[n]
4. Report specific breaking changes
5. Re-attempt phase with additional test coverage
```

---

# FINAL DELIVERABLES CHECKLIST

## Complete Application Features:
```
□ Multi-tenant architecture with tenant isolation
□ User authentication with roles (owner, admin, assessor, viewer)
□ Organization/tenant management
□ Complete ISO 27001:2022 control library (93 controls)
□ Assessment creation wizard
□ Control assessment interface with maturity levels
□ Evidence upload and management
□ Risk management module with heatmap
□ Statement of Applicability (SoA) generator
□ Document management with versioning
□ Executive dashboard with analytics
□ 6+ report types with exports
□ Audit trail with complete logging
□ Email + in-app notifications
□ Scheduled tasks
□ REST API with documentation
□ Production deployment scripts
```

## Code Quality:
```
□ Service layer with all business logic
□ Repository pattern implemented
□ DTOs for all data transfer
□ Form requests with validation
□ Policies for authorization
□ Unit tests (minimum 80% coverage for services)
□ Feature tests for critical paths
□ No business logic in controllers
□ PSR-12 compliance
□ Proper error handling
```

## Documentation:
```
□ API documentation (Swagger/Postman)
□ Deployment guide
□ User manual
□ Developer setup guide
□ Database schema documentation
□ Troubleshooting guide
```

## Security:
```
□ Multi-tenancy isolation verified
□ XSS protection
□ CSRF tokens
□ SQL injection prevention
□ File upload validation
□ Password policies
□ Rate limiting
□ Audit logging
□ Data encryption at rest (stub)
□ Secure headers
```

---

# START IMMEDIATELY WITH PHASE 1

Provide the complete code for Phase 1 as specified above. Ensure:
1. All file paths are correct
2. Namespaces match PSR-4
3. No business logic in controllers
4. All service classes are stubbed correctly
5. Migrations are reversible (down method)
6. Seeders create complete ISO 27001:2022 data
7. Multi-tenant scope applied to all tenant-specific models

After Phase 1, provide the handoff data to continue with Phase 2 in a new chat session.
```

---

# 📌 **HOW TO USE THIS PROMPT**

1. **Copy entire prompt** → Paste in new AI chat for Phase 1

2. **After Phase 1 completes** → Save the handoff data

3. **New chat** → Paste:
```
Continue from Phase 2. Here's the handoff from Phase 1:
[paste handoff data]
```

4. **Repeat for each phase**

---

## 🎯 **KEY BENEFITS:**
- ✅ **Modular**: Each phase independent
- ✅ **Testable**: Clear validation criteria
- ✅ **Recoverable**: Easy rollback
- ✅ **Scalable**: Can add features later
- ✅ **Enterprise-ready**: Follows best practices
- ✅ **AI-friendly**: Clear context for each chat

---

**Bhai, ye prompt save kar lo (Notion/Notes me). Har phase ke liye naya chat start karo aur ye prompt paste kar do. Bilkul professional enterprise-level application banega!** 🚀

**Koi phase start karna hai to batao, main help kar dunga!** 💪