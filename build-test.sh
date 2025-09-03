#!/bin/bash

# =============================================================================
# Build Test Script for PocketLedger
# This script helps test the build process locally before deployment
# =============================================================================

echo "🚀 Testing PocketLedger Build Process"
echo "======================================"

# Check if Node.js is installed
if ! command -v node &> /dev/null; then
    echo "❌ Node.js is not installed. Please install Node.js 18 or later."
    exit 1
fi

# Check if npm is installed
if ! command -v npm &> /dev/null; then
    echo "❌ npm is not installed. Please install npm."
    exit 1
fi

# Check Node.js version
NODE_VERSION=$(node --version | cut -d'v' -f2 | cut -d'.' -f1)
if [ "$NODE_VERSION" -lt 18 ]; then
    echo "❌ Node.js version 18 or later is required. Current version: $(node --version)"
    exit 1
fi

echo "✅ Node.js version: $(node --version)"
echo "✅ npm version: $(npm --version)"

# Check if package.json exists
if [ ! -f "package.json" ]; then
    echo "❌ package.json not found. Are you in the correct directory?"
    exit 1
fi

echo "✅ package.json found"

# Install dependencies
echo "📦 Installing dependencies..."
if npm install; then
    echo "✅ Dependencies installed successfully"
else
    echo "❌ Failed to install dependencies"
    exit 1
fi

# Check if required files exist
echo "🔍 Checking required files..."

REQUIRED_FILES=(
    "vite.config.js"
    "tailwind.config.js"
    "postcss.config.js"
    "resources/css/app.css"
    "resources/js/app.js"
)

for file in "${REQUIRED_FILES[@]}"; do
    if [ -f "$file" ]; then
        echo "✅ $file exists"
    else
        echo "❌ $file is missing"
        exit 1
    fi
done

# Test the build
echo "🔨 Testing build process..."
if npm run build; then
    echo "✅ Build completed successfully!"
    echo "📁 Built files:"
    ls -la public/build/
else
    echo "❌ Build failed!"
    echo "🔍 Debug information:"
    echo "Node version: $(node --version)"
    echo "NPM version: $(npm --version)"
    echo "Current directory: $(pwd)"
    echo "Files in current directory:"
    ls -la
    exit 1
fi

echo ""
echo "🎉 All tests passed! Your build should work in Docker."
echo "💡 If you're still having issues with Render.com, try using Dockerfile.render instead."
