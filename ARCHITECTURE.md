# 🏛️ Architecture & Design Patterns

The ISMS Compliance Assessment Platform adheres strictly to enterprise software engineering principles to ensure maintainability, scalability, and testability.

---

## 📐 Core Architectural Principles

1. **Zero Business Logic in Controllers**: Controllers act purely as thin HTTP gateways. They validate incoming data via Form Requests, delegate execution to Service classes, and return responses or redirects.
2. **Repository Pattern**: Database queries and Eloquent interactions are abstracted behind dedicated repository interfaces (`app/Repositories/Contracts/`), decoupling data access from business rules.
3. **Immutable DTOs (Data Transfer Objects)**: Inter-layer communication (Controller to Service) utilizes strongly typed PHP 8.2 DTOs to prevent array tampering and ensure data integrity.
4. **Tenant Isolation via Global Scopes**: The `TenantScope` automatically appends `WHERE tenant_id = ?` to all tenant-bound database queries based on the authenticated user's active session.

---

## 📂 Project Directory Structure

```tree
app/
├── Console/Commands/       # Custom Artisan commands & scheduled tasks
├── DTOs/                   # Readonly Data Transfer Objects
├── Exports/                # Maatwebsite Excel & DomPDF export handlers
├── Http/
│   ├── Controllers/        # Thin HTTP controllers
│   ├── Middleware/         # Tenant selection & permission guards
│   └── Requests/           # Form validation request classes
├── Jobs/                   # Background queue jobs (Snapshots, Reminders)
├── Models/                 # Eloquent models with global scopes
├── Policies/               # Granular authorization policies (AssetPolicy, RiskPolicy)
├── Repositories/           # Data access repository implementations & contracts
├── Reports/                # Strategy Pattern report classes & registry
└── Services/               # Core business logic (Compliance, Risk, SoA, Reporting)