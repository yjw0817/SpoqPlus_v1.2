#!/usr/bin/env python3
"""
Test the detect_for_registration endpoint
"""
import requests
import json

def test_endpoint():
    """Test if the endpoint is working"""
    
    url = "http://localhost:5002/api/face/detect_for_registration"
    
    # Test with empty FormData (should return 400 with proper error)
    print("Testing with empty FormData...")
    try:
        response = requests.post(url, files={})
        print(f"Status: {response.status_code}")
        if response.status_code == 400:
            data = response.json()
            print(f"Response: {json.dumps(data, indent=2)}")
            
            # Check if all required fields are present
            required_fields = ['success', 'face_detected', 'suitable_for_registration']
            missing_fields = [field for field in required_fields if field not in data]
            
            if missing_fields:
                print(f"⚠️  Missing fields: {missing_fields}")
            else:
                print("✅ All required fields present in error response")
    except Exception as e:
        print(f"Error: {e}")
    
    print("\n" + "="*60)
    print("Expected behavior:")
    print("- 400 status code when no image is provided")
    print("- Response should include all required fields:")
    print("  - success: false")
    print("  - face_detected: false")  
    print("  - suitable_for_registration: false")
    print("  - error: descriptive message")
    print("  - recommendations: array of suggestions")

if __name__ == "__main__":
    test_endpoint()