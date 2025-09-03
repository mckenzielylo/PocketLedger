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

#### 4. Oniguruma package missing
- **Cause:** Missing `oniguruma-dev` package required for `mbstring` extension
- **Solution:** Use `Dockerfile.minimal` which includes all required packages

#### 5. Composer package discovery error
- **Cause:** Laravel package discovery fails during build due to missing environment variables
- **Solution:** Use updated Dockerfiles with proper environment variables and --no-scripts flag

#### 6. Bootstrap cache directory error
- **Cause:** Laravel bootstrap/cache directory missing or not writable
- **Solution:** Use updated Dockerfiles that create required directories with proper permissions

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

### Option 1: Use Minimal Dockerfile (Recommended)
```bash
# For maximum compatibility, use:
# Dockerfile.minimal - Absolute minimum extensions
# Dockerfile.simple - PostgreSQL optimized
# Dockerfile.render - Full features
```

### Option 2: Fix Composer Package Discovery Error
```bash
# If you get "package:discover --ansi handling the post-autoload-dump event returned with error code 1":
# Use Dockerfile.minimal which includes proper environment variables and --no-scripts flag
```

### Option 3: Fix Bootstrap Cache Directory Error
```bash
# If you get "bootstrap/cache directory must be present and writable" error:
# Use updated Dockerfiles that create required directories with proper permissions
```

### Option 4: Fix Oniguruma Error
```bash
# If you get "oniguruma package not met" error:
# Use Dockerfile.minimal which includes oniguruma-dev
```

### Option 5: Test Build Locally
```bash
# Run the build test script
./build-test.sh

# If it passes locally, the issue is with Docker environment
```

### Option 6: Debug Docker Build
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
