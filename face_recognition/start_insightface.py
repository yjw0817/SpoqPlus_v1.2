#!/usr/bin/env python
# -*- coding: utf-8 -*-
"""
InsightFace Service Starter
Simple Python script to start the service without batch file issues
"""

import sys
import os
import subprocess

def check_requirements():
    """Check if all required packages are installed"""
    required_packages = [
        'insightface',
        'flask',
        'flask_cors',
        'cv2',
        'numpy',
        'sklearn',
        'mysql.connector',
        'PIL'
    ]
    
    missing_packages = []
    
    for package in required_packages:
        try:
            if package == 'cv2':
                import cv2
            elif package == 'flask_cors':
                import flask_cors
            elif package == 'PIL':
                import PIL
            elif package == 'sklearn':
                import sklearn
            else:
                __import__(package)
        except ImportError:
            missing_packages.append(package)
    
    return missing_packages

def main():
    print("=" * 50)
    print("InsightFace Service Starter")
    print("=" * 50)
    print()
    
    # Check Python version
    print(f"Python Version: {sys.version}")
    print()
    
    # Check required packages
    print("Checking required packages...")
    missing = check_requirements()
    
    if missing:
        print(f"\nMissing packages: {', '.join(missing)}")
        print("\nInstalling missing packages...")
        
        # Map package names to pip install names
        pip_names = {
            'cv2': 'opencv-python-headless',
            'flask_cors': 'flask-cors',
            'PIL': 'pillow',
            'sklearn': 'scikit-learn',
            'mysql.connector': 'mysql-connector-python'
        }
        
        for package in missing:
            pip_name = pip_names.get(package, package)
            print(f"\nInstalling {pip_name}...")
            subprocess.check_call([sys.executable, '-m', 'pip', 'install', pip_name])
    
    # Check if insightface_service.py exists
    if not os.path.exists('insightface_service.py'):
        print("\nERROR: insightface_service.py not found!")
        print("Please make sure you're in the correct directory.")
        input("\nPress Enter to exit...")
        return
    
    # Start the service
    print("\n" + "=" * 50)
    print("Starting InsightFace Service...")
    print("Server will run on http://localhost:5002")
    print("Press Ctrl+C to stop the server")
    print("=" * 50 + "\n")
    
    try:
        subprocess.call([sys.executable, 'insightface_service.py'])
    except KeyboardInterrupt:
        print("\n\nServer stopped by user.")
    except Exception as e:
        print(f"\nERROR: {str(e)}")
        input("\nPress Enter to exit...")

if __name__ == "__main__":
    main()