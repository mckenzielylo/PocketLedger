# 🚀 PocketLedger Railway.app Deployment Guide

This guide will help you deploy PocketLedger to Railway.app, a modern cloud platform for deploying applications.

## 📋 Prerequisites

- [Railway.app account](https://railway.app)
- GitHub repository with your PocketLedger code
- PostgreSQL database (Railway provides this)

## 🚀 Quick Deployment

### 1. Connect Your Repository

1. Go to [Railway.app](https://railway.app)
2. Click **"New Project"**
3. Select **"Deploy from GitHub repo"**
4. Choose your PocketLedger repository
5. Railway will automatically detect the Dockerfile

### 2. Add PostgreSQL Database

1. In your Railway project, click **"+ New"**
2. Select **"Database"** → **"PostgreSQL"**
3. Railway will automatically provision a PostgreSQL database
4. Note the database connection details

### 3. Configure Environment Variables

1. Go to your web service settings
2. Click **"Variables"** tab
3. Add the following environment variables:

#### Required Variables:
```bash
APP_NAME=PocketLedger
APP_ENV=production
APP_DEBUG=false
APP_KEY=base64:your-generated-key-here
```

#### Database Variables (Railway provides these):
```bash
DB_CONNECTION=pgsql
DB_HOST=${{Postgres.PGHOST}}
DB_PORT=${{Postgres.PGPORT}}
DB_DATABASE=${{Postgres.PGDATABASE}}
DB_USERNAME=${{Postgres.PGUSER}}
DB_PASSWORD=${{Postgres.PGPASSWORD}}
```

#### Application Variables:
```bash
DEFAULT_CURRENCY=USD
DEFAULT_CURRENCY_SYMBOL=$
MAX_FILE_SIZE=2048
ALLOWED_FILE_TYPES=jpg,jpeg,png,pdf
```

### 4. Deploy

1. Railway will automatically build and deploy your application
2. The build process will:
   - Install Node.js dependencies
   - Build frontend assets
   - Install PHP dependencies
   - Run database migrations
   - Start the application

## 🔧 Configuration Details

### Railway-Specific Features

- **Dynamic Port**: Railway provides a `$PORT` environment variable
- **Ephemeral Filesystem**: Uses file-based sessions and cache
- **Automatic HTTPS**: Railway provides SSL certificates
- **Health Checks**: Built-in health monitoring

### Environment Variables

| Variable | Description | Default |
|----------|-------------|---------|
| `APP_KEY` | Laravel application key | Generated automatically |
| `DB_CONNECTION` | Database connection type | `pgsql` |
| `SESSION_DRIVER` | Session storage driver | `file` |
| `CACHE_STORE` | Cache storage driver | `file` |
| `QUEUE_CONNECTION` | Queue connection | `sync` |
| `PORT` | Application port | Railway provides this |

### File Structure

```
pocketledger/
├── Dockerfile                 # Railway-optimized Dockerfile
├── railway.json              # Railway configuration
├── .railway.env.example      # Environment variables template
├── docker/
│   ├── railway-entrypoint.sh # Railway startup script
│   ├── railway-nginx.conf    # Railway Nginx config
│   └── railway-supervisord.conf # Railway Supervisor config
└── RAILWAY_DEPLOYMENT.md     # This guide
```

## 🗄️ Database Setup

### Automatic Migration

The Railway entrypoint script automatically runs database migrations on startup:

```bash
php artisan migrate --force
```

### Manual Database Operations

If you need to run database operations manually:

1. Go to your Railway project
2. Click on your web service
3. Go to **"Deployments"** tab
4. Click **"View Logs"** to see the application logs

### Seeding (Optional)

To seed the database with sample data:

1. Set `SEED_DATABASE=true` in your environment variables
2. Redeploy your application

## 📊 Monitoring & Logs

### Viewing Logs

1. Go to your Railway project
2. Click on your web service
3. Go to **"Deployments"** tab
4. Click **"View Logs"**

### Health Checks

Railway automatically monitors your application health:
- Health check endpoint: `/health`
- Check interval: 30 seconds
- Timeout: 10 seconds

### Metrics

Railway provides built-in metrics for:
- CPU usage
- Memory usage
- Network traffic
- Response times

## 🔒 Security

### Environment Variables

- Never commit sensitive data to your repository
- Use Railway's environment variables for secrets
- Railway automatically encrypts environment variables

### HTTPS

- Railway provides automatic HTTPS
- SSL certificates are managed automatically
- All traffic is encrypted by default

## 🚀 Scaling

### Automatic Scaling

Railway automatically scales your application based on:
- CPU usage
- Memory usage
- Request volume

### Manual Scaling

To manually scale your application:

1. Go to your Railway project
2. Click on your web service
3. Go to **"Settings"** tab
4. Adjust the scaling settings

## 🛠️ Troubleshooting

### Common Issues

#### Build Failures
- Check the build logs in Railway dashboard
- Ensure all dependencies are properly configured
- Verify the Dockerfile is correct

#### Database Connection Issues
- Verify database environment variables
- Check if PostgreSQL service is running
- Ensure database credentials are correct

#### Application Not Starting
- Check the application logs
- Verify all required environment variables are set
- Ensure the entrypoint script is executable

### Getting Help

1. Check Railway's [documentation](https://docs.railway.app)
2. View your application logs
3. Check the Railway community forums
4. Contact Railway support

## 📈 Performance Optimization

### Caching

Railway-optimized configuration includes:
- File-based session storage
- File-based cache storage
- Optimized PHP-FPM settings
- Nginx caching for static assets

### Database Optimization

- PostgreSQL connection pooling
- Optimized query performance
- Automatic database backups

## 🔄 Updates & Maintenance

### Automatic Deployments

Railway automatically deploys when you push to your main branch:
1. Push changes to your repository
2. Railway detects the changes
3. Builds and deploys automatically
4. Updates your application

### Manual Deployments

To manually trigger a deployment:
1. Go to your Railway project
2. Click **"Deploy"** button
3. Railway will build and deploy the latest code

## 🎉 Success!

Your PocketLedger application is now deployed on Railway.app! 

### Next Steps

1. **Test your application** - Visit your Railway URL
2. **Configure domain** - Add a custom domain if needed
3. **Set up monitoring** - Configure alerts and notifications
4. **Scale as needed** - Adjust resources based on usage

### Useful Links

- [Railway Dashboard](https://railway.app/dashboard)
- [Railway Documentation](https://docs.railway.app)
- [Railway Community](https://discord.gg/railway)

---

**Happy deploying! 🚀**
