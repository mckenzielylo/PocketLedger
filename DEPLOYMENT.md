# 🚀 PocketLedger Deployment Guide

Complete deployment guide for PocketLedger on Render.com and other platforms.

## 📋 Prerequisites

- **Docker** installed locally (for testing)
- **Git** repository with PocketLedger code
- **Render.com account** (free tier available)
- **Domain name** (optional, for custom domain)

## 🐳 Docker Setup

### Local Development

```bash
# Build and run with Docker Compose
docker-compose up --build

# Access the application
open http://localhost:8000
```

### Production Build

```bash
# Build production image
docker build -t pocketledger:latest .

# Run production container
docker run -p 8000:80 \
  -e APP_ENV=production \
  -e APP_DEBUG=false \
  -e DB_CONNECTION=sqlite \
  pocketledger:latest
```

## 🌐 Render.com Deployment

### Method 1: Using render.yaml (Recommended)

1. **Push your code** to GitHub/GitLab
2. **Connect repository** to Render.com
3. **Deploy automatically** - Render will use the `render.yaml` configuration

### Method 2: Manual Setup

1. **Create Web Service**
   - **Environment**: Docker
   - **Dockerfile Path**: `./Dockerfile`
   - **Docker Context**: `.`
   - **Plan**: Starter (free) or higher

2. **Create Database Service**
   - **Type**: PostgreSQL
   - **Plan**: Starter (free) or higher
   - **Region**: Same as web service

3. **Create Redis Service**
   - **Type**: Redis
   - **Plan**: Starter (free) or higher
   - **Region**: Same as web service

4. **Create Background Worker**
   - **Type**: Background Worker
   - **Environment**: Docker
   - **Start Command**: `php artisan queue:work --sleep=3 --tries=3 --max-time=3600`

### Environment Variables

Set these in your Render.com dashboard:

#### Application Settings
```env
APP_NAME=PocketLedger
APP_ENV=production
APP_DEBUG=false
APP_URL=https://your-app-name.onrender.com
```

#### Database Settings
```env
DB_CONNECTION=pgsql
DB_HOST=your-db-host
DB_PORT=5432
DB_DATABASE=your-db-name
DB_USERNAME=your-db-user
DB_PASSWORD=your-db-password
```

#### Cache & Session Settings
```env
CACHE_STORE=redis
REDIS_HOST=your-redis-host
REDIS_PORT=6379
REDIS_PASSWORD=your-redis-password
SESSION_DRIVER=redis
SESSION_SECURE_COOKIE=true
```

#### Mail Settings
```env
MAIL_MAILER=smtp
MAIL_HOST=smtp.gmail.com
MAIL_PORT=587
MAIL_USERNAME=your-email@gmail.com
MAIL_PASSWORD=your-app-password
MAIL_FROM_ADDRESS=noreply@pocketledger.com
MAIL_FROM_NAME=PocketLedger
```

## 🔧 Configuration Options

### Database Options

#### PostgreSQL (Recommended for Production)
```env
DB_CONNECTION=pgsql
DB_HOST=your-postgres-host
DB_PORT=5432
DB_DATABASE=pocketledger
DB_USERNAME=your-username
DB_PASSWORD=your-password
```

#### MySQL (Alternative)
```env
DB_CONNECTION=mysql
DB_HOST=your-mysql-host
DB_PORT=3306
DB_DATABASE=pocketledger
DB_USERNAME=your-username
DB_PASSWORD=your-password
```

#### SQLite (Development Only)
```env
DB_CONNECTION=sqlite
DB_DATABASE=/var/www/html/database/database.sqlite
```

### File Storage Options

#### Local Storage (Default)
```env
FILESYSTEM_DISK=local
```

#### AWS S3 (Production)
```env
FILESYSTEM_DISK=s3
AWS_ACCESS_KEY_ID=your-access-key
AWS_SECRET_ACCESS_KEY=your-secret-key
AWS_DEFAULT_REGION=us-east-1
AWS_BUCKET=your-bucket-name
```

### Cache Options

#### Redis (Recommended)
```env
CACHE_STORE=redis
REDIS_HOST=your-redis-host
REDIS_PORT=6379
REDIS_PASSWORD=your-redis-password
```

#### Database Cache
```env
CACHE_STORE=database
```

## 🚀 Deployment Steps

### 1. Prepare Your Repository

```bash
# Ensure all files are committed
git add .
git commit -m "Ready for deployment"
git push origin main
```

### 2. Deploy to Render.com

1. **Connect Repository**
   - Go to Render.com dashboard
   - Click "New +" → "Web Service"
   - Connect your GitHub/GitLab repository

2. **Configure Service**
   - **Name**: `pocketledger-web`
   - **Environment**: `Docker`
   - **Dockerfile Path**: `./Dockerfile`
   - **Docker Context**: `.`
   - **Plan**: Choose based on your needs

3. **Set Environment Variables**
   - Copy from the `render.yaml` file
   - Or set manually in the dashboard

4. **Deploy**
   - Click "Create Web Service"
   - Wait for deployment to complete

### 3. Create Additional Services

1. **Database Service**
   - **Type**: PostgreSQL
   - **Name**: `pocketledger-db`
   - **Plan**: Starter or higher

2. **Redis Service**
   - **Type**: Redis
   - **Name**: `pocketledger-redis`
   - **Plan**: Starter or higher

3. **Background Worker**
   - **Type**: Background Worker
   - **Environment**: Docker
   - **Start Command**: `php artisan queue:work --sleep=3 --tries=3 --max-time=3600`

## 🔍 Health Checks

The application includes health check endpoints:

- **Main Health Check**: `GET /health`
- **Database Check**: `GET /health/db`
- **Cache Check**: `GET /health/cache`

## 📊 Monitoring

### Render.com Dashboard
- Monitor service health
- View logs and metrics
- Check resource usage

### Application Logs
```bash
# View logs in Render.com dashboard
# Or check container logs locally
docker logs pocketledger-app
```

## 🔒 Security Considerations

### Production Security
- Set `APP_DEBUG=false`
- Use `SESSION_SECURE_COOKIE=true`
- Configure proper CORS settings
- Use HTTPS (automatic on Render.com)
- Set strong database passwords

### Environment Variables
- Never commit `.env` files
- Use Render.com's environment variable system
- Rotate secrets regularly

## 🛠️ Troubleshooting

### Common Issues

#### Build Failures
```bash
# Check Docker build locally
docker build -t pocketledger:test .

# Check logs in Render.com dashboard
```

#### Database Connection Issues
```bash
# Verify database credentials
# Check network connectivity
# Ensure database service is running
```

#### File Upload Issues
```bash
# Check storage permissions
# Verify file size limits
# Check disk space
```

#### Performance Issues
```bash
# Enable Redis caching
# Optimize database queries
# Use CDN for static assets
```

### Debug Mode

For debugging, temporarily set:
```env
APP_DEBUG=true
LOG_LEVEL=debug
```

## 📈 Scaling

### Horizontal Scaling
- Use multiple web service instances
- Load balance with Render.com
- Scale background workers

### Vertical Scaling
- Upgrade to higher plans
- Increase memory and CPU
- Optimize database queries

## 🔄 Updates and Maintenance

### Deploying Updates
```bash
# Push changes to repository
git push origin main

# Render.com will automatically redeploy
```

### Database Migrations
```bash
# Migrations run automatically on deployment
# Or run manually in Render.com shell
php artisan migrate
```

### Backup Strategy
- Regular database backups
- File storage backups
- Environment variable backups

## 📞 Support

### Render.com Support
- [Render.com Documentation](https://render.com/docs)
- [Render.com Community](https://community.render.com)

### PocketLedger Support
- Check application logs
- Review error messages
- Test locally with Docker

---

**Happy deploying! 🎉**

Your PocketLedger application is now ready for production deployment on Render.com!
