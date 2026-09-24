# school_001_db Schema Diagram

Verified from the local MySQL database `school_001_db`.

## School Bus Tracking ER Diagram

```mermaid
erDiagram
    PARENTS {
        bigint id PK
        varchar full_name
        varchar phone UK
        varchar email
        text address
        varchar emergency_contact_name
        varchar emergency_contact_phone
        tinyint is_active
        bigint created_by_id
        bigint updated_by_id
        timestamp created_at
        timestamp updated_at
    }

    CLASS_SECTIONS {
        bigint id PK
        varchar class_name
        varchar section
        tinyint is_active
        bigint created_by_id
        bigint updated_by_id
        timestamp created_at
        timestamp updated_at
    }

    STUDENTS {
        bigint id PK
        varchar admission_no UK
        varchar full_name
        bigint parent_id FK
        bigint class_section_id FK
        varchar class_name
        varchar section
        text pickup_address
        text drop_address
        tinyint is_active
        bigint created_by_id
        bigint updated_by_id
        timestamp created_at
        timestamp updated_at
    }

    ADMIN_AND_DRIVERS {
        bigint id PK
        varchar full_name
        varchar phone
        varchar email_id UK
        varchar username UK
        varchar password
        varchar user_role
        varchar license_number UK
        date license_expiry_date
        text address
        tinyint is_active
        bigint created_by_id
        bigint updated_by_id
        timestamp created_at
        timestamp updated_at
    }

    VEHICLES {
        bigint id PK
        varchar vehicle_number UK
        varchar registration_number UK
        tinyint is_active
        bigint created_by_id
        bigint updated_by_id
        timestamp created_at
        timestamp updated_at
    }

    VEHICLE_ROUTES {
        bigint id PK
        varchar route_name
        varchar route_code UK
        varchar trip_type
        varchar start_location
        varchar end_location
        tinyint is_active
        bigint created_by_id
        bigint updated_by_id
        timestamp created_at
        timestamp updated_at
    }

    STOPS {
        bigint id PK
        bigint route_id FK
        varchar stop_name
        varchar stop_code UK
        decimal latitude
        decimal longitude
        int stop_order
        tinyint is_active
        bigint created_by_id
        bigint updated_by_id
        timestamp created_at
        timestamp updated_at
    }

    DRIVER_ROUTE_ASSIGNMENTS {
        bigint id PK
        bigint driver_id FK
        bigint vehicle_id FK
        bigint route_id FK
        date assigned_from
        date assigned_to
        tinyint is_active
        bigint created_by_id
        bigint updated_by_id
        timestamp created_at
        timestamp updated_at
    }

    STUDENT_VEHICLE_ASSIGNMENTS {
        bigint id PK
        bigint student_id FK
        bigint vehicle_id FK
        bigint route_id FK
        bigint stop_id FK
        tinyint is_active
        bigint created_by_id
        bigint updated_by_id
        timestamp created_at
        timestamp updated_at
    }

    ACTIVE_TRIPS {
        bigint id PK
        bigint route_id FK
        bigint vehicle_id FK
        bigint driver_id FK
        date trip_date
        varchar trip_status
        timestamp started_at
        timestamp ended_at
        text notes
        bigint created_by_id
        bigint updated_by_id
        timestamp created_at
        timestamp updated_at
    }

    LIVE_VEHICLE_LOCATIONS {
        bigint id PK
        bigint vehicle_id FK,UK
        bigint active_trip_id FK
        decimal latitude
        decimal longitude
        decimal speed
        decimal heading
        tinyint ignition_on
        timestamp recorded_at
        bigint created_by_id
        bigint updated_by_id
        timestamp created_at
        timestamp updated_at
    }

    VEHICLE_LOCATION_HISTORY {
        bigint id PK
        bigint vehicle_id FK
        bigint active_trip_id FK
        decimal latitude
        decimal longitude
        decimal speed
        decimal heading
        tinyint ignition_on
        timestamp recorded_at
        longtext raw_payload
        bigint created_by_id
        bigint updated_by_id
        timestamp created_at
        timestamp updated_at
    }

    NOTIFICATIONS {
        bigint id PK
        varchar title
        text message
        varchar notification_type
        varchar channel
        tinyint is_read
        timestamp read_at
        timestamp sent_at
        longtext payload
        bigint created_by_id
        bigint updated_by_id
        timestamp created_at
        timestamp updated_at
    }

    PARENTS ||--o{ STUDENTS : "parent_id"
    CLASS_SECTIONS ||--o{ STUDENTS : "class_section_id"

    VEHICLE_ROUTES ||--o{ STOPS : "route_id"

    ADMIN_AND_DRIVERS ||--o{ DRIVER_ROUTE_ASSIGNMENTS : "driver_id"
    VEHICLES ||--o{ DRIVER_ROUTE_ASSIGNMENTS : "vehicle_id"
    VEHICLE_ROUTES ||--o{ DRIVER_ROUTE_ASSIGNMENTS : "route_id"

    STUDENTS ||--o{ STUDENT_VEHICLE_ASSIGNMENTS : "student_id"
    VEHICLES ||--o{ STUDENT_VEHICLE_ASSIGNMENTS : "vehicle_id"
    VEHICLE_ROUTES ||--o{ STUDENT_VEHICLE_ASSIGNMENTS : "route_id"
    STOPS ||--o{ STUDENT_VEHICLE_ASSIGNMENTS : "stop_id"

    ADMIN_AND_DRIVERS ||--o{ ACTIVE_TRIPS : "driver_id"
    VEHICLES ||--o{ ACTIVE_TRIPS : "vehicle_id"
    VEHICLE_ROUTES ||--o{ ACTIVE_TRIPS : "route_id"

    VEHICLES ||--|| LIVE_VEHICLE_LOCATIONS : "vehicle_id unique"
    ACTIVE_TRIPS ||--o{ LIVE_VEHICLE_LOCATIONS : "active_trip_id"

    VEHICLES ||--o{ VEHICLE_LOCATION_HISTORY : "vehicle_id"
    ACTIVE_TRIPS ||--o{ VEHICLE_LOCATION_HISTORY : "active_trip_id"
```

## Foreign Key Map

- `students.parent_id -> parents.id`
- `students.class_section_id -> class_sections.id`
- `stops.route_id -> vehicle_routes.id`
- `driver_route_assignments.driver_id -> admin_and_drivers.id`
- `driver_route_assignments.vehicle_id -> vehicles.id`
- `driver_route_assignments.route_id -> vehicle_routes.id`
- `student_vehicle_assignments.student_id -> students.id`
- `student_vehicle_assignments.vehicle_id -> vehicles.id`
- `student_vehicle_assignments.route_id -> vehicle_routes.id`
- `student_vehicle_assignments.stop_id -> stops.id`
- `active_trips.driver_id -> admin_and_drivers.id`
- `active_trips.vehicle_id -> vehicles.id`
- `active_trips.route_id -> vehicle_routes.id`
- `live_vehicle_locations.vehicle_id -> vehicles.id`
- `live_vehicle_locations.active_trip_id -> active_trips.id`
- `vehicle_location_history.vehicle_id -> vehicles.id`
- `vehicle_location_history.active_trip_id -> active_trips.id`

## Unique Indexes

- `admin_and_drivers.email_id`
- `admin_and_drivers.username`
- `admin_and_drivers.license_number`
- `class_sections.class_name + class_sections.section`
- `parents.phone`
- `students.admission_no`
- `vehicles.vehicle_number`
- `vehicles.registration_number`
- `vehicle_routes.route_code`
- `stops.stop_code`
- `live_vehicle_locations.vehicle_id`

## Other Tenant Tables

Laravel infrastructure tables in `school_001_db`: `cache`, `cache_locks`, `failed_jobs`, `jobs`, `job_batches`, `migrations`, `password_reset_tokens`, and `sessions`.
