# 🐘 PostgreSQL Deployment Guide for PocketLedger

## Overview

PocketLedger has been optimized for PostgreSQL deployment across multiple platforms. This guide covers the PostgreSQL-specific configurations and deployment options.

## 🚀 Quick Start

### For Render.com
```bash
# Use the simple Dockerfile for maximum compatibility
# In Render.com settings, specify: Dockerfile.simple
```

### For Railway.app
```bash
# Use the simple Dockerfile for Railway deployment
# In railway.json, dockerfilePath is set to: Dockerfile.simple
```

## 📁 PostgreSQL-Optimized Files

### Dockerfiles
- **`Dockerfile.simple`** - Ultra-minimal with PostgreSQL-only extensions
- **`Dockerfile.render`** - Single-stage with PostgreSQL focus
- **`Dockerfile`** - Multi-stage with PostgreSQL optimization

### Configuration Files
- **`.env.example`** - PostgreSQL production settings
- **`.railway.env.example`** - Railway-specific PostgreSQL config
- **`render.yaml`** - Render.com PostgreSQL service configuration
- **`railway.json`** - Railway PostgreSQL deployment config

## 🔧 PostgreSQL Configuration

### Database Connection
```env
DB_CONNECTION=pgsql
DB_HOST=your-postgres-host
DB_PORT=5432
DB_DATABASE=pocketledger
DB_USERNAME=postgres
DB_PASSWORD=your-password
```

### PHP Extensions
All Dockerfiles include these PostgreSQL-optimized PHP extensions:
- `pdo` - PHP Data Objects
- `pdo_pgsql` - PostgreSQL driver for PDO
- `mbstring` - Multi-byte string support
- `bcmath` - Arbitrary precision mathematics
- `zip` - Archive handling
- `gd` - Image processing

## 🌐 Platform-Specific Setup

### Render.com
1. **Create PostgreSQL Service:**
   ```yaml
   # In render.yaml
   - type: pserv
     name: pocketledger-db
     env: postgres
   ```

2. **Web Service Configuration:**
   ```yaml
   # Uses Dockerfile.simple
   dockerfilePath: ./Dockerfile.simple
   ```

3. **Environment Variables:**
   - Database connection automatically configured via `fromDatabase` references
   - PostgreSQL service provides host, port, database, user, password

### Railway.app
1. **Add PostgreSQL Service:**
   - Add PostgreSQL service in Railway dashboard
   - Environment variables automatically configured

2. **Deployment Configuration:**
   ```json
   {
     "build": {
       "dockerfilePath": "Dockerfile.simple"
     }
   }
   ```

3. **Environment Variables:**
   ```env
   DB_CONNECTION=pgsql
   DB_HOST=${{Postgres.PGHOST}}
   DB_PORT=${{Postgres.PGPORT}}
   DB_DATABASE=${{Postgres.PGDATABASE}}
   DB_USERNAME=${{Postgres.PGUSER}}
   DB_PASSWORD=${{Postgres.PGPASSWORD}}
   ```

## 🗄️ Database Setup

### Initial Migration
```bash
# Run migrations after deployment
php artisan migrate

# Seed with sample data (optional)
php artisan db:seed
```

### Multi-Currency Sample Data
```bash
# Add multi-currency sample data
php artisan db:seed --class=MultiCurrencySampleSeeder
```

## 🔒 Security Considerations

### Production Settings
- `APP_DEBUG=false`
- `APP_ENV=production`
- `SESSION_SECURE_COOKIE=true`
- `LOG_LEVEL=error`

### Database Security
- Use strong passwords
- Enable SSL connections (`sslmode=prefer`)
- Restrict database access to application servers only

## 📊 Performance Optimization

### PostgreSQL Settings
- Connection pooling recommended for high traffic
- Proper indexing on frequently queried columns
- Regular VACUUM and ANALYZE operations

### Laravel Optimizations
- `CACHE_STORE=file` for simple caching
- `QUEUE_CONNECTION=sync` for immediate processing
- Optimized autoloader with `--optimize-autoloader`

## 🚨 Troubleshooting

### Common Issues

#### Connection Refused
```bash
# Check database host and port
# Verify PostgreSQL service is running
# Check firewall settings
```

#### Authentication Failed
```bash
# Verify username and password
# Check PostgreSQL user permissions
# Ensure database exists
```

#### Extension Not Found
```bash
# Ensure pdo_pgsql extension is installed
# Check PHP configuration
# Verify Dockerfile includes postgresql-dev
```

### Debug Commands
```bash
# Test database connection
php artisan tinker
>>> DB::connection()->getPdo();

# Check migrations status
php artisan migrate:status

# View database configuration
php artisan config:show database
```

## 📈 Monitoring

### Health Checks
- `/health` endpoint for basic health check
- `/health/db` endpoint for database connectivity
- `/health/cache` endpoint for cache functionality

### Logs
- Application logs: `storage/logs/laravel.log`
- Database query logs: Enable in `config/database.php`
- Error tracking: Configure with external service

## 🔄 Backup Strategy

### Database Backups
```bash
# Create backup
pg_dump -h host -U username -d database > backup.sql

# Restore backup
psql -h host -U username -d database < backup.sql
```

### Automated Backups
- Use platform-specific backup services
- Schedule regular backups
- Test restore procedures

## 📚 Additional Resources

- [PostgreSQL Documentation](https://www.postgresql.org/docs/)
- [Laravel Database Configuration](https://laravel.com/docs/database)
- [Render.com PostgreSQL Guide](https://render.com/docs/databases)
- [Railway.app PostgreSQL Guide](https://docs.railway.app/databases/postgresql)

## 🆘 Support

If you encounter issues:
1. Check the troubleshooting section above
2. Review platform-specific documentation
3. Check application logs for detailed error messages
4. Verify environment variables are correctly set

---

**Happy deploying with PostgreSQL! 🐘**
