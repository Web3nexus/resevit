#!/bin/bash

# Make script executable
chmod +x /Users/vincent/Herd/resevit/init_git.sh

# Navigate to project directory
cd /Users/vincent/Herd/resevit

# Initialize Git repository
git init

# Add all files
git add .

# Create initial commit
git commit -m "Initial commit: Laravel resevit project"

# Add GitHub remote
git remote add origin https://github.com/Web3nexus/resevit.git

# Set main branch
git branch -M main

# Push to GitHub
git push -u origin main

echo "Successfully pushed resevit project to GitHub!"
