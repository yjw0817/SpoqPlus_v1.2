# -*- mode: python ; coding: utf-8 -*-

block_cipher = None

a = Analysis(
    ['test_face_remote.py'],
    pathex=[],
    binaries=[],
    datas=[
        # 테스트 이미지 폴더가 있다면 포함
        # ('test_images', 'test_images'),
    ],
    hiddenimports=[
        'cv2',
        'numpy',
        'matplotlib',
        'matplotlib.backends.backend_tkagg',
        'requests',
        'PIL',
        'concurrent.futures',
    ],
    hookspath=[],
    hooksconfig={},
    runtime_hooks=[],
    excludes=[],
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
    name='FaceTestRemote',
    debug=False,
    bootloader_ignore_signals=False,
    strip=False,
    upx=True,
    upx_exclude=[],
    runtime_tmpdir=None,
    console=True,  # False로 하면 콘솔창 없음 (但 realtime 모드는 GUI 창이 있으므로 True 권장)
    disable_windowed_traceback=False,
    target_arch=None,
    codesign_identity=None,
    entitlements_file=None,
    icon='icon.ico' if os.path.exists('icon.ico') else None,
)