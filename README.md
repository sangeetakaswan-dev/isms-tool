
```
isms-tool
├─ .editorconfig
├─ app
│  ├─ Console
│  │  └─ Commands
│  │     └─ SnapshotComplianceScore.php
│  ├─ DTOs
│  │  ├─ AssessmentData.php
│  │  ├─ AssessmentResponseData.php
│  │  ├─ DashboardMetricsData.php
│  │  ├─ DocumentData.php
│  │  ├─ ReportData.php
│  │  ├─ RiskData.php
│  │  ├─ RiskTreatmentData.php
│  │  ├─ SoAEntryData.php
│  │  ├─ TenantData.php
│  │  └─ UserData.php
│  ├─ Exports
│  │  ├─ ReportExport.php
│  │  ├─ RiskRegisterExport.php
│  │  ├─ SoAExcelExport.php
│  │  └─ SoAPdfExport.php
│  ├─ Http
│  │  ├─ Controllers
│  │  │  ├─ AssessmentController.php
│  │  │  ├─ AssessmentResponseController.php
│  │  │  ├─ AssessmentTeamController.php
│  │  │  ├─ AssetController.php
│  │  │  ├─ Auth
│  │  │  │  ├─ AuthenticatedSessionController.php
│  │  │  │  ├─ ConfirmablePasswordController.php
│  │  │  │  ├─ EmailVerificationNotificationController.php
│  │  │  │  ├─ EmailVerificationPromptController.php
│  │  │  │  ├─ NewPasswordController.php
│  │  │  │  ├─ PasswordController.php
│  │  │  │  ├─ PasswordResetLinkController.php
│  │  │  │  ├─ RegisteredUserController.php
│  │  │  │  └─ VerifyEmailController.php
│  │  │  ├─ Controller.php
│  │  │  ├─ DashboardController.php
│  │  │  ├─ DocumentController.php
│  │  │  ├─ DocumentReviewController.php
│  │  │  ├─ DocumentVersionController.php
│  │  │  ├─ InvitationController.php
│  │  │  ├─ NotificationController.php
│  │  │  ├─ ProfileController.php
│  │  │  ├─ ReportController.php
│  │  │  ├─ RiskController.php
│  │  │  ├─ RiskRegisterController.php
│  │  │  ├─ RiskTreatmentController.php
│  │  │  ├─ SoAController.php
│  │  │  ├─ TenantController.php
│  │  │  └─ TreatmentPlanController.php
│  │  ├─ Kernel.php
│  │  ├─ Middleware
│  │  │  ├─ CheckTenantPermission.php
│  │  │  ├─ EnsureTenantSelected.php
│  │  │  └─ ValidateTenantAccess.php
│  │  └─ Requests
│  │     ├─ Assessment
│  │     │  ├─ BulkUpdateRequest.php
│  │     │  ├─ CreateAssessmentRequest.php
│  │     │  └─ UpdateResponseRequest.php
│  │     ├─ Auth
│  │     │  └─ LoginRequest.php
│  │     ├─ Document
│  │     │  └─ StoreDocumentRequest.php
│  │     ├─ Invitation
│  │     │  └─ CreateInvitationRequest.php
│  │     ├─ ProfileUpdateRequest.php
│  │     ├─ Risk
│  │     │  ├─ StoreRiskRequest.php
│  │     │  ├─ StoreTreatmentRequest.php
│  │     │  └─ UpdateRiskRequest.php
│  │     └─ Tenant
│  │        └─ CreateTenantRequest.php
│  ├─ Jobs
│  │  ├─ PruneReportSnapshotsJob.php
│  │  ├─ SendTreatmentDueRemindersJob.php
│  │  └─ SnapshotComplianceScoreJob.php
│  ├─ Livewire
│  │  ├─ RiskDashboard.php
│  │  └─ RiskHeatmap.php
│  ├─ Models
│  │  ├─ Assessment.php
│  │  ├─ AssessmentResponse.php
│  │  ├─ AssessmentTeam.php
│  │  ├─ Asset.php
│  │  ├─ Control.php
│  │  ├─ Document.php
│  │  ├─ DocumentReview.php
│  │  ├─ DocumentVersion.php
│  │  ├─ Domain.php
│  │  ├─ GeneratedReport.php
│  │  ├─ Invitation.php
│  │  ├─ ReportSnapshot.php
│  │  ├─ Risk.php
│  │  ├─ RiskAssessment.php
│  │  ├─ RiskTreatmentPlan.php
│  │  ├─ SoAEntry.php
│  │  ├─ SoAVersion.php
│  │  ├─ Tenant.php
│  │  └─ User.php
│  ├─ Notifications
│  │  ├─ RiskAssignedNotification.php
│  │  └─ TreatmentDueNotification.php
│  ├─ Policies
│  │  ├─ AssessmentTeamPolicy.php
│  │  └─ RiskPolicy.php
│  ├─ Providers
│  │  ├─ AppServiceProvider.php
│  │  └─ RepositoryServiceProvider.php
│  ├─ Reports
│  │  ├─ AbstractReport.php
│  │  ├─ AssetInventoryReport.php
│  │  ├─ Contracts
│  │  │  └─ ReportInterface.php
│  │  ├─ DocumentComplianceReport.php
│  │  ├─ ExecutiveSummaryReport.php
│  │  ├─ GapAnalysisReport.php
│  │  ├─ ReportRegistry.php
│  │  ├─ RiskRegisterReport.php
│  │  └─ SoaStatusReport.php
│  ├─ Repositories
│  │  ├─ AssessmentRepository.php
│  │  ├─ AssessmentResponseRepository.php
│  │  ├─ BaseRepository.php
│  │  ├─ Contracts
│  │  │  ├─ AssessmentRepositoryInterface.php
│  │  │  ├─ AssessmentResponseRepositoryInterface.php
│  │  │  ├─ ControlRepositoryInterface.php
│  │  │  ├─ DocumentRepositoryInterface.php
│  │  │  ├─ RiskRepositoryInterface.php
│  │  │  ├─ SoAEntryRepositoryInterface.php
│  │  │  ├─ SoAVersionRepositoryInterface.php
│  │  │  ├─ TenantRepositoryInterface.php
│  │  │  └─ UserRepositoryInterface.php
│  │  ├─ ControlRepository.php
│  │  ├─ DocumentRepository.php
│  │  ├─ Reporting
│  │  │  ├─ Contracts
│  │  │  │  └─ ReportRepositoryInterface.php
│  │  │  └─ ReportRepository.php
│  │  ├─ RiskRepository.php
│  │  ├─ SoAEntryRepository.php
│  │  ├─ SoAVersionRepository.php
│  │  ├─ TenantRepository.php
│  │  └─ UserRepository.php
│  ├─ Scopes
│  │  └─ TenantScope.php
│  ├─ Services
│  │  ├─ Assessment
│  │  │  ├─ AssessmentService.php
│  │  │  └─ ResponseService.php
│  │  ├─ AssessmentTeamService.php
│  │  ├─ Compliance
│  │  │  ├─ ComplianceCalculator.php
│  │  │  └─ StatementOfApplicabilityService.php
│  │  ├─ Document
│  │  │  └─ DocumentService.php
│  │  ├─ Notification
│  │  │  └─ NotificationService.php
│  │  ├─ Reporting
│  │  │  ├─ DashboardService.php
│  │  │  ├─ ExportService.php
│  │  │  └─ ReportService.php
│  │  ├─ Risk
│  │  │  ├─ AssetService.php
│  │  │  ├─ RiskCalculationService.php
│  │  │  ├─ RiskService.php
│  │  │  └─ RiskTreatmentService.php
│  │  ├─ Tenant
│  │  │  ├─ InvitationService.php
│  │  │  └─ TenantService.php
│  │  └─ User
│  │     └─ UserService.php
│  └─ View
│     └─ Components
│        ├─ AppLayout.php
│        └─ GuestLayout.php
├─ artisan
├─ bootstrap
│  ├─ app.php
│  ├─ cache
│  │  ├─ packages.php
│  │  └─ services.php
│  └─ providers.php
├─ Bugs.txt
├─ composer.json
├─ composer.lock
├─ config
│  ├─ app.php
│  ├─ audit.php
│  ├─ auth.php
│  ├─ cache.php
│  ├─ database.php
│  ├─ dompdf.php
│  ├─ filesystems.php
│  ├─ livewire.php
│  ├─ logging.php
│  ├─ mail.php
│  ├─ permission.php
│  ├─ queue.php
│  ├─ sanctum.php
│  ├─ services.php
│  └─ session.php
├─ database
│  ├─ database.sqlite
│  ├─ factories
│  │  ├─ AssessmentFactory.php
│  │  ├─ AssessmentResponseFactory.php
│  │  ├─ AssetFactory.php
│  │  ├─ ControlFactory.php
│  │  ├─ DomainFactory.php
│  │  ├─ RiskAssessmentFactory.php
│  │  ├─ SoAEntryFactory.php
│  │  ├─ TenantFactory.php
│  │  └─ UserFactory.php
│  ├─ migrations
│  │  ├─ 0001_01_01_000000_create_users_table.php
│  │  ├─ 0001_01_01_000001_create_cache_table.php
│  │  ├─ 0001_01_01_000002_create_jobs_table.php
│  │  ├─ 2026_08_16_163133_create_permission_tables.php
│  │  ├─ 2026_08_16_163141_create_audits_table.php
│  │  ├─ 2026_08_16_163357_create_tenants_table.php
│  │  ├─ 2026_08_16_163438_modify_users_table.php
│  │  ├─ 2026_08_16_163503_create_tenant_user_table.php
│  │  ├─ 2026_08_16_164129_create_invitations_table.php
│  │  ├─ 2026_08_16_164157_create_domains_table.php
│  │  ├─ 2026_08_16_164209_create_controls_table.php
│  │  ├─ 2026_08_16_164259_create_assessments_table.php
│  │  ├─ 2026_08_16_164315_create_assessment_responses_table.php
│  │  ├─ 2026_08_16_175913_create_personal_access_tokens_table.php
│  │  ├─ 2026_08_18_152957_create_assessment_team_table.php
│  │  ├─ 2026_08_18_154029_add_evidence_files_to_assessment_responses.php
│  │  ├─ 2026_08_30_180248_create_assets_table.php
│  │  ├─ 2026_08_30_180248_create_risk_assessments_table.php
│  │  ├─ 2026_08_30_180249_create_risk_treatment_plans_table.php
│  │  ├─ 2026_09_02_163926_create_soa_entries_table.php
│  │  ├─ 2026_09_02_164837_create_soa_versions_table.php
│  │  ├─ 2026_09_26_100147_create_documents_table.php
│  │  ├─ 2026_09_26_100324_create_document_versions_table.php
│  │  ├─ 2026_09_26_100414_create_document_reviews_table.php
│  │  ├─ 2026_09_26_100503_create_document_control_map_table.php
│  │  ├─ 2026_09_26_105549_create_report_snapshots_table.php
│  │  ├─ 2026_09_26_110140_create_generated_reports_table.php
│  │  ├─ 2026_10_11_163504_create_risks_table.php
│  │  ├─ 2026_10_11_164400_extend_risk_treatment_plans_for_phase7.php
│  │  ├─ 2026_10_11_174028_create_notifications_table.php
│  │  └─ 2026_10_11_185711_make_due_date_nullable_on_treatment_plans.php
│  └─ seeders
│     ├─ AssessmentTeamSeeder.php
│     ├─ AssetSeeder.php
│     ├─ ControlSeeder.php
│     ├─ DatabaseSeeder.php
│     ├─ DemoDataSeeder.php
│     ├─ DomainSeeder.php
│     ├─ PolicyTemplateSeeder.php
│     ├─ RiskManagementSeeder.php
│     ├─ RiskSeeder.php
│     ├─ TestUserSeeder.php
│     ├─ TreatmentPlanSeeder.php
│     └─ UserSeeder.php
├─ end goal.txt
├─ package-lock.json
├─ package.json
├─ phase 1 report .md
├─ phase 2 report.md
├─ phase 3 report.md
├─ phase 4 report.md
├─ phase 5 report.md
├─ phase 6 report.md
├─ phase 7 report.md
├─ phpunit.xml
├─ postcss.config.js
├─ public
│  ├─ .htaccess
│  ├─ favicon.ico
│  ├─ index.php
│  └─ robots.txt
├─ README.md
├─ resources
│  ├─ css
│  │  └─ app.css
│  ├─ js
│  │  ├─ app.js
│  │  └─ bootstrap.js
│  └─ views
│     ├─ assessments
│     │  ├─ conduct
│     │  │  └─ index.blade.php
│     │  ├─ create.blade.php
│     │  ├─ index.blade.php
│     │  └─ show.blade.php
│     ├─ assets
│     │  ├─ create.blade.php
│     │  ├─ edit.blade.php
│     │  ├─ index.blade.php
│     │  └─ show.blade.php
│     ├─ auth
│     │  ├─ confirm-password.blade.php
│     │  ├─ forgot-password.blade.php
│     │  ├─ login.blade.php
│     │  ├─ register.blade.php
│     │  ├─ reset-password.blade.php
│     │  └─ verify-email.blade.php
│     ├─ components
│     │  ├─ application-logo.blade.php
│     │  ├─ auth-session-status.blade.php
│     │  ├─ danger-button.blade.php
│     │  ├─ dropdown-link.blade.php
│     │  ├─ dropdown.blade.php
│     │  ├─ input-error.blade.php
│     │  ├─ input-label.blade.php
│     │  ├─ modal.blade.php
│     │  ├─ nav-link.blade.php
│     │  ├─ primary-button.blade.php
│     │  ├─ responsive-nav-link.blade.php
│     │  ├─ secondary-button.blade.php
│     │  └─ text-input.blade.php
│     ├─ dashboard
│     │  ├─ history.blade.php
│     │  ├─ index.blade.php
│     │  ├─ partials
│     │  │  └─ risk-heatmap.blade.php
│     │  └─ risk.blade.php
│     ├─ dashboard.blade.php
│     ├─ documents
│     │  ├─ create.blade.php
│     │  ├─ index.blade.php
│     │  └─ show.blade.php
│     ├─ layouts
│     │  ├─ app.blade.php
│     │  ├─ guest.blade.php
│     │  └─ navigation.blade.php
│     ├─ livewire
│     │  ├─ risk-dashboard.blade.php
│     │  └─ risk-heatmap.blade.php
│     ├─ notifications
│     │  └─ index.blade.php
│     ├─ profile
│     │  ├─ edit.blade.php
│     │  └─ partials
│     │     ├─ delete-user-form.blade.php
│     │     ├─ update-password-form.blade.php
│     │     └─ update-profile-information-form.blade.php
│     ├─ reports
│     │  ├─ history.blade.php
│     │  ├─ index.blade.php
│     │  ├─ pdf.blade.php
│     │  └─ show.blade.php
│     ├─ risks
│     │  ├─ create.blade.php
│     │  ├─ edit.blade.php
│     │  ├─ index.blade.php
│     │  └─ show.blade.php
│     ├─ soa
│     │  ├─ compare.blade.php
│     │  ├─ index.blade.php
│     │  └─ show.blade.php
│     ├─ tenants
│     │  ├─ create.blade.php
│     │  ├─ index.blade.php
│     │  └─ show.blade.php
│     ├─ treatments
│     │  ├─ form.blade.php
│     │  └─ index.blade.php
│     └─ welcome.blade.php
├─ routes
│  ├─ api.php
│  ├─ auth.php
│  ├─ console.php
│  └─ web.php
├─ storage
│  ├─ app
│  │  ├─ private
│  │  │  └─ templates
│  │  │     ├─ Acceptable_Use_Policy.txt
│  │  │     ├─ Access_Control_Policy.txt
│  │  │     ├─ Backup_&_Recovery_Policy.txt
│  │  │     ├─ Business_Continuity_Plan.txt
│  │  │     ├─ Change_Management_Procedure.txt
│  │  │     ├─ Data_Classification_Policy.txt
│  │  │     ├─ Incident_Response_Procedure.txt
│  │  │     ├─ Information_Security_Policy.txt
│  │  │     ├─ Risk_Assessment_Methodology.txt
│  │  │     └─ Supplier_Security_Policy.txt
│  │  └─ public
│  ├─ framework
│  │  ├─ cache
│  │  │  ├─ data
│  │  │  └─ laravel-excel
│  │  ├─ sessions
│  │  │  ├─ aNefmCWWnI4rvrZB2NYHMavKcNuuC5JMGn42ev7H
│  │  │  ├─ lzAMUMKhB6mhvAY1QGub6xslqDYRx02xYOktEdk5
│  │  │  ├─ rCDVoka8VFWioTbGWMJciYBCOiGKj8q8QKapp8Yq
│  │  │  ├─ yeU04bGx91lIjQMYlGtCwaPfWPDtJ6PP2fKjpYL8
│  │  │  └─ yxv0AhX8tId81hL5Aqt4kMUc8Zi0rxmYEOy78exV
│  │  ├─ testing
│  │  │  └─ disks
│  │  │     ├─ local
│  │  │     │  └─ documents
│  │  │     │     └─ 1
│  │  │     │        └─ policys
│  │  │     │           └─ R5SD9wMvNd8Sf3Wws94ngYUcbBObH7P0QiXHUXFs.pdf
│  │  │     └─ public
│  │  └─ views
│  └─ logs
├─ tailwind.config.js
├─ tests
│  ├─ Feature
│  │  ├─ Assessment
│  │  │  ├─ AssessmentCreationTest.php
│  │  │  ├─ ProgressCalculationTest.php
│  │  │  └─ ResponseUpdateTest.php
│  │  ├─ AssessmentTeamTest.php
│  │  ├─ AssetTest.php
│  │  ├─ Auth
│  │  │  ├─ AuthenticationTest.php
│  │  │  ├─ EmailVerificationTest.php
│  │  │  ├─ PasswordConfirmationTest.php
│  │  │  ├─ PasswordResetTest.php
│  │  │  ├─ PasswordUpdateTest.php
│  │  │  └─ RegistrationTest.php
│  │  ├─ Documents
│  │  │  ├─ DocumentUploadTest.php
│  │  │  ├─ ReviewWorkflowTest.php
│  │  │  └─ VersionControlTest.php
│  │  ├─ ExampleTest.php
│  │  ├─ ProfileTest.php
│  │  ├─ Reporting
│  │  │  ├─ DashboardTest.php
│  │  │  └─ ReportGenerationTest.php
│  │  ├─ Risk
│  │  │  ├─ ResidualRiskCalculationTest.php
│  │  │  ├─ RiskDashboardAnalyticsTest.php
│  │  │  ├─ RiskHeatmapTest.php
│  │  │  ├─ RiskNotificationTest.php
│  │  │  ├─ RiskRegisterTest.php
│  │  │  ├─ RiskTreatmentWorkflowTest.php
│  │  │  └─ ScheduledSnapshotTest.php
│  │  ├─ RiskTest.php
│  │  ├─ SoA
│  │  │  ├─ ApprovalWorkflowTest.php
│  │  │  ├─ SoAExportTest.php
│  │  │  ├─ SoAGenerationTest.php
│  │  │  └─ VersionComparisonTest.php
│  │  ├─ TenantCreationTest.php
│  │  └─ UserInvitationTest.php
│  ├─ TestCase.php
│  └─ Unit
│     └─ ExampleTest.php
└─ vite.config.js

```