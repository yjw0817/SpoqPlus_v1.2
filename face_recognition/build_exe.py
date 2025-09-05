#!/usr/bin/env python
# -*- coding: utf-8 -*-
"""
InsightFace Service EXE 빌드 스크립트
PyInstaller를 사용하여 insightface_service_r1.py를 실행 파일로 변환
"""

import os
import sys
import shutil
import PyInstaller.__main__

def clean_build_dirs():
    """빌드 디렉토리 정리"""
    dirs_to_clean = ['build', 'dist', '__pycache__']
    for dir_name in dirs_to_clean:
        if os.path.exists(dir_name):
            print(f"Cleaning {dir_name}...")
            shutil.rmtree(dir_name)
    
    # .spec 파일 삭제
    spec_files = [f for f in os.listdir('.') if f.endswith('.spec')]
    for spec_file in spec_files:
        print(f"Removing {spec_file}...")
        os.remove(spec_file)

def build_exe():
    """EXE 파일 빌드"""
    
    # PyInstaller 옵션 설정
    options = [
        'insightface_service_r1.py',           # 메인 스크립트
        '--name=InsightFaceService',           # 실행 파일 이름
        '--onefile',                            # 단일 실행 파일로 생성
        '--console',                            # 콘솔 창 표시 (서비스 로그 확인용)
        '--icon=NONE',                          # 아이콘 (필요시 .ico 파일 경로 지정)
        '--clean',                              # 빌드 전 캐시 정리
        
        # 숨겨진 import 추가 (InsightFace 관련)
        '--hidden-import=insightface',
        '--hidden-import=onnxruntime',
        '--hidden-import=opencv-python',
        '--hidden-import=cv2',
        '--hidden-import=PIL',
        '--hidden-import=numpy',
        '--hidden-import=flask',
        '--hidden-import=flask_cors',
        '--hidden-import=pymssql',
        '--hidden-import=mysql.connector',
        '--hidden-import=pyodbc',
        '--hidden-import=sklearn',
        '--hidden-import=scipy',
        
        # InsightFace 모델 관련
        '--hidden-import=insightface.app',
        '--hidden-import=insightface.model_zoo',
        '--hidden-import=insightface.utils',
        
        # 추가 데이터 파일 포함
        '--add-data=templates;templates',       # HTML 템플릿 (있는 경우)
        
        # 런타임 옵션
        '--noconfirm',                          # 덮어쓰기 확인 없음
        '--log-level=INFO',                     # 로그 레벨
        
        # 최적화 옵션
        '--optimize=1',                         # 바이트코드 최적화
    ]
    
    # Windows에서 경로 구분자 조정
    if sys.platform == 'win32':
        options = [opt.replace(';', os.pathsep) for opt in options]
    
    print("Building EXE file...")
    print(f"Options: {options}")
    
    try:
        PyInstaller.__main__.run(options)
        print("\n✅ Build completed successfully!")
        print(f"📁 EXE file location: dist/InsightFaceService.exe")
        
        # 빌드 후 안내
        print("\n📋 다음 단계:")
        print("1. dist/InsightFaceService.exe 파일을 원하는 위치로 복사")
        print("2. InsightFace 모델 파일(.onnx)을 같은 폴더에 복사")
        print("3. 필요한 경우 config.json 파일 생성")
        print("4. 관리자 권한으로 실행")
        
    except Exception as e:
        print(f"\n❌ Build failed: {e}")
        sys.exit(1)

def create_spec_file():
    """고급 설정을 위한 .spec 파일 생성"""
    spec_content = '''# -*- mode: python ; coding: utf-8 -*-

block_cipher = None

a = Analysis(
    ['insightface_service_r1.py'],
    pathex=[],
    binaries=[],
    datas=[
        ('templates', 'templates'),  # HTML 템플릿
    ],
    hiddenimports=[
        'insightface',
        'onnxruntime',
        'cv2',
        'PIL',
        'numpy',
        'flask',
        'flask_cors',
        'pymssql',
        'mysql.connector',
        'pyodbc',
        'sklearn',
        'scipy',
        'insightface.app',
        'insightface.model_zoo',
        'insightface.utils',
    ],
    hookspath=[],
    hooksconfig={},
    runtime_hooks=[],
    excludes=[
        'matplotlib',
        'tkinter',
        'jupyter',
        'notebook',
        'ipython',
        'pytest',
    ],
    win_no_prefer_redirects=False,
    win_private_assemblies=False,
    cipher=block_cipher,
    noarchive=False,
)

pyz = PYZ(a.pure, a.zipped_data, cipher=block_cipher)

exe = EXE(
    pyz,
    a.scripts,
    a.binaries,
    a.zipfiles,
    a.datas,
    [],
    name='InsightFaceService',
    debug=False,
    bootloader_ignore_signals=False,
    strip=False,
    upx=True,
    upx_exclude=[],
    runtime_tmpdir=None,
    console=True,
    disable_windowed_traceback=False,
    argv_emulation=False,
    target_arch=None,
    codesign_identity=None,
    entitlements_file=None,
    icon=None,  # 'icon.ico' 파일이 있으면 여기에 경로 지정
)
'''
    
    with open('InsightFaceService.spec', 'w', encoding='utf-8') as f:
        f.write(spec_content)
    
    print("✅ Created InsightFaceService.spec file")
    print("📝 You can customize this file and run: pyinstaller InsightFaceService.spec")

if __name__ == '__main__':
    print("=" * 60)
    print("InsightFace Service EXE Builder")
    print("=" * 60)
    
    # 옵션 선택
    print("\nSelect build option:")
    print("1. Quick build (기본 설정)")
    print("2. Create .spec file (고급 설정)")
    print("3. Clean build directories")
    
    choice = input("\nEnter choice (1-3): ").strip()
    
    if choice == '1':
        clean_build_dirs()
        build_exe()
    elif choice == '2':
        create_spec_file()
    elif choice == '3':
        clean_build_dirs()
        print("✅ Build directories cleaned")
    else:
        print("Invalid choice")
        sys.exit(1)