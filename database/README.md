# Courier Management System - MySQL Database

This directory contains the complete MySQL database schema and sample data for the Courier Management System.

## Database Structure

### Core Tables

1. **users** - System users (admin, agents, customers)
2. **agents** - Agent-specific information and settings
3. **customers** - Customer profiles and preferences
4. **couriers** - Main shipment/package table
5. **cities** - Supported cities for pickup/delivery
6. **courier_assignments** - Agent assignments to couriers
7. **courier_tracking_history** - Detailed tracking information
8. **notifications** - System notifications
9. **settings** - System configuration
10. **reports** - Generated reports
11. **user_preferences** - User-specific preferences

### Files Description

- **schema.sql** - Complete database schema with tables, relationships, and triggers
- **sample_data.sql** - Sample data for testing and development
- **views.sql** - Useful database views for reporting and queries
- **stored_procedures.sql** - Stored procedures for common operations
- **functions.sql** - Custom MySQL functions
- **indexes.sql** - Additional indexes for performance optimization

## Setup Instructions

1. **Create Database:**
   ```sql
   mysql -u root -p < schema.sql
   ```

2. **Insert Sample Data:**
   ```sql
   mysql -u root -p < sample_data.sql
   ```

3. **Create Views:**
   ```sql
   mysql -u root -p < views.sql
   ```

4. **Add Stored Procedures:**
   ```sql
   mysql -u root -p < stored_procedures.sql
   ```

5. **Add Functions:**
   ```sql
   mysql -u root -p < functions.sql
   ```

6. **Optimize with Indexes:**
   ```sql
   mysql -u root -p < indexes.sql
   ```

## Default Login Credentials

### Admin Users
- **Email:** admin@courierpro.com
- **Password:** password

### Agent Users
- **Email:** agent@courierpro.com
- **Password:** password

### Regular Users
- **Email:** user@courierpro.com
- **Password:** password

## Key Features

### Authentication & Authorization
- Role-based access control (admin, agent, user)
- Secure password hashing
- User preferences and settings

### Courier Management
- Complete shipment lifecycle tracking
- Multiple courier types (standard, express, overnight, same-day)
- Automatic tracking number generation
- Status updates and history

### Agent Management
- Agent assignments and workload management
- Performance tracking and reporting
- Availability and auto-assignment features

### Customer Management
- Customer profiles and order history
- Spending tracking and tier classification
- Delivery preferences

### Reporting & Analytics
- Daily, weekly, monthly statistics
- Agent performance reports
- Route analysis
- Revenue tracking

### Notifications
- Real-time status updates
- Email and SMS notification preferences
- System alerts and announcements

## Database Views

- **courier_details** - Complete courier information with relationships
- **agent_performance** - Agent statistics and performance metrics
- **customer_summary** - Customer profiles with order statistics
- **daily_stats** - Daily operational statistics
- **route_analysis** - Popular routes and performance
- **recent_activity** - Recent system activity
- **overdue_shipments** - Overdue deliveries
- **monthly_revenue** - Monthly financial reports

## Stored Procedures

- **CreateCourier** - Create new shipment with tracking
- **UpdateCourierStatus** - Update shipment status and tracking
- **AssignCourierToAgent** - Assign courier to available agent
- **GetAgentPerformanceReport** - Generate agent performance report
- **GetDailyStatistics** - Get daily operational statistics
- **SearchCouriers** - Advanced courier search
- **AutoAssignCouriers** - Automatically assign unassigned couriers

## Custom Functions

- **CalculateDeliveryFee** - Calculate shipping costs
- **GetEstimatedDeliveryDate** - Estimate delivery dates
- **IsAgentAvailable** - Check agent availability
- **GetCustomerTier** - Determine customer tier
- **CalculateDistance** - Calculate distance between cities

## Performance Optimization

- Comprehensive indexing strategy
- Composite indexes for common queries
- Full-text search capabilities
- Optimized views for reporting

## Data Integrity

- Foreign key constraints
- Triggers for automatic updates
- Data validation at database level
- Audit trails for important operations

## Sample Data Included

- 20+ users across all roles
- 6 active agents in different cities
- 10+ customers with order history
- 20+ sample shipments with tracking
- 50+ cities for pickup/delivery
- Complete tracking history
- System notifications
- Performance data

This database schema provides a solid foundation for a production-ready courier management system with all the features demonstrated in the React application.