# 🔧 Build Troubleshooting Guide

## Common Build Issues and Solutions

### Issue: `npm run build` fails with exit code 127

**Error Message:**
```
error: failed to solve: process "/bin/sh -c npm run build" did not complete successfully: exit code: 127
```

**Possible Causes & Solutions:**

#### 1. Node.js/npm not found
- **Cause:** The build environment doesn't have Node.js or npm installed
- **Solution:** Use the single-stage Dockerfile (`Dockerfile.render`) which includes Node.js

#### 2. Missing dependencies
- **Cause:** Some npm packages failed to install
- **Solution:** 
  ```bash
  # Test locally first
  ./build-test.sh
  
  # Or manually:
  npm install
  npm run build
  ```

#### 3. File permissions issues
- **Cause:** Docker can't access certain files
- **Solution:** Check `.dockerignore` and ensure all required files are copied

#### 4. Memory issues during build
- **Cause:** Build process runs out of memory
- **Solution:** Use the single-stage Dockerfile which is more memory efficient

### Issue: PHP extensions installation fails

**Error Message:**
```
error: failed to solve: process "/bin/sh -c docker-php-ext-install -j$(nproc) pdo pdo_sqlite pdo_mysql pdo_pgsql gd zip intl mbstring bcmath opcache" did not complete successfully: exit code: 1
```

**Possible Causes & Solutions:**

#### 1. Missing system dependencies
- **Cause:** Required system packages for PHP extensions are missing
- **Solution:** Use `Dockerfile.simple` which has minimal dependencies

#### 2. Extension conflicts
- **Cause:** Installing too many extensions at once causes conflicts
- **Solution:** Use the simplified Dockerfile that installs extensions separately

#### 3. Alpine package issues
- **Cause:** Some Alpine packages might be incompatible
- **Solution:** Try the ultra-simple Dockerfile with minimal extensions

**Possible Causes & Solutions:**

#### 1. Node.js/npm not found
- **Cause:** The build environment doesn't have Node.js or npm installed
- **Solution:** Use the single-stage Dockerfile (`Dockerfile.render`) which includes Node.js

#### 2. Missing dependencies
- **Cause:** Some npm packages failed to install
- **Solution:** 
  ```bash
  # Test locally first
  ./build-test.sh
  
  # Or manually:
  npm install
  npm run build
  ```

#### 3. File permissions issues
- **Cause:** Docker can't access certain files
- **Solution:** Check `.dockerignore` and ensure all required files are copied

#### 4. Memory issues during build
- **Cause:** Build process runs out of memory
- **Solution:** Use the single-stage Dockerfile which is more memory efficient

## Quick Fixes

### Option 1: Use Single-Stage Dockerfile
```bash
# For Render.com, use one of these:
# Option A: Dockerfile.render (full features)
# Option B: Dockerfile.simple (minimal, most reliable)
```

### Option 2: Use Ultra-Simple Dockerfile
```bash
# Use the minimal version for maximum compatibility
# In Render.com settings, specify: Dockerfile.simple
```

### Option 3: Test Build Locally
```bash
# Run the build test script
./build-test.sh

# If it passes locally, the issue is with Docker environment
```

### Option 4: Debug Docker Build
```bash
# Build with verbose output
docker build --no-cache --progress=plain -t pocketledger .

# Check the build logs for specific errors
```

## Alternative Deployment Options

### For Render.com
1. Use `Dockerfile.render` (single-stage)
2. Ensure all environment variables are set
3. Use PostgreSQL database service

### For Railway.app
1. Use the main `Dockerfile` (multi-stage)
2. Follow the Railway deployment guide
3. Use Railway's PostgreSQL service

## Environment-Specific Issues

### Render.com Specific
- Use single-stage Dockerfile
- Ensure `PORT` environment variable is set
- Use file-based sessions and cache

### Railway.app Specific
- Use multi-stage Dockerfile
- Ensure `$PORT` is handled correctly
- Use Railway's built-in services

## Getting Help

If you're still having issues:

1. **Check the build logs** - Look for specific error messages
2. **Test locally** - Run `./build-test.sh` to verify your setup
3. **Try different Dockerfiles** - Switch between single-stage and multi-stage
4. **Check platform documentation** - Render.com and Railway.app have specific requirements

## Common Commands

```bash
# Test build locally
./build-test.sh

# Build Docker image
docker build -t pocketledger .

# Run container locally
docker run -p 80:80 pocketledger

# Check container logs
docker logs <container-id>
```
