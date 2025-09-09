// 락커 배치 관리 시스템 - Enhanced Version
// Ported from Locker4 Vue Project (LockerPlacementFigma.vue)

(function() {
    'use strict';

    // ============================================
    // 전역 상태 관리
    // ============================================
    const state = {
        // 락커 및 타입 데이터
        lockers: [],
        lockerTypes: [],
        zones: [],
        
        // 선택 상태
        selectedLocker: null,
        selectedLockerIds: new Set(),
        selectedType: null,
        selectedZone: null,
        
        // 드래그 상태
        isDragging: false,
        dragOffset: { x: 0, y: 0 },
        draggedLockers: [],
        
        // 뷰 모드
        currentViewMode: 'floor', // 'floor' | 'front'
        isTransitioningToFloor: false,
        
        // 캔버스 상태
        zoomLevel: 1,
        panOffset: { x: 0, y: 0 },
        canvasWidth: 1000,
        canvasHeight: 700,
        
        // UI 상태
        showSelectionUI: true,
        contextMenuVisible: false,
        contextMenuPosition: { x: 0, y: 0 },
        
        // 드래그 선택
        isDragSelecting: false,
        selectionBox: {
            startX: 0,
            startY: 0,
            endX: 0,
            endY: 0
        },
        
        // 기타
        lockerDragJustFinished: false,
        alignGuides: [],
        LOCKER_VISUAL_SCALE: 2.0,
        GRID_SIZE: 20,
        SNAP_THRESHOLD: 10,
        hasAutoFitted: false
    };

    // ============================================
    // 초기화
    // ============================================
    document.addEventListener('DOMContentLoaded', function() {
        console.log('[Enhanced] Locker Placement System Initialized');
        
        // 초기 데이터 로드
        loadInitialData();
        
        // 이벤트 리스너 설정
        setupEventListeners();
        
        // 캔버스 초기화
        initializeCanvas();
        
        // 키보드 단축키 설정
        setupKeyboardShortcuts();
    });

    // ============================================
    // 데이터 로드 함수
    // ============================================
    function loadInitialData() {
        // 서버에서 미리 로딩된 데이터가 있으면 사용, 없으면 API 호출
        if (window.PreloadedData) {
            console.log('Using preloaded server data for fast loading');
            
            // 미리 로딩된 데이터를 상태에 설정
            if (window.PreloadedData.lockerZones) {
                state.zones = convertZoneData(window.PreloadedData.lockerZones);
                renderZoneTabs();
            }
            
            if (window.PreloadedData.lockerTypes) {
                state.lockerTypes = convertTypeData(window.PreloadedData.lockerTypes);
                renderLockerTypes();
            }
            
            // 첫 번째 구역을 기본 선택 (락커 로드 전에 구역 선택)
            if (state.zones.length > 0) {
                console.log('[Enhanced] Auto-selecting first zone:', state.zones[0]);
                state.selectedZone = state.zones[0].id; // 먼저 선택된 구역 설정
            }
            
            if (window.PreloadedData.lockers) {
                state.lockers = convertLockerData(window.PreloadedData.lockers);
                
                // 구역이 선택된 후에 switchZone 호출하여 렌더링
                if (state.selectedZone) {
                    switchZone(state.selectedZone);
                }
            } else {
                console.log('[Enhanced] No locker data in PreloadedData');
            }
        } else {
            // 서버 데이터가 없으면 기존 방식으로 API 호출
            console.log('No preloaded data found, falling back to API calls');
            loadZones();
            loadLockerTypes();
            loadLockers();
        }
    }

    // ============================================
    // 데이터 변환 함수 (서버 데이터 → JavaScript 형식)
    // ============================================
    function convertZoneData(serverZones) {
        return serverZones.map(zone => ({
            id: zone.LOCKR_KND_CD,
            name: zone.LOCKR_KND_NM,
            x: zone.X || 0,
            y: zone.Y || 0,
            width: zone.WIDTH || 800,
            height: zone.HEIGHT || 600,
            color: zone.COLOR || '#e5e7eb',
            lockerCount: 0 // 추후 계산
        }));
    }

    function convertTypeData(serverTypes) {
        return serverTypes.map(type => ({
            id: type.LOCKR_TYPE_CD,
            name: type.LOCKR_TYPE_NM,
            width: type.WIDTH,
            height: type.HEIGHT,
            depth: type.DEPTH,
            color: type.COLOR
        }));
    }

    function convertLockerData(serverLockers) {
        return serverLockers.map(locker => ({
            id: locker.LOCKR_CD,
            typeId: locker.LOCKR_TYPE_CD,
            zoneId: locker.LOCKR_KND,
            x: parseInt(locker.X) || 0,
            y: parseInt(locker.Y) || 0,
            rotation: parseInt(locker.ROTATION) || 0,
            status: locker.LOCKR_STAT || '00',
            number: locker.LOCKR_LABEL || locker.LOCKR_NO || '',
            label: locker.LOCKR_LABEL || ''
        }));
    }

    async function loadZones() {
        try {
            // API에서 구역 데이터 로드
            const zones = await window.LockerAPI.getZones();
            state.zones = zones;
            console.log('[Enhanced] Loaded zones:', zones);
        } catch (error) {
            console.error('[Enhanced] Failed to load zones:', error);
            // 폴백: 더미 데이터 사용
            state.zones = [
                { id: 1, name: 'A구역', lockerCount: 0 },
                { id: 2, name: 'B구역', lockerCount: 0 },
                { id: 3, name: 'C구역', lockerCount: 0 }
            ];
        }
        
        renderZoneTabs();
        
        // 첫 번째 구역 선택
        if (state.zones.length > 0) {
            console.log('[Enhanced] Auto-selecting first zone (fallback):', state.zones[0]);
            switchZone(state.zones[0].id);
        }
    }

    async function loadLockerTypes() {
        try {
            // API에서 락커 타입 데이터 로드
            const types = await window.LockerAPI.getLockerTypes();
            state.lockerTypes = types.map(type => ({
                id: type.id,
                name: type.name,
                width: type.width,
                depth: type.depth,
                height: type.height,
                color: type.color,
                isHidden: false
            }));
            console.log('[Enhanced] Loaded locker types:', state.lockerTypes);
        } catch (error) {
            console.error('[Enhanced] Failed to load locker types:', error);
            // 폴백: 더미 데이터 사용
            state.lockerTypes = [
                { id: 1, name: '소형', width: 40, depth: 40, height: 40, color: '#3b82f6', isHidden: false },
                { id: 2, name: '중형', width: 50, depth: 50, height: 60, color: '#10b981', isHidden: false },
                { id: 3, name: '대형', width: 60, depth: 60, height: 80, color: '#f59e0b', isHidden: false }
            ];
        }
        
        renderLockerTypes();
    }

    async function loadLockers() {
        if (!state.selectedZone) return;
        
        try {
            // API에서 락커 데이터 로드
            const compCd = window.LockerConfig?.companyCode || '001';
            const bcoffCd = window.LockerConfig?.officeCode || '001';
            const lockers = await window.LockerAPI.getLockers(compCd, bcoffCd, state.selectedZone);
            
            // API 응답 데이터를 앱 형식으로 변환 (이미 변환된 상태로 반환됨)
            state.lockers = lockers.filter(locker => locker.zoneId === state.selectedZone);
            console.log('[Enhanced] Loaded lockers for zone:', state.selectedZone, state.lockers);
        } catch (error) {
            console.error('[Enhanced] Failed to load lockers:', error);
            // 폴백: 더미 데이터 사용 (state.selectedZone은 이제 ID 문자열)
            const currentZoneId = state.selectedZone || (state.zones.length > 0 ? state.zones[0].id : 'zone-1');
            state.lockers = [
                { id: 'locker-1', typeId: 1, x: 100, y: 100, rotation: 0, zoneId: currentZoneId, number: 'A001' },
                { id: 'locker-2', typeId: 2, x: 200, y: 100, rotation: 0, zoneId: currentZoneId, number: 'A002' },
                { id: 'locker-3', typeId: 1, x: 300, y: 100, rotation: 90, zoneId: currentZoneId, number: 'A003' }
            ];
        }
        
        renderLockers();
    }

    // ============================================
    // 렌더링 함수
    // ============================================
    function renderZoneTabs() {
        const tabsContainer = document.querySelector('.zone-tabs .tabs-left');
        if (!tabsContainer) return;
        
        tabsContainer.innerHTML = '';
        
        state.zones.forEach(zone => {
            const tab = document.createElement('button');
            tab.className = 'zone-tab';
            tab.textContent = zone.name;
            // state.selectedZone은 이제 ID 문자열입니다
            if (state.selectedZone === zone.id) {
                tab.classList.add('active');
            }
            tab.onclick = () => selectZone(zone);
            tab.oncontextmenu = (e) => showZoneContextMenu(e, zone);
            
            tabsContainer.appendChild(tab);
        });
    }

    function renderLockerTypes() {
        const container = document.getElementById('lockerTypeList');
        if (!container) return;
        
        container.innerHTML = '';
        
        // 보이는 타입만 렌더링
        const visibleTypes = state.lockerTypes.filter(type => !type.isHidden);
        
        visibleTypes.forEach(type => {
            const itemWrapper = document.createElement('div');
            itemWrapper.className = 'locker-type-item-wrapper';
            
            const item = document.createElement('div');
            item.className = 'locker-type-item';
            if (state.selectedType?.id === type.id) {
                item.classList.add('active');
            }
            
            // SVG 프리뷰
            const visual = document.createElement('div');
            visual.className = 'type-visual';
            visual.innerHTML = createLockerPreviewSVG(type);
            
            // 타입 정보
            const info = document.createElement('div');
            info.className = 'type-info';
            info.innerHTML = `
                <span class="type-name">${type.name}</span>
                <span class="type-size">${type.width}x${type.depth}x${type.height}cm</span>
            `;
            
            item.appendChild(visual);
            item.appendChild(info);
            
            // 이벤트 핸들러
            item.onclick = () => selectLockerType(type);
            item.ondblclick = () => addLockerByDoubleClick(type);
            
            // 삭제 버튼
            const deleteBtn = document.createElement('button');
            deleteBtn.className = 'delete-type-button';
            deleteBtn.innerHTML = '×';
            deleteBtn.onclick = (e) => {
                e.stopPropagation();
                deleteLockerType(type);
            };
            
            itemWrapper.appendChild(item);
            itemWrapper.appendChild(deleteBtn);
            container.appendChild(itemWrapper);
        });
    }

    function renderLockers() {
        const svg = document.getElementById('lockerCanvas');
        if (!svg) return;
        
        
        // 기존 락커 그룹 제거
        let lockersGroup = svg.querySelector('#lockersGroup');
        if (lockersGroup) {
            lockersGroup.remove();
        }
        
        // 새 그룹 생성
        lockersGroup = document.createElementNS('http://www.w3.org/2000/svg', 'g');
        lockersGroup.id = 'lockersGroup';
        
        // 현재 구역의 락커만 렌더링 (state.selectedZone은 ID 문자열)
        const currentLockers = state.lockers.filter(l => l.zoneId === state.selectedZone);
        
        currentLockers.forEach(locker => {
            const lockerElement = createLockerSVG(locker);
            if (lockerElement) {
                lockersGroup.appendChild(lockerElement);
            }
        });
        
        svg.appendChild(lockersGroup);
        
        // 선택 UI 업데이트
        updateSelectionUI();
        
        // 초기 로드 시 락커들을 중앙에 배치
        if (currentLockers.length > 0 && !state.hasAutoFitted) {
            setTimeout(() => {
                autoFitLockers();
                state.hasAutoFitted = true;
            }, 100);
        }
    }

    // ============================================
    // SVG 생성 함수
    // ============================================
    function createLockerPreviewSVG(type) {
        const scale = state.LOCKER_VISUAL_SCALE;
        const width = (type.width || 40) * scale;
        const height = (type.depth || type.width || 40) * scale;
        
        return `
            <svg width="${width}" height="${height}" viewBox="0 0 ${width} ${height}">
                <rect 
                    x="2" y="2" 
                    width="${width - 4}" 
                    height="${height - 4}"
                    fill="${type.color ? `${type.color}20` : '#FFFFFF'}"
                    stroke="#9ca3af"
                    stroke-width="${0.5 * scale}"
                    rx="${2 * scale}"
                    ry="${2 * scale}"
                />
            </svg>
        `;
    }

    function createLockerSVG(locker) {
        if (!locker) {
            console.warn('[createLockerSVG] Locker is null/undefined');
            return null;
        }
        
        const type = state.lockerTypes.find(t => t.id === locker.typeId);
        if (!type) {
            console.warn('[createLockerSVG] Type not found for typeId:', locker.typeId);
            // 기본 타입 사용
            const defaultType = { id: 'default', width: 50, height: 60, depth: 50, color: '#cccccc' };
            return createLockerSVGWithType(locker, defaultType);
        }
        
        return createLockerSVGWithType(locker, type);
    }
    
    function createLockerSVGWithType(locker, type) {
        const scale = state.LOCKER_VISUAL_SCALE || 1;
        
        // 안전한 값 처리
        const x = parseFloat(locker.x) || 0;
        const y = parseFloat(locker.y) || 0;
        const rotation = parseFloat(locker.rotation) || 0;
        const width = parseFloat(type.width) || 50;
        const height = parseFloat(type.height) || 60;
        const depth = parseFloat(type.depth) || 50;
        
        const g = document.createElementNS('http://www.w3.org/2000/svg', 'g');
        g.setAttribute('data-locker-id', locker.id);
        g.setAttribute('transform', `translate(${x}, ${y}) rotate(${rotation}, ${(width * scale) / 2}, ${(depth * scale) / 2})`);
        g.style.cursor = 'move';
        
        // 락커 사각형
        const rect = document.createElementNS('http://www.w3.org/2000/svg', 'rect');
        rect.setAttribute('width', width * scale);
        rect.setAttribute('height', (state.currentViewMode === 'floor' ? depth : height) * scale);
        rect.setAttribute('fill', type.color ? `${type.color}20` : '#FFFFFF');
        rect.setAttribute('stroke', state.selectedLockerIds.has(locker.id) ? '#0768AE' : '#9ca3af');
        rect.setAttribute('stroke-width', state.selectedLockerIds.has(locker.id) ? '2' : '1');
        rect.setAttribute('rx', 2 * scale);
        
        // 전면 표시선 제거 (사용자 요청에 따라 제거)
        
        // 락커 번호
        const text = document.createElementNS('http://www.w3.org/2000/svg', 'text');
        text.setAttribute('x', (width * scale) / 2);
        text.setAttribute('y', ((state.currentViewMode === 'floor' ? depth : height) * scale) / 2);
        text.setAttribute('text-anchor', 'middle');
        text.setAttribute('dominant-baseline', 'middle');
        text.setAttribute('fill', '#333');
        text.setAttribute('font-size', '14');
        text.setAttribute('font-weight', 'bold');
        text.textContent = locker.number || (locker.id ? String(locker.id).split('-')[1] : '') || locker.id;
        
        g.appendChild(rect);
        g.appendChild(text);
        
        // 이벤트 핸들러
        g.addEventListener('mousedown', (e) => handleLockerMouseDown(e, locker));
        g.addEventListener('click', (e) => handleLockerClick(e, locker));
        g.addEventListener('contextmenu', (e) => handleLockerContextMenu(e, locker));
        
        return g;
    }

    // ============================================
    // 이벤트 핸들러
    // ============================================
    function setupEventListeners() {
        const canvas = document.getElementById('lockerCanvas');
        if (!canvas) return;
        
        // 캔버스 이벤트
        canvas.addEventListener('mousedown', handleCanvasMouseDown);
        canvas.addEventListener('mousemove', handleCanvasMouseMove);
        canvas.addEventListener('mouseup', handleCanvasMouseUp);
        canvas.addEventListener('wheel', handleCanvasWheel);
        
        // 모드 전환 버튼
        document.querySelectorAll('[data-view-mode]').forEach(btn => {
            btn.addEventListener('click', (e) => {
                const mode = e.currentTarget.dataset.viewMode;
                setViewMode(mode);
            });
        });
        
        // 줌 컨트롤
        const zoomInBtn = document.querySelector('.zoom-in');
        const zoomOutBtn = document.querySelector('.zoom-out');
        const zoomResetBtn = document.querySelector('.zoom-reset');
        
        if (zoomInBtn) zoomInBtn.onclick = () => zoomIn();
        if (zoomOutBtn) zoomOutBtn.onclick = () => zoomOut();
        if (zoomResetBtn) zoomResetBtn.onclick = () => resetZoom();
    }

    function setupKeyboardShortcuts() {
        document.addEventListener('keydown', (e) => {
            // Delete 키: 선택된 락커 삭제
            if (e.key === 'Delete' && state.selectedLockerIds.size > 0) {
                deleteSelectedLockers();
            }
            
            // Ctrl+A: 전체 선택
            if ((e.ctrlKey || e.metaKey) && e.key === 'a') {
                e.preventDefault();
                selectAllLockers();
            }
            
            // Ctrl+C: 복사
            if ((e.ctrlKey || e.metaKey) && e.key === 'c') {
                copySelectedLockers();
            }
            
            // Ctrl+V: 붙여넣기
            if ((e.ctrlKey || e.metaKey) && e.key === 'v') {
                pasteLockers();
            }
            
            // ESC: 선택 해제
            if (e.key === 'Escape') {
                clearSelection();
            }
            
            // P: 평면 모드
            if (e.key === 'p' || e.key === 'P') {
                setViewMode('floor');
            }
            
            // F: 정면 모드
            if (e.key === 'f' || e.key === 'F') {
                setViewMode('front');
            }
            
            // R: 회전
            if (e.key === 'r' || e.key === 'R') {
                rotateSelectedLockers();
            }
        });
    }

    // ============================================
    // 드래그 앤 드롭
    // ============================================
    function handleLockerMouseDown(event, locker) {
        // 정면 모드에서는 드래그 비활성화
        if (state.currentViewMode === 'front') return;
        
        event.stopPropagation();
        
        // Ctrl/Cmd 키로 다중 선택
        if (event.ctrlKey || event.metaKey) {
            toggleLockerSelection(locker);
            return;
        }
        
        // 선택되지 않은 락커 클릭 시 단일 선택
        if (!state.selectedLockerIds.has(locker.id)) {
            clearSelection();
            selectLocker(locker);
        }
        
        // 드래그 시작
        startDragLocker(locker, event);
    }

    function startDragLocker(locker, event) {
        state.isDragging = true;
        state.showSelectionUI = false;
        
        const mousePos = getMousePosition(event);
        state.dragOffset = {
            x: mousePos.x - locker.x,
            y: mousePos.y - locker.y
        };
        
        // 드래그할 락커들 설정
        state.draggedLockers = [];
        state.selectedLockerIds.forEach(id => {
            const l = state.lockers.find(lock => lock.id === id);
            if (l) {
                state.draggedLockers.push({
                    id: l.id,
                    initialX: l.x,
                    initialY: l.y,
                    isLeader: l.id === locker.id
                });
            }
        });
        
        console.log('[Drag] Started with', state.draggedLockers.length, 'lockers');
    }

    function handleCanvasMouseMove(event) {
        if (!state.isDragging || state.draggedLockers.length === 0) return;
        
        const mousePos = getMousePosition(event);
        
        // 리더 락커 찾기
        const leaderInfo = state.draggedLockers.find(d => d.isLeader);
        if (!leaderInfo) return;
        
        const leaderLocker = state.lockers.find(l => l.id === leaderInfo.id);
        if (!leaderLocker) return;
        
        // 새 위치 계산
        const newX = mousePos.x - state.dragOffset.x;
        const newY = mousePos.y - state.dragOffset.y;
        
        // 스냅 적용
        const snappedX = snapToGrid(newX);
        const snappedY = snapToGrid(newY);
        
        // 델타 계산
        const deltaX = snappedX - leaderInfo.initialX;
        const deltaY = snappedY - leaderInfo.initialY;
        
        // 모든 선택된 락커 이동
        state.draggedLockers.forEach(dragInfo => {
            const locker = state.lockers.find(l => l.id === dragInfo.id);
            if (locker) {
                locker.x = dragInfo.initialX + deltaX;
                locker.y = dragInfo.initialY + deltaY;
            }
        });
        
        // 렌더링 업데이트
        renderLockers();
        
        // 정렬 가이드 표시
        showAlignmentGuides(leaderLocker);
    }

    async function handleCanvasMouseUp(event) {
        if (!state.isDragging) return;
        
        // 드래그가 끝난 락커들 저장
        const movedLockers = [...state.draggedLockers];
        
        state.isDragging = false;
        state.showSelectionUI = true;
        state.draggedLockers = [];
        
        // 정렬 가이드 숨기기
        hideAlignmentGuides();
        
        // 선택 UI 업데이트
        updateSelectionUI();
        
        console.log('[Drag] Ended');
        
        // API를 통해 이동된 락커들의 위치 저장
        if (movedLockers.length > 0) {
            for (const locker of movedLockers) {
                try {
                    await window.LockerAPI.saveLocker(locker);
                    console.log('[Enhanced] Saved locker position:', locker.id);
                } catch (error) {
                    console.error('[Enhanced] Failed to save locker position:', locker.id, error);
                }
            }
        }
    }

    function handleCanvasMouseDown(event) {
        // 캔버스 빈 공간 클릭
        if (event.target.id === 'lockerCanvas') {
            // 드래그 선택 시작
            const mousePos = getMousePosition(event);
            state.isDragSelecting = true;
            state.selectionBox.startX = mousePos.x;
            state.selectionBox.startY = mousePos.y;
            state.selectionBox.endX = mousePos.x;
            state.selectionBox.endY = mousePos.y;
            
            // 기존 선택 해제 (Ctrl/Cmd 키가 없으면)
            if (!event.ctrlKey && !event.metaKey) {
                clearSelection();
            }
        }
    }

    function handleCanvasWheel(event) {
        event.preventDefault();
        
        // 마우스 위치 가져오기
        const svg = document.getElementById('lockerCanvas');
        const rect = svg.getBoundingClientRect();
        
        // 마우스의 SVG 좌표 계산
        const mouseX = event.clientX - rect.left;
        const mouseY = event.clientY - rect.top;
        
        // 현재 viewBox 정보
        const currentViewBox = svg.getAttribute('viewBox') || `0 0 ${state.canvasWidth} ${state.canvasHeight}`;
        const [vx, vy, vw, vh] = currentViewBox.split(' ').map(Number);
        
        // SVG 좌표계에서의 마우스 위치
        const svgX = vx + (mouseX / rect.width) * vw;
        const svgY = vy + (mouseY / rect.height) * vh;
        
        // 줌 계산
        const delta = event.deltaY > 0 ? 0.9 : 1.1;
        const oldZoom = state.zoomLevel;
        const newZoom = Math.max(0.5, Math.min(3, oldZoom * delta));
        
        // 줌 레벨 업데이트
        state.zoomLevel = newZoom;
        
        // 새로운 viewBox 크기
        const newVW = state.canvasWidth / newZoom;
        const newVH = state.canvasHeight / newZoom;
        
        // 마우스 포인터를 중심으로 줌이 되도록 viewBox 위치 조정
        const zoomRatio = oldZoom / newZoom;
        const newVX = svgX - (svgX - vx) * zoomRatio - (mouseX / rect.width) * (newVW - vw);
        const newVY = svgY - (svgY - vy) * zoomRatio - (mouseY / rect.height) * (newVH - vh);
        
        // viewBox 업데이트
        svg.setAttribute('viewBox', `${newVX} ${newVY} ${newVW} ${newVH}`);
        
        // panOffset 업데이트 (다른 기능과의 호환성을 위해)
        state.panOffset = { x: -newVX, y: -newVY };
    }

    // ============================================
    // 뷰 모드 전환
    // ============================================
    function setViewMode(mode) {
        console.log('[ViewMode] Switching to:', mode);
        
        // 평면 모드로 전환 시 애니메이션 플래그
        if (mode === 'floor' && state.currentViewMode === 'front') {
            state.isTransitioningToFloor = true;
            setTimeout(() => {
                state.isTransitioningToFloor = false;
            }, 400);
        }
        
        state.currentViewMode = mode;
        
        // UI 업데이트
        document.querySelectorAll('[data-view-mode]').forEach(btn => {
            btn.classList.toggle('active', btn.dataset.viewMode === mode);
        });
        
        // 캔버스 재렌더링
        renderLockers();
        
        // 자동 맞춤
        setTimeout(() => {
            autoFitLockers();
        }, 50);
    }

    // ============================================
    // 선택 관리
    // ============================================
    function selectLocker(locker) {
        state.selectedLocker = locker;
        state.selectedLockerIds.add(locker.id);
        renderLockers();
    }

    function toggleLockerSelection(locker) {
        if (state.selectedLockerIds.has(locker.id)) {
            state.selectedLockerIds.delete(locker.id);
            if (state.selectedLocker?.id === locker.id) {
                state.selectedLocker = null;
            }
        } else {
            state.selectedLockerIds.add(locker.id);
            state.selectedLocker = locker;
        }
        renderLockers();
    }

    function clearSelection() {
        state.selectedLocker = null;
        state.selectedLockerIds.clear();
        renderLockers();
    }

    function selectAllLockers() {
        const currentLockers = state.lockers.filter(l => l.zoneId === state.selectedZone?.id);
        currentLockers.forEach(locker => {
            state.selectedLockerIds.add(locker.id);
        });
        if (currentLockers.length > 0) {
            state.selectedLocker = currentLockers[0];
        }
        renderLockers();
    }

    // ============================================
    // 스냅 및 정렬
    // ============================================
    function snapToGrid(value) {
        return Math.round(value / state.GRID_SIZE) * state.GRID_SIZE;
    }

    function snapToAdjacent(x, y, width, height, lockerId, rotation) {
        let snappedX = x;
        let snappedY = y;
        
        const currentLockers = state.lockers.filter(l => 
            l.zoneId === state.selectedZone?.id && l.id !== lockerId
        );
        
        currentLockers.forEach(other => {
            const type = state.lockerTypes.find(t => t.id === other.typeId);
            if (!type) return;
            
            const otherWidth = type.width * state.LOCKER_VISUAL_SCALE;
            const otherHeight = (state.currentViewMode === 'floor' ? type.depth : type.height) * state.LOCKER_VISUAL_SCALE;
            
            // 수평 정렬
            if (Math.abs(x - other.x) < state.SNAP_THRESHOLD) {
                snappedX = other.x;
            }
            if (Math.abs(x - (other.x + otherWidth)) < state.SNAP_THRESHOLD) {
                snappedX = other.x + otherWidth;
            }
            
            // 수직 정렬
            if (Math.abs(y - other.y) < state.SNAP_THRESHOLD) {
                snappedY = other.y;
            }
            if (Math.abs(y - (other.y + otherHeight)) < state.SNAP_THRESHOLD) {
                snappedY = other.y + otherHeight;
            }
        });
        
        return { x: snappedX, y: snappedY };
    }

    function showAlignmentGuides(locker) {
        // 정렬 가이드 라인 표시 로직
        // SVG에 임시 라인 추가
    }

    function hideAlignmentGuides() {
        // 정렬 가이드 라인 숨기기
    }

    // ============================================
    // 줌 및 팬
    // ============================================
    function zoomIn() {
        state.zoomLevel = Math.min(state.zoomLevel * 1.2, 3);
        applyZoom();
    }

    function zoomOut() {
        state.zoomLevel = Math.max(state.zoomLevel / 1.2, 0.5);
        applyZoom();
    }

    function resetZoom() {
        state.zoomLevel = 1;
        state.panOffset = { x: 0, y: 0 };
        applyZoom();
    }

    function applyZoom() {
        const svg = document.getElementById('lockerCanvas');
        if (!svg) return;
        
        const viewBox = `${-state.panOffset.x} ${-state.panOffset.y} ${state.canvasWidth / state.zoomLevel} ${state.canvasHeight / state.zoomLevel}`;
        svg.setAttribute('viewBox', viewBox);
    }

    function autoFitLockers() {
        const currentLockers = state.lockers.filter(l => l.zoneId === state.selectedZone?.id);
        if (currentLockers.length === 0) {
            resetZoom();
            return;
        }
        
        let minX = Infinity, minY = Infinity;
        let maxX = -Infinity, maxY = -Infinity;
        
        currentLockers.forEach(locker => {
            const type = state.lockerTypes.find(t => t.id === locker.typeId);
            if (!type) return;
            
            const width = type.width * state.LOCKER_VISUAL_SCALE;
            const height = (state.currentViewMode === 'floor' ? type.depth : type.height) * state.LOCKER_VISUAL_SCALE;
            
            minX = Math.min(minX, locker.x);
            minY = Math.min(minY, locker.y);
            maxX = Math.max(maxX, locker.x + width);
            maxY = Math.max(maxY, locker.y + height);
        });
        
        const padding = 50;
        const contentWidth = maxX - minX + padding * 2;
        const contentHeight = maxY - minY + padding * 2;
        
        const scaleX = state.canvasWidth / contentWidth;
        const scaleY = state.canvasHeight / contentHeight;
        state.zoomLevel = Math.min(scaleX, scaleY, 1);
        
        state.panOffset = {
            x: minX - padding,
            y: minY - padding
        };
        
        applyZoom();
    }

    // ============================================
    // 유틸리티 함수
    // ============================================
    function getMousePosition(event) {
        const svg = document.getElementById('lockerCanvas');
        const rect = svg.getBoundingClientRect();
        
        return {
            x: (event.clientX - rect.left) / state.zoomLevel + state.panOffset.x,
            y: (event.clientY - rect.top) / state.zoomLevel + state.panOffset.y
        };
    }

    function initializeCanvas() {
        const svg = document.getElementById('lockerCanvas');
        if (!svg) return;
        
        // 캔버스 크기 설정
        const container = svg.parentElement;
        state.canvasWidth = container.clientWidth;
        state.canvasHeight = container.clientHeight;
        
        svg.setAttribute('width', state.canvasWidth);
        svg.setAttribute('height', state.canvasHeight);
        svg.setAttribute('viewBox', `0 0 ${state.canvasWidth} ${state.canvasHeight}`);
        
        // 그리드 패턴 생성
        createGridPattern(svg);
    }

    function createGridPattern(svg) {
        const defs = document.createElementNS('http://www.w3.org/2000/svg', 'defs');
        
        const pattern = document.createElementNS('http://www.w3.org/2000/svg', 'pattern');
        pattern.setAttribute('id', 'grid');
        pattern.setAttribute('width', state.GRID_SIZE);
        pattern.setAttribute('height', state.GRID_SIZE);
        pattern.setAttribute('patternUnits', 'userSpaceOnUse');
        
        const line1 = document.createElementNS('http://www.w3.org/2000/svg', 'line');
        line1.setAttribute('x1', '0');
        line1.setAttribute('y1', '0');
        line1.setAttribute('x2', state.GRID_SIZE);
        line1.setAttribute('y2', '0');
        line1.setAttribute('stroke', '#e5e7eb');
        line1.setAttribute('stroke-width', '0.5');
        
        const line2 = document.createElementNS('http://www.w3.org/2000/svg', 'line');
        line2.setAttribute('x1', '0');
        line2.setAttribute('y1', '0');
        line2.setAttribute('x2', '0');
        line2.setAttribute('y2', state.GRID_SIZE);
        line2.setAttribute('stroke', '#e5e7eb');
        line2.setAttribute('stroke-width', '0.5');
        
        pattern.appendChild(line1);
        pattern.appendChild(line2);
        defs.appendChild(pattern);
        
        // 배경 사각형
        const bg = document.createElementNS('http://www.w3.org/2000/svg', 'rect');
        bg.setAttribute('width', '100%');
        bg.setAttribute('height', '100%');
        bg.setAttribute('fill', 'url(#grid)');
        
        svg.appendChild(defs);
        svg.insertBefore(bg, svg.firstChild);
    }

    function updateSelectionUI() {
        // 선택 UI 업데이트 (삭제, 회전 버튼 등)
    }

    // ============================================
    // 락커 작업 함수
    // ============================================
    function selectLockerType(type) {
        state.selectedType = type;
        renderLockerTypes();
    }

    async function addLockerByDoubleClick(type) {
        if (state.currentViewMode !== 'floor') {
            alert('평면배치모드에서만 락커를 추가할 수 있습니다.');
            return;
        }
        
        if (!state.selectedZone) {
            alert('구역을 선택해주세요.');
            return;
        }
        
        const newLocker = {
            id: `locker-${Date.now()}`,
            typeId: type.id,
            x: 100,
            y: 100,
            rotation: 0,
            zoneId: state.selectedZone.id,
            number: generateLockerNumber(),
            compCd: window.LockerConfig?.companyCode || '001',
            bcoffCd: window.LockerConfig?.officeCode || '001'
        };
        
        try {
            // API를 통해 락커 저장
            const savedLocker = await window.LockerAPI.saveLocker(newLocker);
            if (savedLocker) {
                state.lockers.push(savedLocker);
                renderLockers();
                selectLocker(savedLocker);
                console.log('[Enhanced] Locker created:', savedLocker);
            }
        } catch (error) {
            console.error('[Enhanced] Failed to create locker:', error);
            // 폴백: 로컬에만 추가
            state.lockers.push(newLocker);
            renderLockers();
            selectLocker(newLocker);
        }
    }

    async function deleteSelectedLockers() {
        if (state.selectedLockerIds.size === 0) return;
        
        if (confirm(`선택된 ${state.selectedLockerIds.size}개의 락커를 삭제하시겠습니까?`)) {
            const lockersToDelete = Array.from(state.selectedLockerIds);
            const deletePromises = [];
            
            // API를 통해 각 락커 삭제
            for (const lockerId of lockersToDelete) {
                const locker = state.lockers.find(l => l.id === lockerId);
                if (locker && locker.lockrCd) {
                    deletePromises.push(
                        window.LockerAPI.deleteLocker(locker.lockrCd)
                            .then(success => ({ lockerId, success }))
                            .catch(() => ({ lockerId, success: false }))
                    );
                }
            }
            
            // 모든 삭제 요청 처리
            if (deletePromises.length > 0) {
                const results = await Promise.all(deletePromises);
                const successfulDeletes = results.filter(r => r.success).map(r => r.lockerId);
                
                // 성공적으로 삭제된 락커들을 로컬 상태에서 제거
                state.lockers = state.lockers.filter(l => !successfulDeletes.includes(l.id));
                
                console.log('[Enhanced] Deleted lockers:', successfulDeletes);
            } else {
                // API 없이 로컬에서만 삭제
                state.lockers = state.lockers.filter(l => !state.selectedLockerIds.has(l.id));
            }
            
            clearSelection();
            renderLockers();
        }
    }

    function rotateSelectedLockers() {
        state.selectedLockerIds.forEach(id => {
            const locker = state.lockers.find(l => l.id === id);
            if (locker) {
                locker.rotation = (locker.rotation + 90) % 360;
            }
        });
        renderLockers();
    }

    function deleteLockerType(type) {
        if (confirm(`'${type.name}' 타입을 삭제하시겠습니까?`)) {
            type.isHidden = true;
            renderLockerTypes();
        }
    }

    function selectZone(zone) {
        console.log('[Enhanced] Selecting zone:', zone);
        // zone이 객체면 id 사용, 문자열이면 그대로 사용
        state.selectedZone = typeof zone === 'object' ? zone.id : zone;
        renderZoneTabs();
        renderLockers(); // loadLockers 대신 직접 renderLockers 호출
    }
    
    // View에서 호출되는 switchZone 함수 (zone ID를 받음)
    function switchZone(zoneId) {
        console.log('[Enhanced] Switching to zone ID:', zoneId);
        state.selectedZone = zoneId;
        state.hasAutoFitted = false; // 구역 전환 시 자동 맞춤 리셋
        
        // 해당 구역의 탭을 활성화
        renderZoneTabs();
        
        // 해당 구역의 락커들을 렌더링
        renderLockers();
    }

    function generateLockerNumber() {
        const prefix = state.selectedZone?.name?.[0] || 'L';
        const count = state.lockers.filter(l => l.zoneId === state.selectedZone?.id).length;
        return `${prefix}${String(count + 1).padStart(3, '0')}`;
    }

    function handleLockerClick(event, locker) {
        if (state.lockerDragJustFinished) {
            state.lockerDragJustFinished = false;
            return;
        }
        
        if (event.ctrlKey || event.metaKey) {
            toggleLockerSelection(locker);
        } else {
            clearSelection();
            selectLocker(locker);
        }
    }

    function handleLockerContextMenu(event, locker) {
        event.preventDefault();
        showContextMenu(event, locker);
    }

    function showContextMenu(event, locker) {
        state.contextMenuVisible = true;
        state.contextMenuPosition = {
            x: event.clientX,
            y: event.clientY
        };
        // 컨텍스트 메뉴 표시 로직
    }

    function showZoneContextMenu(event, zone) {
        event.preventDefault();
        // 구역 컨텍스트 메뉴 표시
    }

    // 클립보드 기능
    let clipboard = [];

    function copySelectedLockers() {
        clipboard = [];
        state.selectedLockerIds.forEach(id => {
            const locker = state.lockers.find(l => l.id === id);
            if (locker) {
                clipboard.push({ ...locker });
            }
        });
        console.log('[Clipboard] Copied', clipboard.length, 'lockers');
    }

    function pasteLockers() {
        if (clipboard.length === 0) return;
        
        const offset = 20;
        clipboard.forEach(original => {
            const newLocker = {
                ...original,
                id: `locker-${Date.now()}-${Math.random()}`,
                x: original.x + offset,
                y: original.y + offset,
                number: generateLockerNumber()
            };
            state.lockers.push(newLocker);
        });
        
        renderLockers();
    }

    // 레이아웃 저장
    async function saveLayout() {
        try {
            console.log('[Layout] Saving...', state.lockers.length, 'lockers');
            
            // 모든 락커 저장
            const savePromises = state.lockers.map(locker => 
                window.LockerAPI.saveLocker(locker)
            );
            
            const results = await Promise.all(savePromises);
            console.log('[Layout] Saved successfully:', results.length, 'lockers');
            alert('레이아웃이 저장되었습니다.');
            
            return true;
        } catch (error) {
            console.error('[Layout] Save failed:', error);
            alert('레이아웃 저장에 실패했습니다.');
            return false;
        }
    }

    // 전역 함수 노출
    window.LockerPlacement = {
        state,
        setViewMode,
        zoomIn,
        zoomOut,
        resetZoom,
        autoFitLockers,
        deleteSelectedLockers,
        rotateSelectedLockers,
        selectAllLockers,
        clearSelection,
        saveLayout,
        pasteLockers,
        addLockerByDoubleClick,
        selectZone,
        switchZone,
        loadLockers,
        loadZones,
        loadLockerTypes
    };

})();