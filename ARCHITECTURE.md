# Architecture Documentation

## Overview

This document describes the architectural decisions made for the Mini CRM project.

## Design Patterns

### Repository Pattern

The project uses the Repository pattern to abstract data access logic from the business layer.

**Benefits:**
- Separation of concerns between data access and business logic
- Easy to mock in tests
- Ability to switch data sources without changing business logic

**Implementation:**
```
app/Repositories/
├── Contracts/
│   ├── TicketRepositoryInterface.php
│   └── CustomerRepositoryInterface.php
├── TicketRepository.php
└── CustomerRepository.php
```

Interfaces are bound to implementations in `RepositoryServiceProvider`.

### Service Layer

Business logic is encapsulated in service classes.

**Services:**
- `TicketService` - ticket creation and status management
- `StatisticsService` - statistics calculation
- `FileService` - file attachment handling via Spatie Media Library

### Form Requests

Validation logic is extracted into FormRequest classes:
- `StoreTicketRequest` - validates ticket creation with E.164 phone format
- `UpdateTicketStatusRequest` - validates status updates
- `TicketFilterRequest` - validates admin filter parameters

## Database Design

### Entities

**Users** - Standard Laravel users with Spatie roles
**Customers** - Ticket submitters (name, phone, email)
**Tickets** - Support requests with status workflow

### Ticket Status Flow

```
NEW → IN_PROGRESS → ANSWERED → CLOSED
```

Status is implemented as PHP enum (`App\Enums\TicketStatus`).

### Media Attachments

Using Spatie Media Library for file handling:
- Files stored in `attachments` collection
- Supports images (jpg, png) and documents (pdf, doc, docx)
- Max 5 files per ticket, 10MB each

## API Design

### Widget API

The widget endpoint (`POST /api/tickets`) is stateless and doesn't require CSRF tokens:
- Designed for iframe embedding on external sites
- Rate limited: 10 requests per IP per day
- Business rule: 1 ticket per phone/email per 24 hours

### Statistics API

Protected by Sanctum authentication:
- Provides aggregate data by status and time period
- Supports grouping by day/week/month

## Security Considerations

### Authentication
- Laravel Breeze for web authentication
- Laravel Sanctum for API authentication
- Role-based access control via Spatie Permission

### Rate Limiting
- Global API rate limit: 60 requests/minute per user
- Ticket creation: 10 requests/day per IP
- Business rule: 1 ticket/day per contact

### Input Validation
- Phone numbers validated against E.164 format
- File types restricted to safe formats
- File size limited to 10MB

### CSRF
- Widget API endpoint excluded from CSRF (stateless)
- All admin endpoints protected with CSRF tokens

## Testing Strategy

### Feature Tests
- API endpoint testing
- Admin panel workflow testing
- Authentication testing

### Unit Tests
- Service layer testing
- Business logic validation

### Test Database
- Uses RefreshDatabase trait
- SQLite in-memory for fast tests

## Scalability Notes

### Current Limitations
- File storage is local (consider S3 for production)
- Statistics queries may need optimization for large datasets
- Session storage is database-based

### Future Improvements
- Implement queue for heavy operations
- Add caching for statistics
- Consider read replicas for reporting
