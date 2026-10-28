# 🔐 ISO/IEC 27001:2022 ISMS Compliance Assessment Platform

An enterprise-grade, multi-tenant GRC (Governance, Risk, and Compliance) SaaS platform built to automate ISO/IEC 27001:2022 information security management system (ISMS) assessments, risk management, Statement of Applicability (SoA) generation, and document control.

[![Live Demo](https://img.shields.io/badge/Live-Demo-success?style=for-the-badge)](https://ismsaudit.infinityfree.io)
[![PHP Version](https://img.shields.io/badge/PHP-8.2%2B-blue?style=for-the-badge&logo=php)](https://php.net)
[![Laravel Framework](https://img.shields.io/badge/Laravel-11.x-red?style=for-the-badge&logo=laravel)](https://laravel.com)
[![Tests](https://img.shields.io/badge/Tests-71%2F71%20Passing-green?style=for-the-badge&logo=phpunit)](https://phpunit.de)

---

## 🚀 Core Features

- **Multi-Tenant Architecture**: Shared-database with automated global scopes (`TenantScope`) ensuring strict data isolation per organization.
- **Complete ISO 27001:2022 Control Library**: Pre-seeded with 11 domains (Main Clauses 4–10 & Annex A themes A.5 to A.8) comprising 115 controls.
- **Assessment Engine**: Multi-step wizard, domain-by-domain evaluation, 5 compliance states, and CMMI-aligned maturity scoring (Levels 0–5).
- **Information Asset Register & 5x5 Risk Matrix**: Asset inventory with CIA (Confidentiality, Integrity, Availability) ratings and automated Likelihood × Impact (1–25) risk scoring.
- **Risk Treatment Plans**: 4 ISO treatment strategies (Mitigate, Accept, Transfer, Avoid) with ownership tracking and automated status transitions.
- **Statement of Applicability (SoA) Engine**: One-click SoA generation, regulatory justifications, multi-tier approvals, and immutable JSON version snapshots (`soa_versions`).
- **Document Management System (DMS)**: Policy lifecycle management, semantic version control (v1.0 ➔ v1.1), review workflows, and control mapping (`document_control_map`).
- **Executive Analytics & Reporting**: Real-time Chart.js telemetry and 6 Strategy Pattern Report types with multi-format exports (CSV, DomPDF, Maatwebsite Excel).
- **Enterprise Security & RBAC**: Granular role permissions via Spatie, policy-based action guards (`AssetPolicy`, `RiskPolicy`), and custom error boundaries.

---

## 🛠️ Tech Stack

- **Backend**: PHP 8.2+, Laravel 11.x, MySQL 8.0+
- **Frontend**: Blade Templates, Livewire 3, Alpine.js, Tailwind CSS 4
- **Visualizations**: Chart.js 4
- **Testing**: PHPUnit 10.5 (71 Feature & Unit Tests, 169 Assertions)

---

## 👥 Demo Credentials

| Role | Email | Password | Access Level |
|------|-------|----------|--------------|
| **Owner** | `owner@demo.com` | `password` | Full organization & settings access |
| **Assessor** | `assessor@demo.com` | `password` | Assessment & risk evaluation access |
| **Viewer** | `viewer@demo.com` | `password` | Read-only compliance posture view |

---

## ⚙️ Local Installation & Setup

1. **Clone the repository:**
   ```bash
   git clone [https://github.com/sangeetakaswan-dev/isms-tool.git](https://github.com/sangeetakaswan-dev/isms-tool.git)
   cd isms-tool