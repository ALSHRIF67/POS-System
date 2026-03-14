#!/bin/bash

# Script to automatically add, commit, and push code to GitHub

# Change to your project directory (optional if running from project root)
# cd /path/to/your/project

# Add all changes
git add .

# Commit with a timestamp message
git commit -m "Auto-update: Employee feature changes $(date '+%Y-%m-%d %H:%M:%S')"

# Push to main branch
git push origin main

echo "✅ Project successfully updated to GitHub!"