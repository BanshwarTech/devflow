# Changelog

All notable changes to DevFlow will be documented in this file.

The format is based on [Keep a Changelog](https://keepachangelog.com/en/1.0.0/),
and this project adheres to [Semantic Versioning](https://semver.org/spec/v2.0.0.html).

## [Unreleased]

### Added
- Complete Laravel 13 project setup with Breeze Livewire/Volt
- Spatie Permission integration with Admin, Manager, Employee roles
- Laravel Pulse, Pennant, Sanctum, Reverb integration
- Core migrations for projects, tasks, teams, comments, attachments, activities
- Models with relationships and factories
- Livewire Volt components for Dashboard, Projects, Tasks, Teams, Comments
- Event-driven architecture with 4 domain events
- Policy-based authorization for Projects, Tasks, Teams
- Database seeders with demo data
- Responsive navigation with permission-based links

### Fixed
- Migration duplicate index issues on morphs columns
- Seeder Faker compatibility issues
- Duplicate user creation errors on re-run

## [1.0.0] - 2026-08-03

### Added
- Initial project structure
- Laravel 13 + Breeze Livewire functional stack
- Authentication scaffolding
- Basic welcome page
