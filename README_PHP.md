# CourierPro - PHP & MySQL Version

A complete **Courier Management System** built with pure **PHP** and **MySQL**, converted from the original React/TypeScript application while maintaining all functionality and features.

## 🚀 Features

### **Multi-Role System**
- **Admin**: Complete system management, analytics, user management
- **Agent**: Courier creation, tracking updates, reports
- **User**: Package tracking, shipment history

### **Core Functionality**
- ✅ **Courier Management**: Create, track, update, and manage courier shipments
- ✅ **Real-time Tracking**: Detailed tracking history with status updates
- ✅ **User Management**: Role-based access control and user administration
- ✅ **Analytics Dashboard**: Charts and statistics for business insights
- ✅ **Reports**: Comprehensive reporting system
- ✅ **Support System**: Integrated help desk and support tickets
- ✅ **Responsive Design**: Mobile-friendly interface using Bootstrap 5

### **Technical Features**
- ✅ **Secure Authentication**: Session-based authentication with password hashing
- ✅ **Database Security**: PDO prepared statements, SQL injection protection
- ✅ **Modern UI**: Bootstrap 5 with custom CSS styling
- ✅ **AJAX Integration**: Asynchronous operations for better UX
- ✅ **RESTful API**: JSON API endpoints for frontend communication
- ✅ **Data Validation**: Server-side and client-side validation
- ✅ **Error Handling**: Comprehensive error handling and logging

## 🛠️ Installation & Setup

### **Prerequisites**
- PHP 7.4 or higher
- MySQL 5.7 or higher
- Apache/Nginx web server
- Web browser with JavaScript enabled

### **Step 1: Download & Extract**
```bash
# Download the files to your web server directory
# For XAMPP: C:\xampp\htdocs\courier-management\
# For WAMP: C:\wamp64\www\courier-management\
# For LAMP: /var/www/html/courier-management/
```

### **Step 2: Database Setup**
1. **Create Database:**
   ```sql
   CREATE DATABASE courier_management;
   ```

2. **Import Schema:**
   - Open `database/schema.sql` in phpMyAdmin or MySQL client
   - Execute the SQL script to create tables and insert sample data

3. **Configure Database Connection:**
   - Edit `config/database.php`
   - Update database credentials:
   ```php
   define('DB_HOST', 'localhost');
   define('DB_NAME', 'courier_management');
   define('DB_USER', 'your_username');
   define('DB_PASS', 'your_password');
   ```

### **Step 3: File Permissions**
```bash
# Set proper permissions (Linux/Mac)
chmod 755 -R courier-management/
chmod 644 -R courier-management/*.php
chmod 755 courier-management/uploads/
```

### **Step 4: Web Server Configuration**
- Ensure `mod_rewrite` is enabled (Apache)
- Set document root to the project folder
- Start your web server

### **Step 5: Access the Application**
- Open browser and navigate to: `http://localhost/courier-management/`
- Use demo credentials to login

## 🔑 Demo Accounts

### **Admin Account**
- **Email**: `admin@courierpro.com`
- **Password**: `password`
- **Access**: Full system administration

### **Agent Account**  
- **Email**: `agent@courierpro.com`
- **Password**: `password`
- **Access**: Courier management, reports

### **User Account**
- **Email**: `user@courierpro.com`
- **Password**: `password`
- **Access**: Package tracking, history

## 📁 Project Structure

```
courier-management/
├── index.php                 # Main entry point & routing
├── config/
│   ├── database.php          # Database configuration
│   └── app.php              # Application settings
├── includes/
│   ├── auth.php             # Authentication functions
│   ├── functions.php        # Utility functions
│   ├── header.php           # HTML header & navigation
│   └── footer.php           # HTML footer & scripts
├── pages/
│   ├── login.php            # Login page
│   ├── admin/               # Admin pages
│   │   ├── dashboard.php    # Admin dashboard
│   │   ├── add-courier.php  # Create courier form
│   │   └── ...
│   ├── agent/               # Agent pages
│   │   └── ...
│   └── user/                # User pages
│       └── ...
├── api/
│   └── handler.php          # API endpoints
├── assets/
│   ├── css/
│   │   └── style.css        # Custom styles
│   └── js/
│       └── app.js           # JavaScript utilities
├── database/
│   └── schema.sql           # Database schema & sample data
└── uploads/                 # File upload directory
```

## 🔧 Configuration

### **Application Settings** (`config/app.php`)
```php
define('APP_NAME', 'CourierPro');
define('APP_URL', 'http://localhost/courier-management');
define('MAX_FILE_SIZE', 5 * 1024 * 1024); // 5MB
```

### **Database Settings** (`config/database.php`)
```php
define('DB_HOST', 'localhost');
define('DB_NAME', 'courier_management');
define('DB_USER', 'root');
define('DB_PASS', '');
```

## 🎯 Key Features Explained

### **Routing System**
- Clean URL routing through `index.php`
- Page-based routing: `index.php?page=admin-dashboard`
- Role-based access control

### **Authentication**
- Secure session management
- Password hashing with PHP's `password_hash()`
- Role-based permissions

### **Database Layer**
- PDO with prepared statements
- Singleton database connection
- Transaction support

### **API Endpoints**
- RESTful JSON API
- AJAX integration
- Error handling

## 🚀 Usage Guide

### **For Administrators:**
1. **Dashboard**: View system overview and statistics
2. **User Management**: Add/edit/delete users and agents
3. **Courier Oversight**: Monitor all courier activities
4. **Analytics**: View detailed reports and charts
5. **System Settings**: Configure application settings

### **For Agents:**
1. **Create Couriers**: Add new shipments
2. **Track Updates**: Update courier status and location
3. **Manage Assignments**: Handle assigned couriers
4. **Generate Reports**: Create performance reports

### **For Users:**
1. **Track Packages**: Search by tracking number
2. **View History**: See all past shipments
3. **Contact Support**: Submit support requests
4. **Account Settings**: Manage profile information

## 🔒 Security Features

- **SQL Injection Protection**: PDO prepared statements
- **XSS Prevention**: Input sanitization and output escaping
- **CSRF Protection**: Session-based security
- **Password Security**: Bcrypt hashing
- **Access Control**: Role-based permissions
- **Session Management**: Secure session handling

## 🎨 Customization

### **Styling**
- Edit `assets/css/style.css` for custom styles
- Bootstrap 5 classes available throughout
- CSS variables for theme colors

### **Functionality**
- Add new pages in respective role directories
- Update routing in `index.php`
- Extend API in `api/handler.php`

### **Database**
- Modify `database/schema.sql` for new tables
- Update functions in `includes/functions.php`

## 🐛 Troubleshooting

### **Common Issues:**

1. **Database Connection Error**
   - Check database credentials in `config/database.php`
   - Ensure MySQL service is running
   - Verify database exists

2. **Permission Denied**
   - Check file permissions (755 for directories, 644 for files)
   - Ensure web server has read access

3. **Page Not Loading**
   - Check web server configuration
   - Verify .htaccess rules (if using Apache)
   - Enable error reporting for debugging

4. **Session Issues**
   - Check PHP session configuration
   - Ensure session directory is writable
   - Clear browser cookies

### **Enable Debug Mode:**
```php
// In config/app.php
error_reporting(E_ALL);
ini_set('display_errors', 1);
```

## 📊 Database Schema

### **Main Tables:**
- `users` - User accounts and roles
- `couriers` - Courier shipment data
- `courier_tracking` - Tracking history
- `support_tickets` - Help desk tickets
- `settings` - System configuration

### **Sample Data:**
- 3 demo users (admin, agent, user)
- 4 sample couriers with tracking history
- Default system settings

## 🔄 Migration from React Version

This PHP version maintains **100% feature parity** with the original React application:

### **Preserved Features:**
- ✅ All user roles and permissions
- ✅ Complete dashboard functionality
- ✅ Courier management system
- ✅ Real-time tracking
- ✅ Analytics and reporting
- ✅ User interface design
- ✅ Responsive layout

### **Technology Stack Changes:**
- **Frontend**: React → PHP + Bootstrap 5
- **State Management**: Zustand → PHP Sessions + MySQL
- **Routing**: React Router → PHP Routing
- **API**: React Hooks → AJAX + PHP API
- **Styling**: Tailwind + ShadCN → Bootstrap 5 + Custom CSS

## 📞 Support

For technical support or questions:

1. **Check Documentation**: Review this README thoroughly
2. **Database Issues**: Verify connection and permissions
3. **Configuration Help**: Review config files
4. **Custom Development**: Extend functionality as needed

## 📝 License

This project is provided as-is for educational and commercial use. Feel free to modify and distribute according to your needs.

---

**CourierPro PHP Version** - A complete courier management solution with modern web technologies and robust security features.